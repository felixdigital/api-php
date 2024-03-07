<?php

class Database
	{	
		private $host = "localhost"; 			
		private $user = "root";
		private $pass = "1234";
		private $database = "blog";
		
		private $connection;

	  public function __construct()
			{
				$this->connection = new mysqli( $this->host,$this->user,$this->pass );
			
				if( $this->connection->connect_errno )
					{
						echo $this->connection->connect_errno." ";
						echo $this->connection->connect_error." "; 
						exit();
					}  
	
			  $this->connection->select_db($this->database) or die("No se encuentra la BBDD");
				$this->connection->set_charset("utf8");
								
			}

			public function getAll( $sql,$type="",$value=[] )
				{
					
					$stmt = $this->connection->prepare($sql);
					if( $stmt==false ) return  $this->connection->errno;  
					if( !empty($value) )
						{
							$keys = array_keys($value);
							if(count($keys)==1) $stmt->bind_param($type,$value[$keys[0]]);
							if(count($keys)==2) $stmt->bind_param($type,$value[$keys[0]],$value[$keys[1]]);	
							if(count($keys)==3) $stmt->bind_param($type,$value[$keys[0]],$value[$keys[1]],$value[$keys[2]]);	
							if(count($keys)==4) $stmt->bind_param($type,$value[$keys[0]],$value[$keys[1]],$value[$keys[2]],$value[$keys[3]]);
							if(count($keys)==5) $stmt->bind_param($type,$value[$keys[0]],$value[$keys[1]],$value[$keys[2]],$value[$keys[3]],$value[$keys[4]]);
							if(count($keys)==6) $stmt->bind_param($type,$value[$keys[0]],$value[$keys[1]],$value[$keys[2]],$value[$keys[3]],$value[$keys[4]],$value[$keys[5]]);	
						}
						
					$stmt->execute();
					$resulset = $stmt->get_result();
					$stmt->close();
					$array = [];
					while ( $row = $resulset->fetch_assoc() )
						{
							$array[] = $row;
						} 
					return $array;		
				}
 
		public function getconnection(){ return $this->connection; }

}