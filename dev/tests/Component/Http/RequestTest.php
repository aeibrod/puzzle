<?php

	namespace Puzzle\Tests\Component\Http;

	use Puzzle\Component\Http\Request;
	use Puzzle\Component\Http\Uri;


	class RequestTest extends \PHPUnit\Framework\TestCase {

		/** @var UriInterface */
		protected $uri;


		public function setUp(): void {
			$this->uri = new Uri();
		}


		public function testValidHttpMethods(): void {

			$get     = (new Request($this->uri))->withMethod('get');
			$head    = (new Request($this->uri))->withMethod('head');
			$post    = (new Request($this->uri))->withMethod('post');
			$put     = (new Request($this->uri))->withMethod('put');
			$delete  = (new Request($this->uri))->withMethod('delete');
			$connect = (new Request($this->uri))->withMethod('connect');
			$options = (new Request($this->uri))->withMethod('options');
			$patch   = (new Request($this->uri))->withMethod('patch');
			$trace   = (new Request($this->uri))->withMethod('trace');

			// phpcs:disable Generic.Functions.FunctionCallArgumentSpacing.TooMuchSpaceAfterComma

			$this->assertSame($get->getMethod(),     'GET');
			$this->assertSame($head->getMethod(),    'HEAD');
			$this->assertSame($post->getMethod(),    'POST');
			$this->assertSame($put->getMethod(),     'PUT');
			$this->assertSame($delete->getMethod(),  'DELETE');
			$this->assertSame($connect->getMethod(), 'CONNECT');
			$this->assertSame($options->getMethod(), 'OPTIONS');
			$this->assertSame($patch->getMethod(),   'PATCH');
			$this->assertSame($trace->getMethod(),   'TRACE');

			// phpcs:enable

		}

		public function testInvalidHttpMethod(): void {

			$this->expectException(\InvalidArgumentException::class);

			$request = new Request($this->uri);
			$request = $request->withMethod('invalid');

		}



		public function testWithUriWithoutPreserveHost(): void {

			$request = new Request($this->uri);
			$this->assertFalse($request->hasHeader('host'));

			$request = $request->withUri(
				(new Uri())->withHost('example.com')
			);

			$this->assertTrue($request->hasHeader('host'));
			$this->assertSame($request->getHeaderLine('host'), 'example.com');

		}

		public function testWithUriWithPreserveHost(): void {

			$request = new Request($this->uri);

			$request = $request->withUri(
				(new Uri())->withHost('example.com'),
				true
			);

			$this->assertFalse($request->hasHeader('host'));

		}



		public function testServerParams(): void {

			$request1 = new Request($this->uri);
			$request2 = new Request($this->uri, [ 'key' => 'value' ]);

			$this->assertEmpty($request1->getServerParams());
			$this->assertSame($request2->getServerParams(), [ 'key' => 'value' ]);

		}

		public function testCookieParams(): void {

			$request = new Request($this->uri);

			$this->assertEmpty($request->getCookieParams());

			$request = $request->withCookieParams([ 'key' => 'value' ]);
			$this->assertSame($request->getCookieParams(), [ 'key' => 'value' ]);

		}

		public function testQueryParams(): void {

			$request = new Request($this->uri);

			$this->assertEmpty($request->getQueryParams());

			$request = $request->withQueryParams([ 'key' => 'value' ]);
			$this->assertSame($request->getQueryParams(), [ 'key' => 'value' ]);

		}



		public function testWithAttribute(): void {

			$request = new Request($this->uri);

			$request = $request->withAttribute('attribute', 'value');
			$this->assertSame($request->getAttribute('attribute'), 'value');

		}

		public function testWithoutAttribute(): void {

			$request = new Request($this->uri);

			$request = $request->withAttribute('attribute', 'value');
			$request = $request->withoutAttribute('attribute');

			$this->assertNotSame($request->getAttribute('attribute'), 'value');

		}


		public function testGetAttributes(): void {

			$request = new Request($this->uri);

			$request = $request->withAttribute('attribute1', 'value1');
			$request = $request->withAttribute('attribute2', 'value2');
			$request = $request->withAttribute('attribute3', 'value3');

			$this->assertSame(
				$request->getAttributes(),
				[
					'attribute1' => 'value1',
					'attribute2' => 'value2',
					'attribute3' => 'value3',
				]
			);

		}

		public function testDefaultAttributeValue(): void {

			$request = new Request($this->uri);

			$this->assertSame($request->getAttribute('attribute', 'default'), 'default');

		}

	}
