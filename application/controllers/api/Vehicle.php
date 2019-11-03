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
class Vehicle extends REST_Controller {
    private $services = null;
    private $name = null;
    private $parent_page = 'user';
	private $current_page = 'user/vehicle/';
	private $store;
	
	public function __construct(){
        // Construct the parent class
        parent::__construct();
        $this->load->library('services/Vehicle_services');
		$this->services = new Vehicle_services;
        
        $this->load->model( array( 
			'category_model',
			'vehicle_model' ,
			'store_model' ,
        ));
        $this->vehicle_model->set_message_delimiters( '', '' );
        $this->vehicle_model->set_error_delimiters( '', '' );
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
    protected function vehicle_check( $vehicle_id )
    {
        $vehicle = $this->vehicle_model->vehicle( $vehicle_id )->row();
        if ($vehicle === NULL)
        {
            $result = array(
                "message" =>  "Produk Tidak ada", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return FALSE;
        }
        return $vehicle->id;
    }
##################################################################################
    public function vehicles_get()
    {
        $category_id    = $this->get('category_id', 0);
        $category_id = ( $category_id != NULL ) ? $category_id : NULL ;

        $page    = $this->get('page', 0);
        $page = ( $page != NULL ) ? $page : 0 ;

        $limit_per_page = 2;
        $start = $limit_per_page * $page;

        $vehicles = $this->vehicle_model->vehicles( $start , $limit_per_page, $category_id )->result();
        $this->set_response( $vehicles , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function user_vehicles_get(  )
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
        $vehicles = $this->vehicle_model->vehicle_by_store_id( $store->id )->result();
        $this->set_response( $vehicles , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    
    public function vehicle_get(  )
    {
        $vehicle_id    = $this->get('vehicle_id', 0);
        if ($vehicle_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }

        $vehicle = $this->vehicle_model->vehicle( $vehicle_id )->row();
        if ($vehicle === NULL)
        {
            $this->set_response( array() , REST_Controller::HTTP_OK); 
            return;
        }
        $data["hit"] = $vehicle->hit +=1;

        $data_param["id"] = $vehicle->id;
        $this->vehicle_model->update( $data, $data_param );
        $this->set_response( $vehicle , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
###########################################################################
    public function create_post(  )
    {

        if( ( $store_id = $this->store_check( $this->input->post( 'store_id' ) ) )  == FALSE ) return;

        $this->form_validation->set_rules( $this->services->validation_config(  ) );
        if ( $this->form_validation->run() === TRUE )
        {
            $data['category_id'] 	= $this->input->post( 'category_id' );
			$data['name'] 			= $this->input->post( 'name' );
			$data['description'] 	= $this->input->post( 'description' );
			$data['capacity'] 		= $this->input->post( 'capacity' );
			$data['police_number'] 	= $this->input->post( 'police_number' );
			$data['unit'] 			= $this->input->post( 'unit' );
			$data['timestamp'] 		= time();
			$data['store_id'] 		= $store_id;

            

            // upload photo		
            $this->load->library('upload'); // Load librari upload
            $config = $this->services->get_photo_upload_config( $data['name'] );

            $this->upload->initialize( $config );
            if( $this->upload->do_upload("image") )
            {
                $data['images'] = $this->upload->data()["file_name"];
                // if( !@unlink( $config['upload_path'].$this->input->post( 'file' ) ) );
            }
            else
            {
                $data['images'] = "default.png";
                // $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->upload->display_errors() ) );
                // redirect( site_url($this->current_page."create/")  );				
            }
            
            // echo var_dump( $data ); return ;
			if( $this->vehicle_model->create( $data ) )            
            {
                $result = array(
                    "message" => $this->vehicle_model->messages(),
                    "status" => 1,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
            }else{
                $result = array(
                    "message" => $this->vehicle_model->errors(),
                    "status" => 0,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
            }
            
        }
        else
        {
            $this->form_validation->set_error_delimiters('', ''); 
            $message = (validation_errors() ? validation_errors() : ($this->vehicle_model->errors() ? $this->vehicle_model->errors() : $this->session->flashdata('message')));
            $message = str_replace( '<b>', '', $message );
            $message = str_replace( '</b>', '', $message );

            $result = array(
                "message" => $message ,
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
        
    }

    public function edit_post(  )
    {
        $vehicle_id    = $this->input->post( 'vehicle_id' );
        if ($vehicle_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
        if( ( $vehicle_id = $this->vehicle_check( $vehicle_id ) )  == FALSE ) return;

        $this->form_validation->set_rules( $this->services->validation_config(  ) );
        if ( $this->form_validation->run() === TRUE )
        {
            $data['category_id'] 	= $this->input->post( 'category_id' );
			$data['name'] 			= $this->input->post( 'name' );
			$data['description'] 	= $this->input->post( 'description' );
			$data['capacity'] 		= $this->input->post( 'capacity' );
			$data['police_number'] 	= $this->input->post( 'police_number' );
			$data['unit'] 			= $this->input->post( 'unit' );
            
            $data_param["id"] = $vehicle_id;

			if( $this->vehicle_model->update( $data, $data_param ) )     
            {
                $result = array(
                    "message" => $this->vehicle_model->messages(),
                    "status" => 1,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
            }else{
                $result = array(
                    "message" => $this->vehicle_model->errors(),
                    "status" => 0,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
            }
            
        }
        else
        {
            $this->form_validation->set_error_delimiters('', ''); 
            $message = (validation_errors() ? validation_errors() : ($this->vehicle_model->errors() ? $this->vehicle_model->errors() : $this->session->flashdata('message')));
            $message = str_replace( '<b>', '', $message );
            $message = str_replace( '</b>', '', $message );

            $result = array(
                "message" => $message ,
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
        
    }

    public function upload_photo_post()
	{
		$vehicle_id    = $this->input->post( 'vehicle_id' );
        if ($vehicle_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
        if( ( $vehicle_id = $this->vehicle_check( $vehicle_id ) )  == FALSE ) return;

        $vehicle = $this->vehicle_model->vehicle( $vehicle_id )->row();
		// upload photo		
		$this->load->library('upload'); // Load librari upload
		$config = $this->services->get_photo_upload_config( $vehicle->name );

		$this->upload->initialize( $config );
		if( $this->upload->do_upload("image") )
		{
			$image		 	= $this->upload->data()["file_name"];
			
			$images = explode(";", $vehicle->images );
			// unset( $images[ $this->input->post( 'image_index' ) ] );
            
            $old_image = $images[ $this->input->post( 'image_index' ) ];
            if( $old_image != "default.png" )
				if( !@unlink( $config['upload_path'] . $old_image ) ){};

            $images[ $this->input->post( 'image_index' ) ] = $image;
			$data['images'] 	= implode(";", $images );
		}
		else
		{
			// $data['image'] = "default.png";
			$this->form_validation->set_error_delimiters('', ''); 
            $message = $this->upload->display_errors() ;
            $message = str_replace( '<b>', '', $message );
            $message = str_replace( '</b>', '', $message );

			$result = array(
                "message" => $message ,
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code		
		}

		$data_param["id"] = $vehicle_id;

        if( $this->vehicle_model->update( $data, $data_param ) )     
        {
            $result = array(
                "message" => $this->vehicle_model->messages(),
                "status" => 1,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }else{
            $result = array(
                "message" => $this->vehicle_model->errors(),
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
    }
    
    public function delete_post(  ) 
	{
        $vehicle_id    = $this->input->post( 'vehicle_id' );
        if ($vehicle_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
        if( ( $vehicle_id = $this->vehicle_check( $vehicle_id ) )  == FALSE ) return;

		$data_param["id"] = $vehicle_id;		

		$vehicle = $this->vehicle_model->vehicle( $data_param['id'] )->row();
		if( $vehicle == NULL ) redirect( site_url($this->current_page) );
		
        if( $this->vehicle_model->delete( $data_param ) )
        {
			$image_url = $this->services->get_photo_upload_config( "" )["upload_path"];
			$images = explode(";", $vehicle->images );
			foreach( $images as $i => $image ):
				if( !@unlink( $image_url.$image ) ){};
			endforeach;
            $result = array(
                "message" => $this->vehicle_model->messages(),
                "status" => 1,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
        else
        {
            $result = array(
                "message" => $this->vehicle_model->errors(),
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
		}
	}
    
}
