<?php

	namespace Puzzle\Tests\Component\Http;

	use Puzzle\Component\Http\Uri;


	class UriTest extends \PHPUnit\Framework\TestCase {

		public function testScheme(): void {

			$uri = new Uri();

			$this->assertEmpty($uri->getScheme());

			$uri = $uri->withScheme('http');
			$this->assertSame($uri->getScheme(), 'http');

			$uri = $uri->withScheme('https');
			$this->assertSame($uri->getScheme(), 'https');

			$uri = $uri->withScheme('ftp');
			$this->assertSame($uri->getScheme(), 'ftp');

		}

		public function testInsensitiveCaseScheme(): void {

			$uri = new Uri();

			$uri = $uri->withScheme('HTTP');
			$this->assertSame($uri->getScheme(), 'http');

			$uri = $uri->withScheme('HtTpS');
			$this->assertSame($uri->getScheme(), 'https');

		}

		public function testUserInfo(): void {

			$uri = new Uri();

			$this->assertEmpty($uri->getUserInfo());

			$uri = $uri->withUserInfo('username');
			$this->assertSame($uri->getUserInfo(), 'username');

			$uri = $uri->withUserInfo('username', 'password');
			$this->assertSame($uri->getUserInfo(), 'username:password');

		}

		public function testHost(): void {

			$uri = new Uri();

			$this->assertEmpty($uri->getHost());

			$uri = $uri->withHost('example.com');
			$this->assertSame($uri->getHost(), 'example.com');

		}

		public function testPort(): void {

			$uri = new Uri();

			$this->assertNull($uri->getPort());

			$uri = $uri->withPort(80);
			$this->assertSame($uri->getPort(), 80);

		}

		public function testPath(): void {

			$uri = new Uri();

			$this->assertSame($uri->getPath(), '/');

			$uri = $uri->withPath('/my/path');
			$this->assertSame($uri->getPath(), '/my/path');

		}

		public function testQuery(): void {

			$uri = new Uri();

			$this->assertEmpty($uri->getQuery());

			$uri = $uri->withQuery('key1=value1&key2=value2');
			$this->assertSame($uri->getQuery(), 'key1=value1&key2=value2');

		}

		public function testFragment(): void {

			$uri = new Uri();

			$this->assertEmpty($uri->getFragment());

			$uri = $uri->withFragment('myfragment');
			$this->assertSame($uri->getFragment(), 'myfragment');

		}



		public function testAuthority(): void {

			$uri1 = (new Uri())
				->withUserInfo('username')
				->withHost('example.com')
				->withPort(8080);

			$uri2 = (new Uri())
				->withScheme('http')
				->withUserInfo('username', 'password')
				->withHost('example.com')
				->withPort(80);

			$uri3 = (new Uri())
				->withScheme('ftp')
				->withHost('example.com')
				->withPort(8080);

			$uri4 = (new Uri())
				->withUserInfo('username', 'password')
				->withHost('example.com');


			$this->assertSame($uri1->getAuthority(), 'username@example.com:8080');
			$this->assertSame($uri2->getAuthority(), 'username:password@example.com');
			$this->assertSame($uri3->getAuthority(), 'example.com:8080');
			$this->assertSame($uri4->getAuthority(), 'username:password@example.com');

		}

		public function testToString(): void {

			$uri1 = (new Uri())
				->withScheme('https')
				->withHost('example.com')
				->withPort(443)
				->withPath('/my/path')
				->withFragment('myfragment');

			$uri2 = (new Uri())
				->withScheme('http')
				->withUserInfo('username')
				->withHost('example.com')
				->withPort(8080)
				->withQuery('key=value')
				->withFragment('myfragment');


			$this->assertSame($uri1->__toString(), 'https://example.com/my/path#myfragment');
			$this->assertSame($uri2->__toString(), 'http://username@example.com:8080/?key=value#myfragment');

		}

	}
