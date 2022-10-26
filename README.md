<br />
<div align="center">
  <a href="https://github.com/michael-arawole/synote">
    <!-- <img src="frontend/static/synote.svg" alt="Logo" style="filter: brightness(0) invert(1);" width="40%" height="150"> -->
  </a>

  <h1 align="center">LogadApp\Router</h1>

  <p align="center">
    <a href="https://github.com/michael-arawole/logadapp-php-router/issues">Report Bug</a>
    ·
    <a href="https://github.com/michael-arawole/logadapp-php-router/issues">Request Feature</a>
  </p>
</div>

<!-- ABOUT THE PROJECT -->
## About The Project
Another router class, ikr

Features
* GET Requests
* POST Requests
* Named variables (Ex. users/{username} will allow users/michaelthedev and you can get tht values)
* Custom Request Class
* Custom Response Class
* Routing to class (Ex. users/{username} can route to UserCtrl::actionsHandler())
* Validation support [Rakit\Validation](https://github.com/rakit/validation)

## Usage
```php
<?php

require('vendor/autoload.php');

use LogadApp\Router\Router;
use LogadApp\Router\Request;
use LogadApp\Router\Response;

$router = new Router();

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

// Method 1: Handle post in the same file //
/* $router->post('/contact', function (Request $request, Response $response) {
	var_dump($request->getParsedBody());
}); */

// Method 2: Route to a class method //
$router->post('/contact', [Forms::class, 'contactForm']);

$router->run();
```


If you're using in a sub directory, you'll may encouter a 404 error. Use the setBasePath function after initializing the router class<br>
```php
<?php
$router = new Router();

// Base Path
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($scriptDir == "/") $scriptDir = "";
$router->setBasePath($scriptDir);
```


Setup a 404 handler
```php
$router = new Router();

$router->addNotFoundHandler(function($path) {
	$message = "Route '$path' not found";
  	echo $message;
	// require_once __DIR__ . '/views/error-message.php';
});
```


## Using the `LogadApp\Router\Request` and `LogadApp\Router\Response` classes
Every function the path is being router to will have 2 parameters (`Request` and `Response`)<br>
Example:
```php
function myMethod(Request $request, Response $response)
```


### `LogadApp\Router\Request` class
* `getPOSTBody()` returns the POST body
* `getParameters()` returns the GET parameters
* `getArguments()` returns all the named varialbles. (Ex: users/{city}/{age}, going to the url users/Lagos/30 will have arguments as 'city':'lagos', 'age':'30' as an array
* `getRawBody()` returns http raw body using `php://input`


### `LogadApp\Router\Response` class
* `write($content)` echos the content
* `withStatus(500)` return http status code 500
* `asJson()` adds a content-type:json header 


### Built With
* [PHP](https://php.net/)

### Installation

_How to install._

1. Download the github repo
2. In the directory the extracted files are run the fllowing code to setup composer autoload
   ```javascript
   composer install
   ```
3. Include the generated autoload in your file, See index.php for example
4. If you're going to use the validator please refer to documentation by rakit

<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch
3. Commit your Changes
4. Push to the Branch
5. Open a Pull Request

<!-- CONTACT -->
## Contact
Michael Arawole - mycodemichael@gmail.com
