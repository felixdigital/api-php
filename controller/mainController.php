<?php
	
	require_once("baseController.php");

	class mainController extends baseController
		{
			
			public function __construct()
				{
					 parent::__construct ();
				}

			public function indexAction() 
				{	
					$method = $_SERVER['REQUEST_METHOD'];
					
					if($method=='GET')
						{	
							header("Content-type: application/json; charset=utf-8");
							http_response_code(200);
							$response = [
								"status"=>"success",
								"message"=>"api blog"
							];
							echo json_encode($response); 
						} 
					
					if($method == 'POST') 
						{
							
						require_once('./core/Database.php');
						require_once('./model/ArticlesModel.php');

						$db = new Database();
						$article = new ArticlesModel( $db->getconnection() );

						$json = file_get_contents("php://input");
					  $reg = json_decode($json, true);
						
						$article->setTitle($reg['title']);
						$article->setContent($reg['content']);
						$article->setExtract($reg['extract']);
						$article->setAuthorName($reg['author_name']);
						$article->setAuthorEmail($reg['author_email']);

						$sql =  "INSERT INTO articles ";
						$sql = $sql . "(id,title,content,extract,author_name,author_email,created_at) values ";
						$sql = $sql . "(null,?,?,?,?,?,CURRENT_TIMESTAMP)";

						$params = [
							"title" => $article->getTitle(),
							"content" => $article->getContent(),
							"extract" => $article->getExtract(),
							"author_name" => $article->getAuthorName(),
							"author_email" => $article->getAuthorEmail(),
						];
						
						$result = $article->queryAction($sql,"sssss",$params);

						if(gettype($result)=='array')
							{
								header("Content-type: application/json; charset=utf-8");
								$response = [ 
									"status"=>"error",
									"message"=>$result[0] 
								];
								http_response_code(500);
								echo json_encode($response);
								exit();
						 }

						 $response = [ 
							 "status"=>"success",
							 "afected rows"=> $result 
						 ];

						 header("Content-type: application/json; charset=utf-8");
						 http_response_code(201); 
						 echo json_encode($response); 
	
					}	
				}
				
			public function articlesAction()
				{	
					$method = $_SERVER['REQUEST_METHOD'];
					
					if($method=='GET')
						{
							require_once('./core/Database.php');
							$db = new Database();
							$result = $db->getAll("select * from vw_articles");
							
							if( gettype($result)=='integer')
									{
										header("Content-type: application/json; charset=utf-8");
										$response = [ 
											"status"=>"error",
											"message"=>$result 
										];
										http_response_code(500);
										echo json_encode($response);
										exit();
							   }
							
							$array = [];
							$reg = [
								"id"=>null,
								"title"=>null,
								"content"=>null,
								"author_name"=>null,
								"author_email"=>null,
								"comments"=>null
							];
							
							foreach ($result as $key=>$value) 
							 	{
									$reg['id'] = $value['id'];
									$reg['title'] = $value['title'];
									$reg['content'] = $value['content'];
							  	$reg['author_name'] = $value['author_name'];
									$reg['author_email']= $value['author_email'];
									$reg['comments'] = $value['comments'];

									if($reg['comments']!=null)
											{
												$reg['comments'] = explode(",",$value['comments']);
											}

                  $array[]=$reg;
							} 
							
							header("Content-type: application/json; charset=utf-8");
							http_response_code(200);
				    	echo json_encode($array);

						}
				}

			public function articlesByIdAction()
				{	
					$method = $_SERVER['REQUEST_METHOD'];
					if($method=='GET')
						{
							require_once('./core/Database.php');
							$id = $_GET['id'];
							$db = new Database();
							$sql = " select * from vw_articles where id=? ";
							$result = $db->getAll($sql,"i",["id"=>$id]);

							if( gettype($result)=='integer')
									{
										header("Content-type: application/json; charset=utf-8");
										$response = [ 
											"status"=>"error",
											"message"=>$result 
										];
										http_response_code(500);
										echo json_encode($response);
										exit();
								 	}

							$array = [];
							$reg = [
									"id"=>null,
									"title"=>null,
									"content"=>null,
									"author_name"=>null,
									"author_email"=>null,
									"comments"=>null
								 ];
									 	
							$reg['id'] = $result[0]['id'];
							$reg['title'] = $result[0]['title'];
							$reg['content'] = $result[0]['content'];
							$reg['author_name'] = $result[0]['author_name'];
							$reg['author_email']= $result[0]['author_email'];
							$reg['comments'] = $result[0]['comments'];
		 
							if($reg['comments']!=null)
									{
										$reg['comments'] = explode(",",$result[0]['comments']);
									}
		 
							$array[]=$reg;
									
							header("Content-type: application/json; charset=utf-8");
							http_response_code(200);
							echo json_encode($array);
							
						}

					if($method == 'PUT')
						{
							require_once('./core/Database.php');
							require_once('./model/ArticlesModel.php');
							
							$json = file_get_contents("php://input");
							$reg = json_decode($json, true);
						
							$db = new Database();
							$article = new ArticlesModel( $db->getconnection() );

							$id = $_GET['id'];
								
							$article->setTitle($reg['title']);
							$article->setContent($reg['content']);
							$article->setExtract($reg['extract']);
							$article->setAuthorName($reg['author_name']);
							$article->setAuthorEmail($reg['author_email']);

							$sql =  "update articles set ";
		          $sql =  $sql . "title=?,content=?,extract=?,author_name=?,author_email=? ";
							$sql =  $sql . "where id=?";
							
							$params = [
								"title" => $article->getTitle(),
								"content" => $article->getContent(),
								"extract" => $article->getExtract(),
								"author_name" => $article->getAuthorName(),
								"author_email" => $article->getAuthorEmail(),
								"id" =>$id
							];

							$result = $article->queryAction($sql,"sssssi",$params);

							if(gettype($result)=='array')
								{
									header("Content-type: application/json; charset=utf-8");
									$response = [ 
										"status"=>"error",
										"message"=>$result[0] 
									];
									http_response_code(500);
									echo json_encode($response);
									exit();
								 }
								 
							$response = [ 
									"status"=>"success",
									"afected rows"=> $result 
								];
	 
								header("Content-type: application/json; charset=utf-8");
								http_response_code(200); 
								echo json_encode($response); 

						}
					
					if($method == 'DELETE' )
						{
							require_once('./core/Database.php');
							require_once('./model/ArticlesModel.php');
							
							$db = new Database();
							$article = new ArticlesModel( $db->getconnection() );
							$id = $_GET['id'];
							
							$sql = "delete from articles where id=?";
							
							$result = $article->queryAction($sql,"i",["id"=>$id]);

							if(gettype($result)=='array')
								{
									header("Content-type: application/json; charset=utf-8");
									$response = [ 
										"status"=>"error",
										"message"=>$result[0] 
									];
									http_response_code(500);
									echo json_encode($response);
									exit();
								 }
								 
							$response = [ 
									"status"=>"success",
									"afected rows"=> $result 
								];
	 
							header("Content-type: application/json; charset=utf-8");
							http_response_code(200); 
							echo json_encode($response); 
								 
						}
				}

				public function articlesCommentsAction()
				{	
					$method = $_SERVER['REQUEST_METHOD'];
					
					if($method=='GET')
						{
						
							require_once('./core/Database.php');
							$id = $_GET['id'];
							$db = new Database();
							$sql = " select * from comments where article=?";
							$result = $db->getAll($sql,"i",["id"=>$id]);
						
							if( gettype($result)=='integer')
								{
									header("Content-type: application/json; charset=utf-8");
									$response = [ 
											"status"=>"error",
											"message"=>$result 
									];
									http_response_code(500);
									echo json_encode($response);
									exit();
								}

							header("Content-type: application/json; charset=utf-8");
							http_response_code(200);
							echo json_encode($result);
							}

					if($method=='POST')
						{
							require_once('./core/Database.php');
							require_once('./model/CommentsModel.php');

							$db = new Database();
							$comment = new CommentsModel( $db->getconnection() );
							$json = file_get_contents("php://input");
							
							$reg = json_decode($json, true);
							$id = $_GET['id'];

							$comment->setContent($reg['content']);
							$comment->setAuthorName($reg['author_name']);
							$comment->setAuthorEmail($reg['author_email']);
							$comment->setArticle($id);
	
							$sql =  "INSERT INTO comments ";
							$sql = $sql . "(id,content,author_name,author_email,created_at,article) values ";
							$sql = $sql . "(null,?,?,?,CURRENT_TIMESTAMP,?)";
							
							$params = [
								"content" => $comment->getContent(),
								"author_name" => $comment->getAuthorName(),
								"author_email" => $comment->getAuthorEmail(),
								"article" => $comment->getArticle(),
								
							];
							
							$result = $comment->queryAction($sql,"sssi",$params);

							if(gettype($result)=='array')
								{
									header("Content-type: application/json; charset=utf-8");
									$response = [ 
										"status"=>"error",
										"message"=>$result[0] 
									];
									http_response_code(500);
									echo json_encode($response);
									exit();
						 		}

						 $response = [ 
							 "status"=>"success",
							 "afected rows"=> $result 
						 ];

						 header("Content-type: application/json; charset=utf-8");
						 http_response_code(201); 
						 echo json_encode($response); 
						
						}
			
				}
				
				public function articlesCommentsByIdAction()
					{	
						$method = $_SERVER['REQUEST_METHOD'];
						
						if($method=='PUT')
						{
							require_once('./core/Database.php');
							require_once('./model/CommentsModel.php');
							
							$json = file_get_contents("php://input");
							$reg = json_decode($json, true);
						
							$db = new Database();
							$comment = new CommentsModel( $db->getconnection() );

							$idArticle = $_GET['idArticle'];
							$idComment = $_GET['idComment'];

							$comment->setContent($reg['content']);
							$comment->setAuthorName($reg['author_name']);
							$comment->setAuthorEmail($reg['author_email']);

							$sql =  "update comments set ";
		          $sql =  $sql . "content=?,author_name=?,author_email=? ";
							$sql =  $sql . "where id=? and article=?";

							$params = [
								"content" => $comment->getContent(),
							  "author_name" => $comment->getAuthorName(),
								"author_email" => $comment->getAuthorEmail(),
								"id" =>$idComment,
								"article" =>$idArticle
							];

							$result = $comment->queryAction($sql,"sssii",$params);

							if(gettype($result)=='array')
								{
									header("Content-type: application/json; charset=utf-8");
									$response = [ 
										"status"=>"error",
										"message"=>$result[0] 
									];
									http_response_code(500);
									echo json_encode($response);
									exit();
								 }

							 $response = [ 
									"status"=>"success",
									"afected rows"=> $result 
								];
	 
								header("Content-type: application/json; charset=utf-8");
								http_response_code(200); 
								echo json_encode($response); 

						}

						if($method=='DELETE')
						{
							require_once('./core/Database.php');
							require_once('./model/CommentsModel.php');
							
							$db = new Database();
							$comment = new CommentsModel( $db->getconnection() );

							$idArticle = $_GET['idArticle'];
							$idComment = $_GET['idComment'];

							$sql = "delete from comments where id=?";
							
							$result = $comment->queryAction($sql,"i",["id"=>$idComment]);

							$response = [ 
								"status"=>"success",
								"afected rows"=> $result 
							];
 
							header("Content-type: application/json; charset=utf-8");
							http_response_code(200); 
							echo json_encode($response); 

							
						}
						
					}

		
	}
