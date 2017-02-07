<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Controller {

		function __construct(){
			
		}
		
		protected $segments = array();

		public function segment($y){
			$seg=explode("/", $_SERVER["QUERY_STRING"]);
			for($x=0;$x<count($seg);$x++){
				$this->segments[$x] = $seg[$x];
			}

			if($y>0 && $y<=count($seg)){
				

				if(count($seg)==0){
					$this->segments[0]=$url;
					$this->segments[1]="index";
					
				}

				return $this->segments[$y-1];
			}else{
				throw new Exception("Invalid Segment!");
					
			}
		}

		

		public function view($v,$data=array()){
			extract($data, EXTR_SKIP);
			$view = BASEPATH.'/application/views/'.$v.'.php';
			if(file_exists($view)){
				require $view;
			}else{
				throw new Exception("File ".$v.".php doesn't exist!");
					
			}
		}
		
		

		public function model($m){	
			$model = BASEPATH.'/application/models/'.$m.'.php';
			
			if(file_exists($model)){
				require $model;
				$this->$m = new $m();
			}else{
				throw new Exception("File ".$m.".php doesn't exist!");
					
			}
		}

		public function base_url($url=''){
			return BASE_URL.$url;
		}

		public function truncateString($str, $chars, $to_space, $replacement="...") {
	  		if($chars > strlen($str)) 
	  			return $str;

	  		$str = substr($str, 0, $chars);

	  		$space_pos = strrpos($str, " ");
	  		
	  		if($to_space && $space_pos >= 0) {
	  			$str = substr($str, 0, strrpos($str, " "));
	  		}

	  		return($str . $replacement);
  		}	

  		public function AJAXRequest(){
  			if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
  				return false;
			}
			else{
				return true;
  			}
		}

  		
	}

	function redirect($link="") {
  		header('Location:'.BASE_URL.$link);    
  	}
?>