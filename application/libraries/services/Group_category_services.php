<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Group_category_services
{
  

  function __construct(){

  }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  
  public function get_table_config( $_page, $start_number = 1 )
  {
      $table["header"] = array(
        'group_name' => 'Nama Group',
        'category_name' => 'Nama Kategori',
      );
      $table["number"] = $start_number;
      $table[ "action" ] = array(
              array(
                "name" => 'Edit',
                "type" => "modal_form_multipart",
                "modal_id" => "edit_",
                "url" => site_url( $_page."edit/"),
                "button_color" => "primary",
                "param" => "id",
                "form_data" => $this->get_form_data(  )["form_data"],
                "title" => "Group",
                "data_name" => "name",
              ),
              array(
                "name" => 'X',
                "type" => "modal_delete",
                "modal_id" => "delete_",
                "url" => site_url( $_page."delete/"),
                "button_color" => "danger",
                "param" => "id",
                "form_data" => array(
                  "id" => array(
                    'type' => 'hidden',
                    'label' => "id",
                  ),
                  "file" => array(
                      'type' => 'hidden',
                      'label' => "Nama Group",
                  ),
                ),
                "title" => "Group",
                "data_name" => "name",
              ),
    );
    return $table;
  }
  public function validation_config( )
  {
    $config = array(
        array(
          'field' => 'group_id',
          'label' => 'group_id',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'category_id',
          'label' => 'category_id',
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
	public function get_form_data(  )
	{
		$this->load->model(array(
      'group_model',
			'category_model',
		));
    
    $categories = $this->category_model->categories( 0 )->result();
    $category_select = [];
    foreach( $categories as $item )
    {
      $category_select[ $item->id ] =  $item->name ;
    }

    $groups = $this->group_model->groups(  )->result();
    $group_select = [];
    foreach( $groups as $item )
    {
      $group_select[ $item->id ] =  $item->description ;
    }

		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "ID",
			),
      "group_id" => array(
			  'type' => 'select',
        'label' => "Kategori ",
        'options' => $group_select,
      ),
      "category_id" => array(
			  'type' => 'select',
        'label' => "Kategori ",
        'options' => $category_select,
      ),
    );
		return $_data;
	}
}
?>
