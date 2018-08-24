<?php

	namespace Puzzle\Tests\Component\Link;

	use Puzzle\Component\Link\Link;


	class LinkTest extends \PHPUnit\Framework\TestCase {

		public function testBaseAndIndex(): void {

			$link1 = Link::base();
			$link2 = Link::index();

			$this->assertSame($link1->getHref(), 'http://localhost');
			$this->assertSame($link2->getHref(), 'http://localhost/');

		}

		public function testToString(): void {

			$link = new Link('http://example.com');

			$this->assertEquals($link, 'http://example.com');

		}

	}
