<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Visitor_Controller
{

	public function index()
	{
		$this->data["page_title"] = "Beranda";
		$this->render("visitor/dashboard/content");
	}
}
