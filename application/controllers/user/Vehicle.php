<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vehicle extends User_Controller {
	const IMAGE_TYPE = 4;
	private $services = null;
    private $name = null;
    private $parent_page = 'user';
	private $current_page = 'user/vehicle/';
	private $store;
	
	public function __construct(){
		parent::__construct();
		$this->load->library('services/Vehicle_services');
		$this->services = new Vehicle_services;
		$this->load->model(
				array(
				'vehicle_model',
				'store_model',
			)
		);

		$user_id = $this->ion_auth->get_user_id();
		$this->store = $this->store_model->store_by_user_id(  $user_id )->row();
		// echo var_dump( $store );return;
		if( $this->store == NULL )
		{
			redirect( site_url(  )."user/store"  );	
		}
		$this->data[ "menu_list_id" ] = "vehicle_index";
	}

	public function upload_photo()
	{
		$name = $this->input->post( 'name' );
		$vehicle_id = $this->input->post( 'vehicle_id' );
		$vehicle = $this->vehicle_model->vehicle( $vehicle_id )->row();
		// upload photo		
		$this->load->library('upload'); // Load librari upload
		$config = $this->services->get_photo_upload_config( $name );

		$this->upload->initialize( $config );
		// echo var_dump( $_FILES['images'] ); return;
		// if( $_FILES['image']['name'] != "" )
		if( $this->upload->do_upload("image") )
		{
			$image		 	= $this->upload->data()["file_name"];
			
			if( $this->input->post( 'old_image' ) != "default.png" )
				if( !@unlink( $config['upload_path'].$this->input->post( 'old_image' ) ) ){};

			
			$images = explode(";", $vehicle->images );
			// unset( $images[ $this->input->post( 'image_index' ) ] );
			
			// $images []= $image;
			$images[ $this->input->post( 'image_index' ) ] = $image;
			$data['images'] 	= implode(";", $images );
		}
		else
		{
			// $data['image'] = "default.png";
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->upload->display_errors() ) );
			redirect( site_url($this->current_page)."edit/".$vehicle->id  );			
		}

		$data_param["id"] = $this->input->post( 'vehicle_id' );
		// echo var_dump( $data ); return ;
		if( $this->vehicle_model->update( $data, $data_param ) )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->vehicle_model->messages() ) );
		}else{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->vehicle_model->errors() ) );
		}
		
		redirect( site_url($this->current_page)."edit/".$vehicle->id  );
	}

	public function index()
	{
		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4) -  1 ) : 0;
		// echo $page; return;
        //pagination parameter
        $pagination['base_url'] = base_url( $this->current_page ) .'/index';
        $pagination['total_records'] = $this->vehicle_model->record_count() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
		//set pagination
		if ($pagination['total_records'] > 0 ) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################3
		$table = $this->services->get_table_config( $this->current_page );
		$table[ "rows" ] = $this->vehicle_model->vehicle_by_store_id( $this->store->id , $pagination['start_record'], $pagination['limit_per_page'] )->result();
		$table[ "image_url" ] = $this->services->get_photo_upload_config( "" )["image_path"];

		$table = $this->load->view('templates/tables/plain_table_image_col', $table, true);
		$this->data[ "contents" ] = $table;
		$add_menu = array(
			"name" => "Tambah Transportasi",
			"modal_id" => "add_gallery_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page."add/"),
			"form_data" => $this->services->get_form_data()["form_data"] ,
			'data' => NULL
		);
		$add_menu= $this->load->view('templates/actions/modal_form_multipart', $add_menu, true ); 

		$this->data[ "header_button" ] = $add_menu;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Transportasi Saya";
		$this->data["header"] = "Transportasi Saya";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render( "templates/contents/plain_content" );
	}

	public function add(  )
	{
		if( !($_POST) ) redirect(site_url(  $this->current_page ));  

		// echo var_dump( $data );return;
		$this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			$data['category_id'] 	= $this->input->post( 'category_id' );
			$data['name'] 			= $this->input->post( 'name' );
			$data['description'] 	= $this->input->post( 'description' );
			$data['capacity'] 		= $this->input->post( 'capacity' );
			$data['police_number'] 	= $this->input->post( 'police_number' );
			$data['unit'] 			= $this->input->post( 'unit' );
			$data['timestamp'] 		= time();
			$data['store_id'] 		= $this->store->id;
			
			// upload photo		
			$this->load->library('upload'); // Load librari upload
			$config = $this->services->get_photo_upload_config( $data['name'] );

			$this->upload->initialize( $config );
			// echo var_dump( $_FILES['images'] ); return;
			// if( $_FILES['image']['name'] != "" )
			if( $this->upload->do_multi_upload( "images" ) )
			{
				$result = array();

				$file_data = $this->upload->get_multi_upload_data();
				if( !empty( $this->upload->display_errors() ) ){
					$this->vehicle_model->set_error( $this->upload->display_errors() );
				}
				foreach( $file_data as $i => $val )
				{
					$result[] = $file_data[$i]["file_name"];
				}
				// return $result;
				$data['images'] 			= implode(";", $result);
			}
			else
			{
				// $data['image'] = "default.png";
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->upload->display_errors() ) );
				redirect( site_url($this->current_page )  );				
			}

			// echo var_dump( $data );return;
			if( $this->vehicle_model->create( $data ) ){
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->vehicle_model->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->vehicle_model->errors() ) );
			}
		}
        else
        {
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->vehicle_model->errors() : $this->session->flashdata('message')));
          if(  validation_errors() || $this->vehicle_model->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
		}
		
		redirect( site_url($this->current_page)  );
	}

	public function edit( $vehicle_id )
	{
		$this->form_validation->set_rules( $this->services->validation_config(  ) );
		if ( $this->form_validation->run() === TRUE )
		{
			$data['category_id'] 	= $this->input->post( 'category_id' );
			$data['name'] 			= $this->input->post( 'name' );
			$data['description'] 	= $this->input->post( 'description' );
			$data['price'] 			= $this->input->post( 'price' );
			$data['unit'] 			= $this->input->post( 'unit' );

			$data_param["id"] = $this->input->post( 'id' );
			// echo var_dump( $data ); return ;
			if( $this->vehicle_model->update( $data, $data_param ) )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->vehicle_model->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->vehicle_model->errors() ) );
			}
			
			redirect( site_url($this->current_page)  );
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->vehicle_model->errors() ? $this->vehicle_model->errors() : $this->session->flashdata('message')));
            if(  !empty( validation_errors() ) || $this->vehicle_model->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );

			$vehicle = $this->vehicle_model->vehicle( $vehicle_id )->row();
			$images = explode(";", $vehicle->images );
			$images_arr = array();
			$image_url = $this->services->get_photo_upload_config( "" )["image_path"];
			foreach( $images as $i => $image ):
				$edit_photo = array(
					"name" => "Ganti Gambar",
					"modal_id" => "edit_photo_".$i,
					"button_color" => "primary",
					"url" => site_url( $this->current_page."upload_photo/"),
					"form_data" => array(
						
						"image" => array(
							'type' => 'file',
							'label' => "Foto",
							'value' => "",	
						),
						"name" => array(
							'type' => 'hidden',
							'label' => "vehicle_id",
							'value' => $vehicle->name,	
						),
						"vehicle_id" => array(
							'type' => 'hidden',
							'label' => "vehicle_id",
							'value' => $vehicle->id,	
						),
						"image_index" => array(
							'type' => 'hidden',
							'label' => "image_index",
							'value' => $i,	
						),
						"old_image" => array(
							'type' => 'hidden',
							'label' => "old_image",
							'value' => $image,	
						),
					'data' => NULL
					),
				);
		
				$edit_photo_html = $this->load->view('templates/actions/modal_form_multipart', $edit_photo, true ); 
				$images_arr[]= (object) array(
					"image_url" 		=> $image_url.$image,
					"edit_photo_html" 	=> $edit_photo_html,
				);
			endforeach;

			$form_data = $this->services->get_form_data( $vehicle_id );
			unset( $form_data["form_data"]["images[]"] );
			
			$form_data = $this->load->view('templates/form/plain_form', $form_data , TRUE ) ;

			$this->data[ "user" ] =  $this->ion_auth->user()->row();
			$this->data[ "images_arr" ] =  $images_arr;
            $this->data[ "contents" ] =  $form_data;
			
			
			$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Edit Produk Saya ";
			$this->data["header"] = "Edit Produk Saya";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
            $this->render( "user/vehicle/content_form" );            
		}
	}

	public function delete(  ) {

		if( !($_POST) ) redirect( site_url($this->current_page) );

		$data_param['id'] 	= $this->input->post('id');

		$vehicle = $this->vehicle_model->vehicle( $data_param['id'] )->row();
		if( $vehicle == NULL ) redirect( site_url($this->current_page) );
		
		if( $this->vehicle_model->delete( $data_param ) ){
			$image_url = $this->services->get_photo_upload_config( "" )["upload_path"];
			$images = explode(";", $vehicle->images );
			foreach( $images as $i => $image ):
				echo $image_url.$image ." ";
				if( !@unlink( $image_url.$image ) ){};
			endforeach;
		  	$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->vehicle_model->messages() ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->vehicle_model->errors() ) );
		}
		redirect( site_url($this->current_page)  );
	}
}
