<?php 
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 05 Oct, 2022 06:38AM
// +------------------------------------------------------------------------+


require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/backend/inc/class-autoload.inc.php';

use LogadApp\Router\Router;
use LogadApp\Router\Request;
use LogadApp\Router\Response;

$router = new Router();

// Base Path
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($scriptDir == "/") $scriptDir = "";
$router->setBasePath($scriptDir);

$router->addNotFoundHandler(function($path) {
	$message = "Route '$path' not found";
	require_once __DIR__ . '/views/error-message.php';
});

$router->get('/', function(Request $request, Response $response) {
	$response->write(json_encode([
		'path' => $request->getPath(),
		'message' => 'Hello Mike'
	]))->asJson()
	->withStatus(201);
});

## /users/mike
$router->get('/users/{username}', function(Request $request, Response $response) {
	$args = $request->getArguments();
	$html = '<h1>User page</h1>';
	$html .= '<h2>Username: '. $args['username'] .'</h2>';
	$response->write($html);
});

## /contact?name=Michael
$router->get('/contact', function(Request $request, Response $response) {
	$requestBody = $request->getParameters();
	$name = $requestBody['name'] ?? null;
	echo '<h1>'.$name.'</h1>';
	include __DIR__.'/views/contact.html';
});

// Method 1 //
/* $router->post('/contact', function (Request $request, Response $response) {
	var_dump($request->getParsedBody());
	// (new Forms)->contactForm($requestBody);
}); */

// Method 2 //
$router->post('/contact', [Forms::class, 'contactForm']);

$router->run();