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
class Category extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model( 'category_model');
        $this->load->model( 'group_category_model');
    }

    public function categories_get()
    {
        $category_id    = $this->get('id', 0);
        $category_id = ( $category_id != NULL ) ? $category_id : 0 ;

        $categories = $this->category_model->categories( $category_id )->result();
        $this->set_response( $categories , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function categories_by_group_get()
    {
        $group_id    = $this->get('group_id', 0);
        $group_category = $this->group_category_model->get_by_group_id( $group_id )->row();
        if( $group_category == NULL ) 
        {
            $result = array(
                "message" => "terjadi error",
                "status" => 0,
                "user_data" => array(),
            );
            $this->set_response( $result , REST_Controller::HTTP_BAD_REQUEST); // CREATED (201) being the HTTP response code            
        }
        $categories = $this->category_model->categories( $group_category->category_id )->result();
        $this->set_response( $categories , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
