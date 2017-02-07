<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Welcome extends Controller{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->view("welcome_message");
		
	}
}