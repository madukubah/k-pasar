<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_services
{
  // user var
	protected $id;
	protected $name;
	protected $description;
	protected $price;
	protected $unit;
	protected $category_id;

	
  protected $group_id;
  
  function __construct()
  {
      $this->id		      ='';
      $this->name		='';
      $this->start_date	="";
      $this->description	="";
      $this->price	= 0 ;
	  $this->unit	="";
	  $this->category_id		= "";
	  
  }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  public function get_photo_upload_config( $name = "_" )
  {
    $filename = "PRODUCT_".$name."_".time();
    $upload_path = 'uploads/product/';

    $config['upload_path'] = './'.$upload_path;
    $config['image_path'] = base_url().$upload_path;
    $config['allowed_types'] = "gif|jpg|png|jpeg";
    $config['overwrite']="true";
    $config['max_size']="2048";
    $config['file_name'] = ''.$filename;

    return $config;
  }

  public function get_table_config( $_page, $start_number = 1 )
  {
		$table["header"] = array(
			'name' => 'Nama',
			'description' => 'Keterangan',
			'price' => 'Harga',
			'unit' => 'Satuan',
			'category_name' => 'Kategori',
			'images' => 'Gambar',
		);
		$table["number"] = $start_number ;
		$table[ "action" ] = array(
			// array(
			//   "name" => "Detail",
			//   "type" => "link",
			//   "url" => site_url($_page."detail/"),
			//   "button_color" => "primary",
			//   "param" => "id",
			// ),
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
				"images" => array(
				  'type' => 'hidden',
				  'label' => "images",
				),
			  ),
			  "title" => "Produk",
			  "data_name" => "name",
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
          'field' => 'category_id',
          'label' => 'link',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'price',
          'label' => 'icon',
          'rules' =>  'trim|required',
        ),
    );
    
    return $config;
  }

  /**
	 * get_form_data
	 *
	 * @return array
	 * @author madukubah
	 **/
	public function get_form_data( $product_id = NULL )
	{
		$this->load->model(array(
			'store_model',
			'group_category_model',
			'category_model',
		));
		if( $product_id != NULL )
		{
			$product = $this->product_model->product( $product_id )->row();
			
			$this->id		    	= $product->id;
			$this->category_id		= $product->category_id;
			$this->name				= $product->name ;
			$this->description		= $product->description ;
			$this->price			= $product->price ;
			$this->unit				= $product->unit ;
		}
		$user_id = $this->ion_auth->get_user_id();
		$category = $this->group_category_model->get_by_group_id( $this->ion_auth->user( $user_id )->row()->group_id )->row();
		
		$categories = $this->category_model->categories( $category->category_id )->result();
		$category_select = [];
		foreach( $categories as $item )
		{
			$category_select[ $item->id ] =  $item->name ;
		}

		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "ID",
				'value' => $this->form_validation->set_value('id', $this->id),
			),
			"category_id" => array(
				'type' => 'select',
				'label' => "Kategori ",
				'options' => $category_select,
				"selected" => $this->category_id
			),
			"name" => array(
			  'type' => 'text',
			  'label' => "Nama ",
			  'value' => $this->form_validation->set_value('name', $this->name),
			),
			"description" => array(
			  'type' => 'textarea',
			  'label' => "Keterangan",
			  'value' => $this->form_validation->set_value('description', $this->description),
			),
			"price" => array(
				'type' => 'number',
				'label' => "Harga",
				'value' => $this->form_validation->set_value('price', $this->price),
			),
			"unit" => array(
				'type' => 'text',
				'label' => "Satuan",
				'value' => $this->form_validation->set_value('unit', $this->unit),
			),
			"images[]" => array(
				'type' => 'multiple_file',
				'label' => "Gambar",
			),
			
		);
		return $_data;
	}
}
?>
