<?php

class Router 
	{
		private $routes = array();

		public  function addRoute( $route,$controller,$method )
			{
				$this->routes[$route] = ["controller"=>$controller,"method"=>$method];
			}

		public  function getRoute( $route )
			{
				if( array_key_exists( $route, $this->routes) )
					{
						return 	$this->routes[$route] ;
					}
				else
					{
						echo "Route not found "."</br>";
						return false;
					}
			}
	}


