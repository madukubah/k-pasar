<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Gallery_services
{
  function __construct(){

  }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  public function get_photo_upload_config( $name = "_" )
  {
    $filename = "Gallery_".$name."_".time();
    $upload_path = 'uploads/gallery/';

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
        'description' => 'Deskripsi',
        'images' => 'Gambar',
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
                "form_data" => array(
                    "id" => array(
                        'type' => 'hidden',
                        'label' => "id",
                    ),
                    "name" => array(
                        'type' => 'text',
                        'label' => "Nama Gambar",
                    ),
                    "description" => array(
                        'type' => 'textarea',
                        'label' => "Deskripsi",
                    ),
                    "file" => array(
                        'type' => 'hidden',
                        'label' => "Nama Group",
                    ),
                    "file_image" => array(
                      'type' => 'file',
                      'label' => "Gambar",
                      'value' => "-",
                    ),
                ),
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
          'field' => 'name',
          'label' => 'name',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'description',
          'label' => 'description',
          'rules' =>  'trim|required',
        ),
    );
    
    return $config;
  }
}
?>
