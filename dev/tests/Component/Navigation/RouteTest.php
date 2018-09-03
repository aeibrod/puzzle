<?php

	namespace Puzzle\Tests\Component\Navigation;

	use Puzzle\Component\Navigation\Route;
	use Puzzle\Component\Navigation\Slug;

	use Puzzle\Component\Http\Request;
	use Puzzle\Component\Http\Uri;


	class RouteTest extends \PHPUnit\Framework\TestCase {

		public function testGenerate(): void {

			$route1 = new Route('/simple');
			$route2 = new Route('/with-slug/{slug}');
			$route3 = new Route('/optional-slug/{slug?}');
			$route4 = new Route('/multiple-slugs/{slug1}-{slug2}');
			$route5 = new Route('/characters/{slug:[a-zA-Z]+}');
			$route6 = new Route('/complex/{slug1}-{slug2:\d+}/{slug3?:[a-z]+}');


			$this->assertSame(
				$route1->generate()->getHref(),
				'http://localhost/simple'
			);

			$this->assertSame(
				$route2->generate([ 'slug' => 'my-slug' ])->getHref(),
				'http://localhost/with-slug/my-slug'
			);

			$this->assertSame(
				$route3->generate([ 'slug' => 'my-slug' ])->getHref(),
				'http://localhost/optional-slug/my-slug'
			);

			$this->assertSame(
				$route3->generate()->getHref(),
				'http://localhost/optional-slug/'
			);

			$this->assertSame(
				$route4->generate([ 'slug1' => 'first', 'slug2' => 'second' ])->getHref(),
				'http://localhost/multiple-slugs/first-second'
			);

			$this->assertSame(
				$route5->generate([ 'slug' => 'myslug' ])->getHref(),
				'http://localhost/characters/myslug'
			);

			$this->assertSame(
				$route6->generate([ 'slug1' => 'my-slug', 'slug2' => '123', 'slug3' => 'abcde' ])->getHref(),
				'http://localhost/complex/my-slug-123/abcde'
			);

			$this->assertSame(
				$route6->generate([ 'slug1' => 'my-slug', 'slug2' => '123' ])->getHref(),
				'http://localhost/complex/my-slug-123/'
			);

		}

		public function testGenerateInvalid1(): void {

			$this->expectException(\InvalidArgumentException::class);

			$route = new Route('/with-slug/{slug}');
			$route->generate();

		}

		public function testGenerateInvalid2(): void {

			$this->expectException(\InvalidArgumentException::class);

			$route = new Route('/with-slug/{slug:\d+}');
			$route->generate([ 'slug' => 'my-slug' ]);

		}


		public function testCorrespond(): void {

			$uri = (new Uri())
			->withScheme('http')
			->withHost('localhost');

			$route1 = new Route('/simple');
			$route2 = new Route('/with-slug/{slug}');
			$route3 = new Route('/optional-slug/{slug?}');
			$route4 = new Route('/multiple-slugs/{slug1}-{slug2}');
			$route5 = new Route('/characters/{slug:[a-zA-Z]+}');
			$route6 = new Route('/complex/{slug1}-{slug2:\d+}/{slug3?:[a-z]+}');


			$this->assertTrue(
				$route1->correspond(new Request($uri->withPath('/simple')))
			);

			$this->assertFalse(
				$route1->correspond(new Request($uri->withPath('/random')))
			);

			$this->assertTrue(
				$route2->correspond(new Request($uri->withPath('/with-slug/my-slug')))
			);

			$this->assertFalse(
				$route2->correspond(new Request($uri->withPath('/with-slug/')))
			);

			$this->assertTrue(
				$route3->correspond(new Request($uri->withPath('/optional-slug/my-slug')))
			);

			$this->assertTrue(
				$route3->correspond(new Request($uri->withPath('/optional-slug/')))
			);

			$this->assertFalse(
				$route3->correspond(new Request($uri->withPath('/optional-slug')))
			);

			$this->assertTrue(
				$route4->correspond(new Request($uri->withPath('/multiple-slugs/first-second')))
			);

			$this->assertTrue(
				$route4->correspond(new Request($uri->withPath('/multiple-slugs/a-b-c-d')))
			);

			$this->assertFalse(
				$route4->correspond(new Request($uri->withPath('/multiple-slugs/abcd')))
			);

			$this->assertTrue(
				$route5->correspond(new Request($uri->withPath('/characters/VeryCoolSlug')))
			);

			$this->assertFalse(
				$route5->correspond(new Request($uri->withPath('/characters/slug123')))
			);

			$this->assertTrue(
				$route6->correspond(new Request($uri->withPath('/complex/my-slug-123/abcde')))
			);

			$this->assertTrue(
				$route6->correspond(new Request($uri->withPath('/complex/my-slug-123/')))
			);

			$this->assertFalse(
				$route6->correspond(new Request($uri->withPath('/complex/my-slug/123/abcde')))
			);

		}



		public function testHasName(): void {

			$route1 = new Route('/', 'home');
			$route2 = new Route('/');

			$this->assertTrue($route1->hasName());
			$this->assertFalse($route2->hasName());

		}

		public function testExtractSlugs(): void {

			$route = new Route('/path/{slug1}-{slug2:\d+}/{slug3?:[a-z]+}');

			$slugs = [
				new Slug('{slug1}'),
				new Slug('{slug2:\d+}'),
				new Slug('{slug3?:[a-z]+}')
			];

			$this->assertEquals($route->getSlugs(), $slugs);

		}

	}
