<?php
    
    require_once('routes.php');

    if ( isset( $_GET['url'] ) )
        { 
            $url = "/". $_GET['url'];
            $array = $route->getRoute($url);
            $controller = $array['controller'];
            $method = $array['method'];
    
            if ( $controller != "")
                {
                    require_once("controller/".$controller.".php");
                    $object = new $controller();
                    $object->$method();
                }  
        }
        
         
?>
