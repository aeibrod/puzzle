<?php

	namespace Puzzle\Tests\Component\Navigation;

	use Puzzle\Component\Navigation\Slug;


	class SlugTest extends \PHPUnit\Framework\TestCase {

		public function testName(): void {

			$slug1 = new Slug('{my-slug}');
			$slug2 = new Slug('{another-slug?}');
			$slug3 = new Slug('{new-slug:[a-z]+}');
			$slug4 = new Slug('{very-cool-slug?:\d+}');

			$this->assertSame($slug1->getName(), 'my-slug');
			$this->assertSame($slug2->getName(), 'another-slug');
			$this->assertSame($slug3->getName(), 'new-slug');
			$this->assertSame($slug4->getName(), 'very-cool-slug');

		}

		public function testAcceptedChars(): void {

			$slug1 = new Slug('{my-slug}');
			$slug2 = new Slug('{another-slug?}');
			$slug3 = new Slug('{new-slug:[a-z]+}');
			$slug4 = new Slug('{very-cool-slug?:\d+}');

			$this->assertSame($slug1->getAcceptedChars(), '[a-zA-Z0-9-_]+');
			$this->assertSame($slug2->getAcceptedChars(), '[a-zA-Z0-9-_]+');
			$this->assertSame($slug3->getAcceptedChars(), '[a-z]+');
			$this->assertSame($slug4->getAcceptedChars(), '\d+');

		}

		public function testIsOptional(): void {

			$slug1 = new Slug('{my-slug}');
			$slug2 = new Slug('{another-slug?}');
			$slug3 = new Slug('{new-slug:[a-z]+}');
			$slug4 = new Slug('{very-cool-slug?:\d+}');

			$this->assertFalse($slug1->isOptional());
			$this->assertTrue($slug2->isOptional());
			$this->assertFalse($slug3->isOptional());
			$this->assertTrue($slug4->isOptional());

		}


		public function testRegexGroup(): void {

			$slug1 = new Slug('{slug}');
			$slug2 = new Slug('{slug?}');

			$this->assertSame($slug1->makeRegexGroup(), '(' . $slug1->getAcceptedChars() . ')');
			$this->assertSame($slug2->makeRegexGroup(), '(?:(' . $slug2->getAcceptedChars() . ')*)');

		}

	}
