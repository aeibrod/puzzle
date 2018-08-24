<?php

	namespace Puzzle\Tests\Component\Http;

	use Puzzle\Component\Http\Response;


	class ResponseTest extends \PHPUnit\Framework\TestCase {

		public function testStatusCode(): void {

			$response = new Response();

			$this->assertSame($response->getStatusCode(), 200);

			$response = $response->withStatus(404);
			$this->assertSame($response->getStatusCode(), 404);

		}

		public function testReasonPhrase(): void {

			$response = new Response();

			$this->assertSame($response->getReasonPhrase(), 'OK');

			$response = $response->withStatus(404, 'Not Found');
			$this->assertSame($response->getReasonPhrase(), 'Not Found');

		}

		public function testAutomaticReasonPhrase(): void {

			$response = new Response();

			$response = $response->withStatus(404);
			$this->assertSame($response->getReasonPhrase(), 'Not Found');

			$response = $response->withStatus(500);
			$this->assertSame($response->getReasonPhrase(), 'Internal Server Error');

		}

	}
