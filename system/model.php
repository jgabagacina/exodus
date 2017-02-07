<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Model {

		private $conn;

		private $order_by = array();

		private $row = 1;

		private $where=array();

		private $whereconcut=array();

		private $set=array();

		private $select = "*";


		function __construct(){
			 $this->conn=new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE, USERNAME, PASSWORD);;
			 $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
		}

		
		private function unsetarray(){
			if(!empty($this->where))
				$this->where=array();

			if(!empty($this->whereconcut))
				$this->whereconcut=array();

			if(!empty($this->set))
				$this->set=array();

			if(!empty($this->order_by))
				$this->order_by=array();
		}

		private function setdata($data=array()){
			$values='';
			$i=0;
			$count=count($data);
			foreach ($data as $key => $value) {
				
				if(++$i === $count) {
				    $values.=$key.'=:'.$key;
				}
				else {
					$values.=$key.'=:'.$key.', ';
				}
			}
			return $values;

		}

		public function row($row){
			$this->row=$row;
		}

		public function set($column,$value){
			$this->set[$column]=$value;
		}

		public function insert($table,$data=array()){
		
			$values=$this->setdata($data);
			$query=$this->conn->prepare("INSERT INTO ".DBPREFIX.$table." SET ".$values);
			$query->execute($data);
			$this->unsetarray();
			return $this->conn->lastInsertId();
		}

		public function select($data){
			$this->select=$data;
		}

		public function get($table) {
 			$order_by = $this->getorderby();
			$where=$this->getwhere();
			$query=$this->conn->prepare("SELECT ".$this->select." FROM ".DBPREFIX.$table." ".$where." ".$order_by);
			$query->execute($this->whereconcut);
			$this->unsetarray();
			if($this->row){
				return $query->fetchAll(PDO::FETCH_OBJ);
			}
			else{
				return $query->fetch(PDO::FETCH_OBJ);
			}
		}



		

		private function getwhere(){	
			$values='';
			$i=0;
			$where='';
			$count=count($this->where);
			foreach ($this->where as $key => $value) {
					
					$key2=preg_replace("/[<>!=]+/",' ',$key);
			
					if(++$i === $count) {
					    $values.=$key.':'.$key2;
					}
					else {
						$values.=$key.':'.$key2.' AND ';
					}
				
			}
			if($count>0){
				$where ="WHERE ".$values;
			}

			return $where;
		}


		private function getorderby(){	
			$values='';
			$i=0;
			$order_by='';
			$count=count($this->order_by);
			foreach ($this->order_by as $key => $value) {				
					
					if(++$i === $count) {
					    $values.=$key.' '.$value;
					}
					else {
						$values.=$key.' '.$value.' ,';
					}
				
			}
			if($count>0){
				$order_by ="ORDER BY ".$values;
			}

			return $order_by;
		}

		public function where($column,$value){

			if(preg_match("/[<>!=]+/",$column)){
				$column1 =preg_replace("/[<>!=]+/",'',$column);
				$this->whereconcut[$column1]=$value;
			}
			else
			if(!preg_match("/[<>!=]+/",$column)){
				$this->whereconcut[$column]=$value;
				$column=$column.'=';
			}
			$this->where[$column]=$value;
			

		}

		public function update($table,$data=array()) {
			$values=$this->setdata($this->set);
			$where=$this->getwhere();
			$query=$this->conn->prepare("UPDATE ".DBPREFIX.$table." SET ".$values." ".$where);
			$array=array_merge($this->whereconcut,$this->set);
			$query->execute($array);
			$this->unsetarray();

		}

		public function delete($table,$data=array()) {
			$values=$this->setdata($data);
			$where=$this->getwhere();
			$query=$this->conn->prepare("DELETE FROM ".DBPREFIX.$table." ".$where);
			$query->execute($this->whereconcut);
			$this->unsetarray();

		}

		public function order_by($column,$value)
		{
			$this->order_by['column'] = $value;
		}


		
	
		
	}
?>