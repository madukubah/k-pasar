<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Gallery extends REST_Controller {
	const IMAGE_TYPE = 1;

    private $services = null;
    private $name = null;
    private $parent_page = 'user';
	private $current_page = 'user/gallery/';
	private $store;
	
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->library('services/Gallery_services');
		$this->services = new Gallery_services;
        
        $this->load->model( array( 
			'category_model',
			'gallery_model' ,
			'store_model' ,
        ));
        $this->gallery_model->set_message_delimiters( '', '' );
        $this->gallery_model->set_error_delimiters( '', '' );


    }
    protected function store_check( $store_id )
    {
        $store = $this->store_model->store(  $store_id )->row();
        if ($store === NULL)
        {
            $result = array(
                "message" =>  "Belum Ada Store", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return FALSE;
        }
        return $store->id;
    }
    protected function gallery_check( $gallery_id )
    {
        $gallery = $this->gallery_model->gallery( $gallery_id )->row();
        if ($gallery === NULL)
        {
            $result = array(
                "message" =>  "Produk Tidak ada", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return FALSE;
        }
        return $gallery->id;
    }
##################################################################################
    public function galleries_get()
    {
        $category_id    = $this->get('category_id', 0);
        $category_id = ( $category_id != NULL ) ? $category_id : NULL ;

        $page    = $this->get('page', 0);
        $page = ( $page != NULL ) ? $page : 0 ;

        $limit_per_page = 10;
        $start = $limit_per_page * $page;

        $galleries = $this->gallery_model->galleries( Gallery::IMAGE_TYPE,  $start , NULL, $category_id )->result();
        $this->set_response( $galleries , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function user_galleries_get(  )
    {
        $user_id    = $this->get('user_id', 0);
        if ($user_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
        $store = $this->store_model->store_by_user_id(  $user_id )->row();
        if ($store === NULL)
        {
            $result = array(
                "message" =>  "Belum Ada Store", 
                "status" => 1,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
        $page    = $this->get('page', 0);
        $page = ( $page != NULL ) ? $page : 0 ;

        $limit_per_page = NULL;
        $start = $limit_per_page * $page;

        $galleries = $this->gallery_model->galleries_by_store_id( $store->id, Gallery::IMAGE_TYPE,  $start, $limit_per_page )->result();
        $this->set_response( $galleries , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    
###########################################################################
    public function create_post(  )
    {

        if( ( $store_id = $this->store_check( $this->input->post( 'store_id' ) ) )  == FALSE ) return;

        $this->form_validation->set_rules( $this->services->validation_config(  ) );
        if ( $this->form_validation->run() === TRUE )
        {
            $data['name'] = $this->input->post( 'name' );
			$data['description'] = $this->input->post( 'description' );
			$data['type'] = Gallery::IMAGE_TYPE;

            

            // upload photo		
            $this->load->library('upload'); // Load librari upload
            $config = $this->services->get_photo_upload_config( $data['name'] );

            $this->upload->initialize( $config );
            if( $this->upload->do_upload("file_image") )
            {
                $data['file'] = $this->upload->data()["file_name"];
                // if( !@unlink( $config['upload_path'].$this->input->post( 'file' ) ) );
            }
            else
            {
                $message = $this->upload->display_errors() ;
                $message = str_replace( '<b>', ' ', $message );
                $message = str_replace( '</b>', ' ', $message );
                $message = str_replace( '<p>', ' ', $message );
                $message = str_replace( '</p>', ' ', $message );
                $result = array(
                    "message" => $message ,
                    "status" => 0,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
                return;
            }
            // echo var_dump( $data ); return ;
			if( $gallery_id = $this->gallery_model->create( $data ) )            
            {
                $this->load->model(
					array(
						'store_gallery_model',
					)
				);
				$_data["store_id"] 		= $store_id ;
				$_data["gallery_id"] 	= $gallery_id ;
				// echo var_dump( $_data );return;				
                $this->store_gallery_model->create( $_data );
                
                $result = array(
                    "message" => $this->gallery_model->messages(),
                    "status" => 1,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
            }else{
                $result = array(
                    "message" => $this->gallery_model->errors(),
                    "status" => 0,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
            }
            
        }
        else
        {
            $message = (validation_errors() ? validation_errors() : ($this->gallery_model->errors() ? $this->gallery_model->errors() : $this->session->flashdata('message')));
            $message = str_replace( '<b>', ' ', $message );
            $message = str_replace( '</b>', ' ', $message );

            $result = array(
                "message" => $message ,
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
        
    }


    public function delete_post(  ) 
	{
        
        $gallery_id    = $this->input->post( 'gallery_id' );
        if ($gallery_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
        if( ( $gallery_id = $this->gallery_check( $this->input->post( 'gallery_id' ) ) )  == FALSE ) return;

        $data_param['id'] 	= $gallery_id;
        $gallery = $this->gallery_model->gallery( $data_param['id'] )->row();
        if( $this->gallery_model->delete( $data_param ) )
        {
            $image_url = $this->services->get_photo_upload_config( "" )["upload_path"];
			if( !@unlink( $image_url . $gallery->file ) )return;
            $result = array(
                "message" => $this->gallery_model->messages(),
                "status" => 1,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
		}else{
            $result = array(
                "message" => $this->gallery_model->errors(),
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
		}
	}
}
