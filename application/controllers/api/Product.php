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
class Product extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model( array( 
			'category_model',
			'product_model' ,
			'store_model' ,
		));
    }

    public function products_get()
    {
        $category_id    = $this->get('category_id', 0);
        $category_id = ( $category_id != NULL ) ? $category_id : NULL ;

        $products = $this->product_model->products( 0 , NULL, $category_id )->result();
        $this->set_response( $products , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    public function user_products_get(  )
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
        $products = $this->product_model->product_by_store_id( $store->id )->result();
        $this->set_response( $products , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    
    public function product_get(  )
    {
        $product_id    = $this->get('product_id', 0);
        if ($product_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }

        $product = $this->product_model->product( $product_id )->row();
        $data["hit"] = $product->hit +=1;

        $data_param["id"] = $product->id;
        $this->product_model->update( $data, $data_param );
        $this->set_response( $product , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    
}
