<?php

	namespace Puzzle\Tests\Component\Link;

	use Puzzle\Component\Link\EvolvableLink;


	class EvolvableLinkTest extends \PHPUnit\Framework\TestCase {

		public function testWithHref(): void {

			$link = new EvolvableLink('http://localhost');
			$this->assertSame($link->getHref(), 'http://localhost');

			$link = $link->withHref('http://example.com');
			$this->assertSame($link->getHref(), 'http://example.com');

		}


		public function testWithRel(): void {

			$link = (new EvolvableLink('http://localhost'))
				->withRel('nofollow')
				->withRel('noreferrer')
				->withRel('nofollow')
				->withRel('prev');

			$rels = [ 'nofollow', 'noreferrer', 'prev' ];

			$this->assertSame($link->getRels(), $rels);

		}

		public function testWithoutRel(): void {

			$link = (new EvolvableLink('http://localhost'))
				->withRel('nofollow')
				->withRel('noreferrer')
				->withRel('prev');

			$link = $link->withoutRel('noreferrer');
			$rels = [ 'nofollow', 'prev' ];

			$this->assertSame($link->getRels(), $rels);

		}


		public function testWithAttribute(): void {

			$link = (new EvolvableLink('http://localhost'))
				->withAttribute('key1', 'value1')
				->withAttribute('key2', 'value2')
				->withAttribute('key3', 'value3');

			$attributes = [
				'key1' => 'value1',
				'key2' => 'value2',
				'key3' => 'value3'
			];

			$this->assertSame($link->getAttributes(), $attributes);

		}

		public function testWithoutAttributes(): void {

			$link = (new EvolvableLink('http://localhost'))
				->withAttribute('key1', 'value1')
				->withAttribute('key2', 'value2')
				->withAttribute('key3', 'value3');

			$link = $link->withoutAttribute('key2');
			$attributes = [
				'key1' => 'value1',
				'key3' => 'value3'
			];

			$this->assertSame($link->getAttributes(), $attributes);

		}

	}
