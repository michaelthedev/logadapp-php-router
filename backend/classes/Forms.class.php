<?php
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 05 Oct, 2022 9:04 AM
// +------------------------------------------------------------------------+

// +----------------------------+
// | Forms Handler
// +----------------------------+

use LogadApp\Router\Response;
use LogadApp\Router\Request;

class Forms {
	public function contactForm(Request $request, Response $response) {
		$body = $request->getPOSTBody();
		$request->validate($request->getPOSTBody(), [
			'name' => 'required',
			'email' => 'required|email',
			'message' => 'required'
		]);
		if (!empty($request->validationError)) {
			return $response->write('Error - '.$request->validationError)
				->withStatus(400);
		}

		return $response->write(json_encode([
			'error' => false,
			'message' => 'Message sent',
			'data' => [
				'name' => $body['name'],
				'email' => $body['email'],
				'message' => $body['message']
			]
		]))->asJson()->withStatus(201);
	}
}