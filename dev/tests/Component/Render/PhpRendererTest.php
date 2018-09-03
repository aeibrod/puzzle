<?php

	namespace Puzzle\Tests\Component\Render;

	use Puzzle\Core\Container;
	use Puzzle\Core\Model;

	use Puzzle\Component\Render\PhpRenderer;

	class PhpRendererTest extends \PHPUnit\Framework\TestCase {

		public function testContainerSetterAndGetter(): void {

			$render = new PhpRenderer();
			$container = $this->createMock(Container::class);

			$this->assertNull($render->getContainer());

			$render->setContainer($container);

			$this->assertSame($render->getContainer(), $container);

		}

		public function testModelSetterAndGetter(): void {

			$render = new PhpRenderer();
			$model = $this->createMock(Model::class);

			$this->assertNull($render->getModel());

			$render->setModel($model);

			$this->assertSame($render->getModel(), $model);

		}


		public function testSlugsSetterAndGetter(): void {

			$render = new PhpRenderer();
			$slugs = [ 'item1', 'item2' ];

			$this->assertEmpty($render->getSlugs());

			$render->setSlugs($slugs);

			$this->assertSame($render->getSlugs(), $slugs);

		}

		public function testDirectorySetterAndGetter(): void {

			$render = new PhpRenderer();

			$this->assertEmpty($render->getDirectories());

			$render->addDirectory('/my-dir');
			$render->addDirectory('/my-second-dir');

			$this->assertSame($render->getDirectories(), [ '/my-dir', '/my-second-dir' ]);

		}

		public function testFileExtensionSetterAndGetter(): void {

			$render = new PhpRenderer();

			$this->assertSame($render->getFileExtensions(), [ 'php', 'html', 'htm' ]);

			$render->addFileExtension('txt');
			$render->addFileExtension('md');

			$this->assertSame(
				$render->getFileExtensions(),
				[ 'php', 'html', 'htm', 'txt', 'md' ]
			);

		}


		public function testClearSlugs(): void {

			$render = new PhpRenderer();
			$slugs = [ 'item1', 'item2' ];

			$render->setSlugs($slugs);
			$this->assertSame($render->getSlugs(), $slugs);

			$render->clearSlugs();
			$this->assertEmpty($render->getSlugs());

		}

		public function testClearDirectories(): void {

			$render = new PhpRenderer();

			$render->addDirectory('/my-dir');
			$this->assertSame($render->getDirectories(), [ '/my-dir' ]);

			$render->clearDirectories();
			$this->assertEmpty($render->getDirectories());

		}

		public function testClearFileExtensions(): void {

			$render = new PhpRenderer();

			$this->assertSame($render->getFileExtensions(), [ 'php', 'html', 'htm' ]);

			$render->clearFileExtensions();
			$this->assertEmpty($render->getFileExtensions());

		}

	}
