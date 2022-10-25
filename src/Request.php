<?php
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 05 Oct, 2022 9:34 AM
// +------------------------------------------------------------------------+
// | Copyright (c) 2022 Logad Networks. All rights reserved.
// +------------------------------------------------------------------------+

// +----------------------------+
// |
// +----------------------------+
namespace LogadApp\Router;

use Rakit\Validation\Validator;

class Request {
	public array $args;
	/**
	 * @var mixed
	 */
	public $path;
	public string $validationError;

	public function getPOSTBody() {
		return $_POST;
	}
	public function getParsedBody() {
		if (function_exists('cleanBody')) {
			return cleanBody($this->getPOSTBody());
		} else {
			return $this->getPOSTBody();
		}
		return $this;
	}
	public function getParameters() {
		return $_GET;
	}
	public function getArguments():array {
		return $this->args;
	}
	public function getPath() {
		return $this->path;
	}
	public function getFiles() {
		return $_FILES;
	}
	public function getRawBody() {
		return file_get_contents('php://input');
	}
	public function validate($data, array $rules) {
		// If you're using Rakit\Validation\Validator
		$validator = new Validator;
		$validator->setMessages([
			'required' => ':attribute is required',
			'email' => ':email is not valid'
		]);
		$validation = $validator->make($data, $rules);
		$validation->validate();
		if ($validation->fails()) {
			$errorMessage = "";
			foreach ($validation->errors()->firstOfAll() as $errMsg) {
				$errorMessage .= "$errMsg, ";
			}
			$errorMessage = rtrim($errorMessage, ', ');
			$this->setValidationError($errorMessage);
			return false;
		} else {
			return true;
		}
	}
	private function setValidationError(string $errorMessage) {
		$this->validationError = $errorMessage;
	}
}