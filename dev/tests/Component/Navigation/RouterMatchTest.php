<?php

	namespace Puzzle\Tests\Component\Navigation;

	use Puzzle\Component\Navigation\Router;
	use Puzzle\Component\Navigation\Route;

	use Puzzle\Component\Http\Request;
	use Puzzle\Component\Http\Uri;


	class RouterMatchTest extends \PHPUnit\Framework\TestCase {

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



		public function testAny1(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('get'));

			$verify = false;

			$router->any(new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testAny2(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('get'));

			$verify = false;

			$router->match('any', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testAny3(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('get'));

			$verify = false;

			$router->match('*', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}



		public function testGet1(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('GET'));

			$verify = false;

			$router->get(new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testGet2(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('GET'));

			$verify = false;

			$router->match('get', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}



		public function testHead1(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('HEAD'));

			$verify = false;

			$router->head(new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testHead2(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('HEAD'));

			$verify = false;

			$router->get(new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testHead3(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('HEAD'));

			$verify = false;

			$router->match('head', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testHead4(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('HEAD'));

			$verify = false;

			$router->match('get', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}



		public function testPost1(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('POST'));

			$verify = false;

			$router->post(new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testPost2(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('POST'));

			$verify = false;

			$router->match('post', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}



		public function testPut1(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('put'));

			$verify = false;

			$router->put(new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testPut2(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('put'));

			$verify = false;

			$router->match('PUT', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}



		public function testDelete1(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('delete'));

			$verify = false;

			$router->delete(new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testDelete2(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('delete'));

			$verify = false;

			$router->match('DELETE', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}



		public function testOptions1(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('opTIOns'));

			$verify = false;

			$router->options(new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testOptions2(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('opTIOns'));

			$verify = false;

			$router->match('OPtionS', new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}



		public function testPatch1(): void {

			$router = new Router();
			$router->setRequest($this->request->withMethod('PaTcH'));

			$verify = false;

			$router->patch(new Route('/'), function() use (&$verify){
				$verify = true;
			});

			$router->execute();

			$this->assertTrue($verify);

		}

		public function testPatch2(): void {

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

	}
