<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();


function set_sessiondata($data=array())
{
	foreach ($data as $session=>$value) {
		$_SESSION[$session]=$value;
	}
}

function sessiondata($data)
{
	if(isset($_SESSION[$data])){
		return $_SESSION[$data];
	}
	else{
		// throw new Exception("Undefined \$_SESSION: $data");
		return false;
	}
}

function sess_destroy()
{
	session_destroy(); 
}




