<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$router = new Router();
require BASEPATH."/".$application_path."/config/routes.php";
class Router {

	protected $routes = array(
							
							//"/^(?P<controller>[a-z]+)\/(?P<action>[a-z]+)$/i"=>array(),
							// "/^(?P<controller>[a-z]+)\/(?P<action>[a-z]+)\/(?P<id>\w+)$/i"=>array(),
						);


	protected $params = array();

	
	public function getParams()
	{
		return $this->params; 
	}
 
	

	public function getRoutes() 
	{
		return $this->routes;
	}

	public function addRoute($route,$params)
	{
		
		$route = preg_replace("/\//", "\\/", $route);

		$route = preg_replace("/\{([a-z]+)\}/", "(?P<\$1>[a-z]+)", $route);

		$route = preg_replace("/\{([a-z]+):([^\}]+)\}/", "(?P<\$1>\$2)", $route);

		$route = "/^".$route."$/i";

		$segments = explode("/", $params);
		$segments = array_filter($segments);
		if(count($segments)==1){
			$segments[]="index";

		}
		$this->routes[$route] = array('controller'=>$segments[0],'action'=>$segments[1]);
	}	


	public function match($url)
	{	
		// $reg_exp = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";
		
		
		foreach ($this->routes as $route => $params) 
		{
			
			if(preg_match($route, $url, $matches))
			{
				
				
				foreach ($matches as $key => $match) {
					if(is_string($key)) {
						$params[$key] = $match; 

					}
					
					
				}

				$this->params = $params;
				return true;
			}
			

		}
		return false;
	}


	public function dispatch($url) 
	{	
		
		if($this->match($url)) {

			$x=1;
			$controllername = $this->params['controller'];
			$controller = $controllername;
			$controller = $this->convertTOStudlyCaps($controller);
			$controller = BASEPATH."/application/controllers/$controller.php";
		
			if(file_exists($controller)) {
				require  $controller;
				$controller_object = new $this->params['controller']();
				$action = $this->params['action'];
				$action = $this->convertTOCamelCase($action);
				if(is_callable(array($controller_object,$action))) {
					$controller_object->$action();
				} 
				else{
					throw new Exception("Method $action (in controller $controller) not Found!", 404);
					
				}

			}
			else{
				throw new Exception("Controller class $controllername not found!", 404);
					
			}
			
		} 
		else {
			$dir = BASEPATH."/application/controllers/";
			if(count(scandir($dir))>0){
				$x=0;
				$segments= array();
				$segments = explode("/", $url);
				$segments = array_filter($segments);
				if(count($segments)==1){
					$segments[]="index";

				}
				$controllername = $segments[0];
				$controller = $controllername;
				$controller = $this->convertTOStudlyCaps($controller);
				$controller = BASEPATH."/application/controllers/$controller.php";
			
				if(file_exists($controller)) {
					require  $controller;
					$controller_object = new $segments[0]();
					$action = $segments[1];
					$action = $this->convertTOCamelCase($action);
					if(is_callable(array($controller_object,$action))) {
						$controller_object->$action();
					} 
					else{
						throw new Exception("Method $action (in controller $controller) not Found!", 404);
						
					}

				}
				else{
					throw new Exception("Controller class $controllername not found!", 404);
						
				}
			}
			else {
				throw new Exception("No controller found!", 404);
			}
		} 

		// else {
		// 	die("ERROR 404 : Page Not Found!");
		// }


	}

	protected function convertTOStudlyCaps($string)
	{
		return str_replace(' ','',ucwords(str_replace('-',' ',$string))); 
	}

	protected function convertTOCamelCase($string)
	{
		return lcfirst($this->convertTOStudlyCaps($string));
	}




}


$router->dispatch($_SERVER['QUERY_STRING']);



