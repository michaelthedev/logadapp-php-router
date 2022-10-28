<?php
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 07 Oct, 2022 9:25 PM
// +------------------------------------------------------------------------+

namespace LogadApp\Router;

class Response {
	public $body;

	public function asJson(): Response {
		header('Content-Type: application/json');
		return $this;
	}
	public function asArray(): array {
		return $this->body;
	}
	public function write($content): Response {
		$this->body = $content;
		echo $this->body;
		return $this;
	}
	public function withStatus(int $statusCode): Response {
		http_response_code($statusCode);
		return $this;
	}
}