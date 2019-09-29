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
class Iklan extends REST_Controller {
	const IMAGE_TYPE = 4;
	
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        $this->load->model(array(
			'gallery_model',
		));
        $this->gallery_model->set_message_delimiters( '', '' );
        $this->gallery_model->set_error_delimiters( '', '' );


    }
    public function index_get()
    {
        $iklan = $this->gallery_model->galleries( Iklan::IMAGE_TYPE )->result();
        $this->set_response( $iklan , REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}