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
class Mobile extends REST_Controller {
	
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        $this->load->model(array(
			'mobile_model',
		));
        $this->mobile_model->set_message_delimiters( '', '' );
        $this->mobile_model->set_error_delimiters( '', '' );


    }
    public function mobile_status_get()
    {
        $mobile = $this->mobile_model->mobiles(  )->row() ;
        $this->set_response( $mobile , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}