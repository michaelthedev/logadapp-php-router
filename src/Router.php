<?php
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 05 Oct, 2022 7:45 AM
// +------------------------------------------------------------------------+
// | Copyright (c) 2022 Logad Networks. All rights reserved.
// +------------------------------------------------------------------------+

// +----------------------------+
// | LogadApp Router Class
// +----------------------------+

declare(strict_types=1);

namespace LogadApp\Router;

class Router {
	private string $basePath;
	private array $handlers;
	private const METHOD_GET = 'GET';
	private const METHOD_POST = 'POST';
	public $notFoundHandler;

	public function setBasePath(string $basePath):void {
		$this->basePath = $basePath;
	}

	## GET HTTP method ##
	public function get(string $path, $handler):void {
		$this->addHandler(self::METHOD_GET, $path, $handler);
	}

	## POST HTTP method ##
	public function post(string $path, $handler):void {
		$this->addHandler(self::METHOD_POST, $path, $handler);
	}

	## 404 Handler ##
	public function addNotFoundHandler($handler):void {
		$this->notFoundHandler = $handler;
	}

	##
	private function addHandler(string $method, string $path, $handler):void {
		$regex = null;
		// named variables
		if (strpos($path, '{')) {
			$regex = preg_replace('/{([a-zA-Z_]+)}/', "(?P<$1>[a-zA-Z0-9_-]+)", $path);
			$regex = str_replace('/(', '\/(', $regex);
		}

		$this->handlers[$method . $path] = [
			'path' => $this->basePath.$path,
			'method' => $method,
			'handler' => $handler,
			'regex' => $regex
		];
	}
	
	## Deploy ##
	public function run(): void {
		$requestUrl = parse_url($_SERVER['REQUEST_URI']);
		$requestPath = $requestUrl['path'];
		$requestMethod = $_SERVER['REQUEST_METHOD'];

		$callback = null;
		$args = [];
		foreach ($this->handlers as $handler) {
			if (!empty($handler['regex']) && $handler['method'] === $requestMethod) {
				$regex = trim($handler['regex'], '/');
				if (preg_match("%$regex%", $requestPath, $matches)) {
					foreach ($matches as $mKey => $value) {
						if (is_numeric($mKey)) continue;
						$args[$mKey] = $value;
						$callback = $handler['handler'];
					}
				}
			} else {
				if ($handler['path'] === $requestPath && $handler['method'] === $requestMethod) {
					$callback = $handler['handler'];
				}
			}
		}

		// Classes as callback
		if (is_array($callback)) {
			$className = array_shift($callback);
			$handler = new $className;
			$methodFunction = array_shift($callback);
			$callback = [$handler, $methodFunction];
		}

		if (!$callback) {
			http_response_code(404);
			if (!empty($this->notFoundHandler)) {
				call_user_func($this->notFoundHandler, $requestPath);
				return;
			}
		}

		$request = new Request;
		$request->args = $args;
		$request->path = $requestPath;
		call_user_func_array($callback, [
			$request,
			new Response
		]);
	}
}