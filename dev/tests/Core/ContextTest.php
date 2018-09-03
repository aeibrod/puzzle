<?php

	namespace Puzzle\Tests\Core;

	use Puzzle\Core\Context;
	use Puzzle\Core\Entity;

	use Puzzle\Component\Http\Request;

	use Puzzle\Exception\ItemNotFoundException;


	class ContextTest extends \PHPUnit\Framework\TestCase {

		public function testSetterAndGetter(): void {

			$context = new Context();

			$context->set('key1', 'value1');
			$context->set('key2', 'value2');

			$this->assertSame($context->get('key1'), 'value1');
			$this->assertSame($context->get('key2'), 'value2');

		}

		public function testInvalidGetter(): void {

			$this->expectException(ItemNotFoundException::class);

			$context = new Context();
			$context->get('randomkey');

		}


		public function testHas(): void {

			$context = new Context();

			$context->set('key1', 'value1');

			$this->assertTrue($context->has('key1'));
			$this->assertFalse($context->has('KEY1'));
			$this->assertFalse($context->has('key2'));

		}

		public function testKeys(): void {

			$context = new Context();

			$context->set('key1', 'value1');
			$context->set('key2', 'value2');
			$context->set('key3', 'value3');

			$keys = [ 'key1', 'key2', 'key3' ];

			$this->assertSame($context->keys(), $keys);

		}


		public function testRegister(): void {

			$context = new Context();

			$entity = $this->createMock(Entity::class);
			$entity->method('getId')
				->willReturn('myid');

			$context->register($entity);

			$this->assertTrue($context->has('myid'));
			$this->assertSame($context->get('myid'), $entity);

		}

		public function testGetRequest(): void {

			$context = new Context();

			$request = $this->createMock(Request::class);
			$request->method('getId')
				->willReturn('myrequest');

			$context->register($request);

			$this->assertSame($context->get('myrequest'), $request);
			$this->assertSame($context->get('myrequest'), $context->getRequest());

		}

	}
