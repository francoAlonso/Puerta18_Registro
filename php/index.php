<?php

require 'Slim-2.6.2/Slim/Slim.php';
require 'DatabaseConfig.php';
require 'Database.php';
require 'Usuario.php';

// Permite el acceso desde otros dominios (CORS) - INICIO
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}
// Permite el acceso desde otros dominios (CORS) - FIN

date_default_timezone_set('America/Argentina/Buenos_Aires');

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$DataBaseConfig = new DatabaseConfig();
$pdo = new Database("mysql:host=" . $DataBaseConfig->host . ";dbname=" . $DataBaseConfig->dbname, $DataBaseConfig->username, $DataBaseConfig->password);

$app->get('/usuario', function () use ($app, $pdo){
	try{
		$listaUsuario = Usuario::ObtenerTodos($pdo);
		echo json_encode($listaUsuario);
	}
	catch (Exception $ex)
	{
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});//usuario

$app->get('/buscarUsuarios', function() use ($app, $pdo){
	try{
		$str = $app->request()->get('term');
		$usuariosEncontrados = Usuario::BuscarUsuarios($str, $pdo);
		echo json_encode($usuariosEncontrados);
	}	
	catch (Exception $ex)
	{
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});//mostrarUsuarios

$app->get('/login/:nombre', function($nombre) use($app, $pdo){
	try{
		$nuevoEstado = 0;
		$estadoActual = Usuario::ObtenerConectadoEstado($nombre, $pdo);
		if($estadoActual->Conectado == 0){
			$nuevoEstado = 1;
		}
		Usuario::UpdateConectado($nombre, $nuevoEstado, $pdo);
	}
	catch(Exception $ex){
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});//login

$app->get('/random', function() use($app, $pdo){
	try{
		$usuario = Usuario::Random($pdo);
		echo json_encode($usuario);
	}
	catch(Exception $ex){
		$app->response->setStatus(500);
		echo $ex->getMessage();
	}
});

$app->run(); //corre los resultados

?>