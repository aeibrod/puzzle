<?php

	namespace Puzzle\Tests\Component\Navigation;

	use Puzzle\Component\Navigation\Router;
	use Puzzle\Component\Navigation\Route;

	use Puzzle\Component\Http\Request;
	use Puzzle\Component\Http\Uri;


	class RouterTest extends \PHPUnit\Framework\TestCase {

		/** @var UriInterface */
		protected $uri;

		/** @var RequestInterface*/
		protected $request;

		public function setUp(): void {

			$this->uri = (new Uri())
			->withScheme('http')
			->withHost('localhost')
			->withPath('/');

			$this->request = new Request($this->uri);

		}



		public function testAny(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('get'));

			$verify = false;

			$router->any(new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testGet(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('GET'));

			$verify = false;

			$router->match('get', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testHead(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('HEAD'));

			$verify = false;

			$router->match('head', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testPost(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('POST'));

			$verify = false;

			$router->match('post', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testPut(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('put'));

			$verify = false;

			$router->match('PUT', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testDelete(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('delete'));

			$verify = false;

			$router->match('DELETE', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testConnect(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('connect'));

			$verify = false;

			$router->match('CONNECT', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testOptions(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('opTIOns'));

			$verify = false;

			$router->match('OPtionS', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testTrace(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('TRAce'));

			$verify = false;

			$router->match('trACE', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testPatch(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('PaTcH'));

			$verify = false;

			$router->match('patch', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}


		public function test404(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('get'));

			$verify = false;

			$router->any(new Route('/article'), function(){
				$this->fail();
			});
			$router->any(new Route('/contact'), function(){
				$this->fail();
			});

			$router->set404(function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testWho(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('get'));

			$route1 = new Route('/article', 'article');
			$route2 = new Route('/contact', 'contact');
			$route3 = new Route('/', 'home');

			$router->any($route1, function(){ });
			$router->any($route2, function(){ });
			$router->any($route3, function(){ });

			$router->execute();

			$this->assertSame($router->who(), $route3);

		}



		public function testCorrectSlug(): void {

			$router = new Router();
			$router->setRequest((new Request($this->uri->withPath('/test/first-second/third')))->withMethod('get'));

			$verifyMatches = [];
			$verifyFirst = '';
			$verifySecond = '';
			$verifyThird = '';

			$router->any(
				new Route('/test/{one}-{two}/{three}'),
				function($matches, $first, $second, $third) use (&$verifyMatches, &$verifyFirst, &$verifySecond, &$verifyThird){
					$verifyMatches = $matches;
					$verifyFirst = $first;
					$verifySecond = $second;
					$verifyThird = $third;
				}
			);

			$router->execute();

			$this->assertSame($verifyMatches, [ 'first', 'second', 'third' ]);
			$this->assertSame($verifyFirst, 'first');
			$this->assertSame($verifySecond, 'second');
			$this->assertSame($verifyThird, 'third');

		}



		public function testGenerateValidRoute(): void {

			$router = new Router();

			$router->any(new Route('/article/{slug}', 'article'), function(){ });

			$this->assertSame(
				$router->generate('article', [ 'slug' => 'my-article' ])->getHref(),
				'http://localhost/article/my-article'
			);

		}

		public function testGenerateInvalidRoute(): void {

			$this->expectException(\InvalidArgumentException::class);

			$router = new Router();

			$router->generate('/random');

		}

	}
