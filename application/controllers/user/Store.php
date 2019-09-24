<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends User_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'user';
	private $current_page = 'user/store/';

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('url', 'language'));
		$this->lang->load('auth');

		$this->load->library('services/Store_services');
		$this->services = new Store_services;

		$this->load->model(array(
			'store_model',
		));
		
	} 
	public function index()
	{
		$user_id = $this->ion_auth->get_user_id();
		$store = $this->store_model->store_by_user_id(  $user_id )->row();
		// echo var_dump( $store );return;
		if( $store == NULL )
		{
			$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Usaha Saya ";
			$this->data["header"] = "Usaha Saya";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
			$this->render( "user/store/content_blank" );
			return;
			// echo var_dump( $store == NULL );return;
		}
		$form_data = $this->services->get_form_data(  $store->id );
		unset( $form_data["form_data"]["image"] );
		$form_data = $this->load->view('templates/form/plain_form_readonly', $form_data , TRUE ) ;

		$this->data[ "user" ] 		=  $this->ion_auth->user()->row();
		$this->data[ "store" ] 		=  $store;
		$this->data[ "contents" ] 	=  $form_data;
		
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Usaha Saya ";
		$this->data["header"] = "Usaha Saya";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render( "user/store/content" );
	}

	public function upload_photo()
	{
		$data['name'] = $this->input->post( 'name' );
		// $data['image'] = $this->input->post( 'old_image' );

		$this->load->library('upload'); // Load librari upload
		$config = $this->services->get_photo_upload_config( $data['name'] );

		$this->upload->initialize( $config );
		// echo var_dump( $data ); return;
		// if( $_FILES['image']['name'] != "" )
		if( $this->upload->do_upload("image") )
		{
			$data['image'] = $this->upload->data()["file_name"];
			if( $this->input->post( 'old_image' ) != "default.png" )
				if( !@unlink( $config['upload_path'].$this->input->post( 'old_image' ) ) );
		}
		else
		{
			// $data['image'] = "default.png";
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->upload->display_errors() ) );
			redirect( site_url($this->current_page."edit/")  );				
		}
		
		$data_param["id"] = $this->input->post( 'id' );
		// echo var_dump( $data ); return ;
		if( $this->store_model->update( $data, $data_param ) )
		{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->store_model->messages() ) );
		}else{
			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->store_model->errors() ) );
		}
		redirect( site_url($this->current_page )  );	
	}

	public function create() //edut curr profile
	{
		$user_id = $this->ion_auth->get_user_id();

		$this->form_validation->set_rules( $this->services->validation_config(  ) );
		if ( $this->form_validation->run() === TRUE )
		{
			$data['name'] = $this->input->post( 'name' );
			$data['start_date'] = strtotime( $this->input->post( 'start_date' ) );
			$data['description'] = $this->input->post( 'description' );
			$data['address'] = $this->input->post( 'address' );

			$data['user_id'] = $this->ion_auth->get_user_id(  );

			$data['timestamp'] = time();	
			// upload photo		
			$this->load->library('upload'); // Load librari upload
			$config = $this->services->get_photo_upload_config( $data['name'] );

			$this->upload->initialize( $config );
			// echo var_dump( $_FILES ); return;
			// if( $_FILES['image']['name'] != "" )
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
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->store_model->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->store_model->errors() ) );
			}
			
			redirect( site_url($this->current_page)  );
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->store_model->errors() ? $this->store_model->errors() : $this->session->flashdata('message')));
            if(  !empty( validation_errors() ) || $this->store_model->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );


            $form_data = $this->services->get_form_data(  );
			
			$form_data = $this->load->view('templates/form/plain_form', $form_data , TRUE ) ;

			$this->data[ "user" ] =  $this->ion_auth->user()->row();
            $this->data[ "contents" ] =  $form_data;
			
			$edit_photo = array(
				"name" => "Ganti Foto",
				"modal_id" => "edit_photo_",
				"button_color" => "primary",
				"url" => site_url( $this->current_page."upload_photo/"),
				"form_data" => array(
					"image" => array(
						'type' => 'file',
						'label' => "Foto",
						'value' => "",	
					),
				'data' => NULL
				),
			);
	
			$edit_photo= $this->load->view('templates/actions/modal_form_multipart', $edit_photo, true ); 
	
			$this->data[ "edit_photo" ] =  $edit_photo ;

			$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Buat Usaha Saya ";
			$this->data["header"] = "Buat Usaha Saya";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
            $this->render( "user/store/content_form_create" );
		}
	}

	public function edit() //edut curr profile
	{
		$user_id = $this->ion_auth->get_user_id();
		$store = $this->store_model->store_by_user_id(  $user_id )->row();

		$this->form_validation->set_rules( $this->services->validation_config(  ) );
		if ( $this->form_validation->run() === TRUE )
		{
			$data['name'] = $this->input->post( 'name' );
			$data['start_date'] = strtotime( $this->input->post( 'start_date' ) );
			$data['description'] = $this->input->post( 'description' );
			$data['address'] = $this->input->post( 'address' );

			$data_param["id"] = $this->input->post( 'id' );
			// echo var_dump( $data ); return ;
			if( $this->store_model->update( $data, $data_param ) )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->store_model->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->store_model->errors() ) );
			}
			
			redirect( site_url($this->current_page)  );
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->store_model->errors() ? $this->store_model->errors() : $this->session->flashdata('message')));
            if(  !empty( validation_errors() ) || $this->store_model->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );


			$form_data = $this->services->get_form_data( $store->id );
			unset( $form_data["form_data"]["image"] );
			
			$form_data = $this->load->view('templates/form/plain_form', $form_data , TRUE ) ;

			$this->data[ "user" ] =  $this->ion_auth->user()->row();
			$this->data[ "store" ] =  $store;
            $this->data[ "contents" ] =  $form_data;
			
			$edit_photo = array(
				"name" => "Ganti Foto",
				"modal_id" => "edit_photo_",
				"button_color" => "primary",
				"url" => site_url( $this->current_page."upload_photo/"),
				"form_data" => array(
					"image" => array(
						'type' => 'file',
						'label' => "Foto",
						'value' => "",	
					),
					"id" => array(
						'type' => 'hidden',
						'label' => "Foto",
						'value' => $store->id,	
					),
					"name" => array(
						'type' => 'hidden',
						'label' => "Foto",
						'value' => $store->name,	
					),
					"old_image" => array(
						'type' => 'hidden',
						'label' => "Foto",
						'value' => $store->image,	
					),
				'data' => NULL
				),
			);
	
			$edit_photo= $this->load->view('templates/actions/modal_form_multipart', $edit_photo, true ); 
	
			$this->data[ "edit_photo" ] =  $edit_photo ;

			$alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Edit Usaha Saya ";
			$this->data["header"] = "Edit Usaha Saya";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
            $this->render( "user/store/content_form" );            
		}
	}

}
