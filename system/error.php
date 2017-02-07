<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Error
{
	public static function errorHandler($level, $message, $file, $line)
	{
		if(error_reporting() !== 0) {
			throw new ErrorException($message, 0, $level, $file, $line);
		}
	}



	public static function exceptionHandler($exception)
	{
		$code = $exception->getCode();
		if($code!=404) {
			$code = 500;
		}

		if(SHOW_ERRORS){
			echo "<h1>Fatal Error!</h1>";
			echo "<p>Uncaught exception: '".get_class($exception)."'</p>";
			echo "<p>Message: ".$exception->getMessage()."</p>";
			echo "<p>Stack trace:<pre>".$exception->getMessage()."</pre></p>";
			echo "<p>Thrown in '".$exception->getFile()."' on line ".$exception->getLine()."</p>";
		}else{
			$log=BASEPATH.'/logs/'.date('Y-m-d').'.txt';
			ini_set('error_log', $log);
			$message = "Fatal Error!";
			$message.= "Uncaught exception: '".get_class($exception)."'";
			$message.= "Message: ".$exception->getMessage();
			$message.= "\nStack trace:".$exception->getMessage();
			$message.= "\nThrown in '".$exception->getFile()."' on line ".$exception->getLine();
			error_log($message); 
			$controller = new Controller;
			$controller->view('error/'.$code);
			
		}
	}
	
}