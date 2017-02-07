<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	require $application_path."/config/config.php";
	
	require "error.php";
	require "session.php";
	set_error_handler("Error::errorHandler");
	set_exception_handler("Error::exceptionHandler");
	require "controller.php";
	require "model.php";
	require "router.php";
	