<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mobile_services
{


  function __construct(){

  }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  
  public function get_table_config( $_page, $start_number = 1 )
  {
      $status_select = array(
        0 => "maintenance",
        1 => "stable",
        2 => "message",
      );
      $table["header"] = array(
        'status' => 'Status',
        'message' => 'Pesan',
      );
      $table["number"] = $start_number;
      $table[ "action" ] = array(
              array(
                "name" => 'Edit',
                "type" => "modal_form",
                "modal_id" => "edit_",
                "url" => site_url( $_page."edit/"),
                "button_color" => "primary",
                "param" => "id",
                "form_data" => array(
                    "id" => array(
                        'type' => 'hidden',
                        'label' => "id",
                    ),
                    "status" => array(
                        'type' => 'select',
                        'label' => "Status",
                        "options" => $status_select
                    ),
                    "message" => array(
                        'type' => 'textarea',
                        'label' => "Pesan",
                    ),
                ),
                "title" => "Group",
                "data_name" => "name",
              ),
    );
    return $table;
  }
  public function validation_config( ){
    $config = array(
        array(
          'field' => 'status',
          'label' => 'status',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'message',
          'label' => 'message',
          'rules' =>  'trim|required',
        ),
    );
    
    return $config;
  }
}
?>
