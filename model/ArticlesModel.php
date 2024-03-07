<?php

class ArticlesModel
	{

		private $table = "articles";
		private $connection = null;

		private $id;
		private $title; 
		private $content; 
		private $extract;
		private $author_name; 
		private $author_email; 
		private $created_at;

		public function __construct($connection)
			{
				$this->connection = $connection;
			}

		public function queryAction( $sql,$type="",$value=[] )
				{
					$stmt = $this->connection->prepare($sql);
					if( $stmt==false ) return  [$this->connection->errno]; 
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
					$affectedRows = $stmt->affected_rows;
					$stmt->close();
					
					return $affectedRows;
				}

		
		public function setTitle($title){ $this->title = $title; }
		public function setContent($content){ $this->content = $content; }
		public function setExtract($extract){ $this->extract = $extract; }
		public function setAuthorName($author_name){ $this->author_name = $author_name; }
		public function setAuthorEmail($author_email){ $this->author_email = $author_email; }

		public function getId(){ return $this->id; }
		public function getTitle(){ return $this->title; }
		public function getContent(){ return $this->content; }
		public function getExtract(){ return $this->extract; }
		public function getAuthorName(){ return $this->author_name; }
		public function getAuthorEmail(){ return $this->author_email; }
		public function getCreatedAt(){ return $this->created_at; }


}