<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Category extends Uadmin_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'uadmin';
	private $current_page = 'uadmin/category/';
	
	public function __construct(){
		parent::__construct();
		$this->load->library('services/Menu_services');
		$this->services = new Menu_services;
		$this->load->model(array(
			'category_model',
			'Group_model',
		));
		$this->data[ "menu_list_id" ] = "category_index";
	}	
	public function index1()
	{
		// return;
		// echo json_encode( $this->data[ "_menus" ] ) ;return;
		$table = $this->services->groups_table_config( $this->current_page );
		$table[ "rows" ] = $this->Group_model->groups()->result();
		$table = $this->load->view('templates/tables/plain_table', $table, true);
		$this->data[ "contents" ] = $table;
		$this->data[ "header_button" ] = '';
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Kategori";
		$this->data["header"] = "Kategori";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		$this->render( "templates/contents/plain_content" );
	}

	public function index( $group_id = 0 )
	{
		// return;
		// echo var_dump( $this->category_model->tree( $group_id ) ) ;return;
		// $group = $this->Group_model->group( $group_id )->row();
		// if( $group == NULL ) redirect( site_url($this->current_page)  );
		$this->data[ "menus_tree" ] = $this->category_model->tree( $group_id );
		$this->data[ "menu_list" ] = $this->category_model->get_category_list(  );
		// $this->data[ "group" ] = $group;
		$this->data[ "contents" ] = '' ;
		##################################################################################################################################
		$add_menu = array(
			"name" => "Tambah Kategori",
			"modal_id" => "add_menu_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page."add/"),
			"form_data" => array(
			  "name" => array(
				'type' => 'text',
				'label' => "Nama Menu",
			  ),
			  "description" => array(
				'type' => 'textarea',
				'label' => "Deskripsi",
				'value' => "-",				
			  ),
			  "_order" => array(
				'type' => 'number',
				'label' => "Urutan",
				'value' => 1,				
			  ),
			  "category_id" => array(
				'type' => 'hidden',
				'label' => "category_id",
				'value' => $group_id,
			  ),
			//   "group_id" => array(
			// 	'type' => 'hidden',
			// 	'label' => "group_id",
			// 	'value' => $group->id,
			//   ),
			),
			'data' => NULL
		);

		$add_menu= $this->load->view('templates/actions/modal_form', $add_menu, true ); 

		$this->data[ "header_button" ] =  $add_menu ;
		// echo return;
		##################################################################################################################################
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Kategori ";
		$this->data["header"] = "Kategori ";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

		$this->render( "uadmin/category/content_category" );
	}

	public function add(  )
	{
		if( !($_POST) ) redirect(site_url(  $this->current_page ));  

		$group_id = $this->input->post( 'group_id' );
		// echo var_dump( $data );return;
		$this->form_validation->set_rules( "name", "name", "trim|required" );
		$this->form_validation->set_rules( "description", "description", "trim|required" );
		$this->form_validation->set_rules( "_order", "_order", "trim|required" );

        if ($this->form_validation->run() === TRUE )
        {
			$data['name'] = $this->input->post( 'name' );
			$data['description'] = $this->input->post( 'description' );
			$data['_order'] = $this->input->post( '_order' );
			$data['category_id'] = $this->input->post( 'category_id' );
			

			if( $this->category_model->create( $data ) ){
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->category_model->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->category_model->errors() ) );
			}
		}
        else
        {
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->category_model->errors() : $this->session->flashdata('message')));
          if(  validation_errors() || $this->category_model->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
		}
		
		redirect( site_url($this->current_page)  );
	}

	public function edit(  )
	{
		if( !($_POST) ) redirect(site_url(  $this->current_page ));  

		$group_id = $this->input->post( 'group_id' );
		// echo var_dump( $data );return;
		$this->form_validation->set_rules( "name", "name", "trim|required" );
		$this->form_validation->set_rules( "description", "description", "trim|required" );
		$this->form_validation->set_rules( "_order", "_order", "trim|required" );
        if ($this->form_validation->run() === TRUE )
        {
			$data['name'] = $this->input->post( 'name' );
			$data['description'] = $this->input->post( 'description' );
			$data['_order'] = $this->input->post( '_order' );
			
			$data_param['id'] = $this->input->post( 'id' );

			if( $this->category_model->update( $data, $data_param  ) ){
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->category_model->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->category_model->errors() ) );
			}
		}
        else
        {
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->m_account->errors() ? $this->category_model->errors() : $this->session->flashdata('message')));
          if(  validation_errors() || $this->category_model->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
		}
		
		redirect( site_url($this->current_page)  );
	}

	public function delete(  ) {
		if( !($_POST) ) redirect( site_url($this->current_page) );
	  
		$data_param['id'] 	= $this->input->post('id');
		$group_id = $this->input->post( 'group_id' );
		if( $this->category_model->delete( $data_param ) ){
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->category_model->messages() ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->category_model->errors() ) );
		}
		redirect( site_url($this->current_page )  );
	  }
}
