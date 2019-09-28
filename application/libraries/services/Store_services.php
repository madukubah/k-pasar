<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Store_services
{
  // user var
	protected $id;
	protected $name;
	protected $start_date;
	protected $description;
	protected $address;

  
  function __construct()
  {
      $this->id		      ='';
      $this->name		='';
      $this->start_date	="";
      $this->description	="";
      $this->address	="";
  }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  public function get_photo_upload_config( $name )
  {
    $filename = "STORE_".$name."_".time();
    $upload_path = 'uploads/store/';

    $config['upload_path'] = './'.$upload_path;
    $config['allowed_types'] = "gif|jpg|png|jpeg";
    $config['overwrite']="true";
    $config['max_size']="2048";
    $config['file_name'] = ''.$filename;

    return $config;
  }

  public function get_table_config( $_page, $start_number = 1 )
  {
    $table["header"] = array(
			'username' => 'username',
			'group_name' => 'Group',
			'user_fullname' => 'Nama Lengkap',
			'phone' => 'No Telepon',
			'email' => 'Email',
		  );
		  $table["number"] = $start_number ;
		  $table[ "action" ] = array(
			array(
			  "name" => "Detail",
			  "type" => "link",
			  "url" => site_url($_page."detail/"),
			  "button_color" => "primary",
			  "param" => "id",
			),
			array(
			  "name" => "Edit",
			  "type" => "link",
			  "url" => site_url($_page."edit/"),
			  "button_color" => "primary",
			  "param" => "id",
			),
			array(
			  "name" => 'X',
			  "type" => "modal_delete",
			  "modal_id" => "delete_category_",
			  "url" => site_url( $_page."delete/"),
			  "button_color" => "danger",
			  "param" => "id",
			  "form_data" => array(
				"id" => array(
				  'type' => 'hidden',
				  'label' => "id",
				),
				"group_id" => array(
				  'type' => 'hidden',
				  'label' => "group_id",
				),
			  ),
			  "title" => "User",
			  "data_name" => "user_fullname",
			),
		);
    return $table;
  }
  public function validation_config( ){
    $config = array(
        array(
          'field' => 'name',
          'label' => 'name',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'start_date',
          'label' => 'start_date',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'description',
          'label' => 'description',
          'rules' =>  'trim|required',
		),
		// array(
		// 	'field' => 'user_id',
		// 	'label' => 'User',
		// 	'rules' =>  'trim|required|is_unique[store.user_id]',
		// ),
    );
    
    return $config;
  }

  /**
	 * get_form_data
	 *
	 * @return array
	 * @author madukubah
	 **/
	public function get_form_data( $store_id = NULL )
	{
		$this->load->model(
			array(
				'store_model',
			)
		);
		if( $store_id != NULL )
		{
			$store 				= $this->store_model->store( $store_id )->row();
			$this->id		    = $store->id;
			$this->name			= $store->name;
			$this->start_date	= date( "d-m-Y", $store->start_date ) ;
			$this->address		= $store->address ;
			$this->description	= $store->description ;
		}

		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "ID",
				'value' => $this->form_validation->set_value('id', $this->id),
			),
			"user_id" => array(
				'type' => 'hidden',
				'label' => "ID",
				'value' => $this->ion_auth->get_user_id(  ),
			),
			"name" => array(
			  'type' => 'text',
			  'label' => "Nama ",
			  'value' => $this->form_validation->set_value('name', $this->name),
			),
			"start_date" => array(
				'type' => 'date',
				'label' => "Tanggal Berdiri",
				'value' => $this->form_validation->set_value('start_date', $this->start_date),			  
			),
			"address" => array(
			  'type' => 'text',
			  'label' => "Alamat",
			  'value' => $this->form_validation->set_value('address', $this->address),
			),
			"description" => array(
				'type' => 'textarea',
				'label' => "Keterangan",
				'value' => $this->form_validation->set_value('description', $this->description),
			),
			"image" => array(
				'type' => 'file',
				'label' => "Gambar",
				'value' => $this->form_validation->set_value('description', $this->description),
			),
			
		  );
		return $_data;
	}
}
?>
