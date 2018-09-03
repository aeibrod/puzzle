<?php

	namespace Puzzle\Tests\Core;

	use Puzzle\Core\Container;
	use Puzzle\Core\Entity;

	use Puzzle\Component\Http\Request;

	use Puzzle\Exception\ItemNotFoundException;


	class ContainerTest extends \PHPUnit\Framework\TestCase {

		public function testSetterAndGetter(): void {

			$container = new Container();

			$container->set('key1', 'value1');
			$container->set('key2', 'value2');

			$this->assertSame($container->get('key1'), 'value1');
			$this->assertSame($container->get('key2'), 'value2');

		}

		public function testInvalidGetter(): void {

			$this->expectException(ItemNotFoundException::class);

			$container = new Container();
			$container->get('randomkey');

		}


		public function testHas(): void {

			$container = new Container();

			$container->set('key1', 'value1');

			$this->assertTrue($container->has('key1'));
			$this->assertFalse($container->has('KEY1'));
			$this->assertFalse($container->has('key2'));

		}

		public function testKeys(): void {

			$container = new Container();

			$container->set('key1', 'value1');
			$container->set('key2', 'value2');
			$container->set('key3', 'value3');

			$keys = [ 'key1', 'key2', 'key3' ];

			$this->assertSame($container->keys(), $keys);

		}


		public function testRegister(): void {

			$container = new Container();

			$entity = $this->createMock(Entity::class);
			$entity->method('getId')
				->willReturn('myid');

			$container->register($entity);

			$this->assertTrue($container->has('myid'));
			$this->assertSame($container->get('myid'), $entity);

		}

		public function testGetRequest(): void {

			$container = new Container();

			$request = $this->createMock(Request::class);
			$request->method('getId')
				->willReturn('myrequest');

			$container->register($request);

			$this->assertSame($container->get('myrequest'), $request);
			$this->assertSame($container->get('myrequest'), $container->getRequest());

		}

	}
