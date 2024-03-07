<?php

	require_once("lib/Router.php");
	$route = new Router();
	

	//routes
	$route->addRoute("/index","mainController","indexAction");
	$route->addRoute("/articles","mainController","articlesAction");
	$route->addRoute("/articles/","mainController","articlesAction");

	if( isset( $_GET['id'] ))
		{
			$id = $_GET['id'];
			$route->addRoute("/articles/$id","mainController","articlesByIdAction");
			$route->addRoute("/articles/$id/","mainController","articlesByIdAction");
			$route->addRoute("/articles/$id/comments","mainController","articlesCommentsAction");
			$route->addRoute("/articles/$id/comments/","mainController","articlesCommentsAction");
		}
	
	if( isset( $_GET['idArticle']) && isset( $_GET['idComment'] ) )
		{
			$idArticle = $_GET['idArticle'];
			$idComment = $_GET['idComment'];
			$route->addRoute("/articles/$idArticle/comments/$idComment","mainController","articlesCommentsByIdAction");
			$route->addRoute("/articles/$idArticle/comments/$idComment/","mainController","articlesCommentsByIdAction");
		}

 
?>