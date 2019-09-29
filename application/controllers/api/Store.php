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
class Store extends REST_Controller {
    private $services = null;
    private $name = null;
    private $parent_page = 'user';
	private $current_page = 'user/store/';

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->library('services/Store_services');
        $this->services = new Store_services;

        $this->load->model( array( 
			'category_model',
			'product_model' ,
			'store_model' ,
        ));
        $this->store_model->set_message_delimiters( '', '' );
        $this->store_model->set_error_delimiters( '', '' );
    }
    protected function user_check( $user_id )
    {
        $user = $this->ion_auth->user( $user_id )->row();
        if ($user === NULL)
        {
            $result = array(
                "message" => "User Tidak ada",
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
            return FALSE;
        }
        return $user->id;
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
   
    protected function store_check_by_user_id( $user_id )
    {
        $store = $this->store_model->store_by_user_id(  $user_id )->row();        
        if ( $store != NULL)
        {
            $result = array(
                "message" =>  "Sudah Ada Store", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return FALSE;
        }
        return $store->id;
    }
#################################################################################

    public function stores_get()
    {
        $group_id    = $this->get('group_id', 0);
        $group_id = ( $group_id != NULL ) ? $group_id : NULL ;

        $page    = $this->get('page', 0);
        $page = ( $page != NULL ) ? $page : 0 ;

        $limit_per_page = 10;
        $start = $limit_per_page * $page;

        $stores = $this->store_model->stores( $start , $limit_per_page, $group_id )->result();
        $this->set_response( $stores , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function user_store_get(  )
    {
        $user_id    = $this->get('user_id', 0);
        if ( $user_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
        if( ( $user_id = $this->user_check( $user_id ) )  == FALSE ) return;
        
        $store = $this->store_model->store_by_user_id(  $user_id )->row();
        if ($store === NULL)
        {
            $result = array(
                "message" =>  "Belum Ada Store", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
        $this->set_response( $store , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    
    public function store_get(  )
    {
        $store_id    = $this->get('store_id', 0);
        if ($store_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }

        $store = $this->store_model->store( $store_id )->row();
        $data["hit"] = $store->hit +=1;

        $data_param["id"] = $store->id;
        $this->store_model->update( $data, $data_param );
        $this->set_response( $store , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
###########################################################################
    public function create_post(  )
    {
        $user_id = $this->input->post('user_id') ;
        if ( $user_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
        if( ( $user_id = $this->user_check( $user_id ) )  == FALSE ) return;

        if( ( $store_id = $this->store_check_by_user_id( $user_id ) )  == FALSE ) return;

        $this->form_validation->set_rules( $this->services->validation_config(  ) );
		if ( $this->form_validation->run() === TRUE )
		{
			$data['name'] = $this->input->post( 'name' );
			$data['start_date'] = strtotime( $this->input->post( 'start_date' ) );
			$data['description'] = $this->input->post( 'description' );
			$data['address'] = $this->input->post( 'address' );
			$data['user_id'] = $user_id ;

			

			$data['timestamp'] = time();	
			// upload photo		
			$this->load->library('upload'); // Load librari upload
			$config = $this->services->get_photo_upload_config( $data['name'] );

			$this->upload->initialize( $config );
			if( $this->upload->do_upload("image") )
			{
				$data['image'] = $this->upload->data()["file_name"];
				// if( !@unlink( $config['upload_path'].$this->input->post( 'file' ) ) );
			}
			else
			{
				$data['image'] = "default.png";
				// $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->upload->display_errors() ) );
				// redirect( site_url($this->current_page."create/")  );				
			}
			
			// echo var_dump( $data ); return ;
			if( $this->store_model->create( $data ) )
			{
                $result = array(
                    "message" => $this->store_model->messages(),
                    "status" => 1,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
            }else{
                $result = array(
                    "message" => $this->store_model->errors(),
                    "status" => 0,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
            }
			
		}
		else
		{
            $this->form_validation->set_error_delimiters('', ''); 
            $message = (validation_errors() ? validation_errors() : ($this->store_model->errors() ? $this->store_model->errors() : $this->session->flashdata('message')));
            $message = str_replace( '<b>', ' ', $message );
            $message = str_replace( '</b>', ' ', $message );

			$result = array(
                "message" => $message ,
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
        
    }

    public function edit_post(  )
    {
        $store_id = $this->input->post('store_id') ;
        if ( $store_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
        if( ( $store_id = $this->store_check( $store_id ) )  == FALSE ) return;

        // $validation_config = $this->services->validation_config(  );
        $this->form_validation->set_rules( $this->services->validation_config(  ) );
		if ( $this->form_validation->run() === TRUE )
		{
			$data['name'] = $this->input->post( 'name' );
			$data['start_date'] = strtotime( $this->input->post( 'start_date' ) );
			$data['description'] = $this->input->post( 'description' );
			$data['address'] = $this->input->post( 'address' );

			$data_param["id"] = $store_id;
			
			if( $this->store_model->update( $data, $data_param ) )			
			{
                $result = array(
                    "message" => $this->store_model->messages(),
                    "status" => 1,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
            }else{
                $result = array(
                    "message" => $this->store_model->errors(),
                    "status" => 0,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
            }
			
		}
		else
		{
            $this->form_validation->set_error_delimiters('', ''); 
            $message = (validation_errors() ? validation_errors() : ($this->store_model->errors() ? $this->store_model->errors() : $this->session->flashdata('message')));
            $message = str_replace( '<b>', ' ', $message );
            $message = str_replace( '</b>', ' ', $message );

			$result = array(
                "message" => $message ,
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
        
    }

    public function upload_photo_post()
	{
        $store_id = $this->input->post('store_id') ;
        if ( $store_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
        if( ( $store_id = $this->store_check( $store_id ) )  == FALSE ) return;

		$data['name'] = $this->input->post( 'name' );
		// $data['image'] = $this->input->post( 'old_image' );

		$this->load->library('upload'); // Load librari upload
		$config = $this->services->get_photo_upload_config( $data['name'] );

		$this->upload->initialize( $config );
	
		if( $this->upload->do_upload("image") )
		{
            $store = $this->store_model->store(  $store_id )->row();
			$data['image'] = $this->upload->data()["file_name"];
			if( $store->image != "default.png" )
				if( !@unlink( $config['upload_path']. $store->image ) );
		}
		else
		{
			// $data['image'] = "default.png";
			$this->form_validation->set_error_delimiters('', ''); 
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
		}
		
        $data_param["id"] = $store_id;		
		if( $this->store_model->update( $data, $data_param ) )			
        {
            $result = array(
                "message" => $this->store_model->messages(),
                "status" => 1,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }else{
            $result = array(
                "message" => $this->store_model->errors(),
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
    }
    
}
