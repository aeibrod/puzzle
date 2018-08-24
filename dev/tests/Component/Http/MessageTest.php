<?php

	namespace Puzzle\Tests\Component\Http;

	use Puzzle\Component\Http\Response;
	use Puzzle\Component\Http\Stream;


	class MessageTest extends \PHPUnit\Framework\TestCase {

		public function testWithHeader(): void {

			$message = new Response();

			$message = $message->withHeader('header1', 'value1');
			$message = $message->withHeader('header2', 'value2');
			$message = $message->withHeader('header1', 'value3');

			$this->assertSame($message->getHeader('header1'), [ 'value3' ]);
			$this->assertSame($message->getHeader('header2'), [ 'value2' ]);

		}

		public function testWithAddedHeader(): void {

			$message = new Response();

			$message = $message->withAddedHeader('header1', 'value1');
			$message = $message->withAddedHeader('header2', 'value2');
			$message = $message->withAddedHeader('header1', 'value3');

			$this->assertSame($message->getHeader('header1'), [ 'value1', 'value3' ]);
			$this->assertSame($message->getHeader('header2'), [ 'value2' ]);

		}

		public function testWithoutHeader(): void {

			$message = new Response();

			$message = $message->withAddedHeader('header', 'value1');
			$message = $message->withoutHeader('header');
			$message = $message->withAddedHeader('header', 'value2');
			$message = $message->withAddedHeader('header', 'value3');

			$this->assertSame($message->getHeader('header'), [ 'value2', 'value3' ]);

		}


		public function testGetHeaders(): void {

			$message = new Response();

			$message = $message->withHeader('header1', 'value1');
			$message = $message->withAddedHeader('header1', 'another-value1');
			$message = $message->withHeader('header2', 'value2');
			$message = $message->withHeader('header3', 'value3');

			$this->assertSame(
				$message->getHeaders(),
				[
					'header1' => [ 'value1', 'another-value1' ],
					'header2' => [ 'value2' ],
					'header3' => [ 'value3' ]
				]
			);

		}

		public function testGetHeaderLine(): void {

			$message = new Response();

			$message = $message->withAddedHeader('header1', 'value1');
			$message = $message->withAddedHeader('header1', 'another-value1');
			$message = $message->withAddedHeader('header2', 'value2');

			$this->assertSame($message->getHeaderLine('header1'), 'value1, another-value1');
			$this->assertSame($message->getHeaderLine('header2'), 'value2');

		}


		public function testHasHeader(): void {

			$message = new Response();

			$message = $message->withHeader('header1', 'value1');
			$message = $message->withAddedHeader('header2', 'value2');

			$this->assertTrue($message->hasHeader('header1'));
			$this->assertTrue($message->hasHeader('header2'));
			$this->assertFalse($message->hasHeader('random-header-name'));

		}

		public function testCaseInsensitiveHeaderName(): void {

			$message = new Response();

			$message = $message->withAddedHeader('CaSe-InSeNsItIvE', 'My value');

			$this->assertTrue($message->hasHeader('case-insensitive'));
			$this->assertTrue($message->hasHeader('CASE-INSENSITIVE'));

			$this->assertSame($message->getHeaderLine('case-insensitive'), 'My value');

		}



		public function testProtocolVersion(): void {

			$message = new Response();

			$this->assertSame($message->getProtocolVersion(), '1.1');

			$message = $message->withProtocolVersion('2');
			$this->assertSame($message->getProtocolVersion(), '2');

		}

		public function testBody(): void {

			$message = new Response();
			$body    = new Stream();

			$message = $message->withBody($body);

			$this->assertSame($message->getBody(), $body);

		}

	}
