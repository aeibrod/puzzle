<?php

	namespace Puzzle\Component\Render;

	use Puzzle\Core\Model;
	use Puzzle\Core\Container;

	use Puzzle\Component\Http\Response;

	use Psr\Http\Message\ResponseInterface;


	class PhpRenderer {

		/** @var Container */
		protected $container;

		/** @var Model */
		protected $model;

		/** @var string[] */
		protected $directories = [];

		/** @var string[] */
		protected $extensions = [ 'php', 'html', 'htm' ];

		/** @var string[] */
		protected $slugs = [];


		/**
		 * @param Controller|View $from
		 * @return PhpRenderer
		 */
		public static function create($from): PhpRenderer {

			$renderer = new PhpRenderer();

			if ($from->getContainer() !== null){
				$renderer->setContainer($from->getContainer());
			}

			if ($from->getModel() !== null){
				$renderer->setModel($from->getModel());
			}

			$renderer->setSlugs($from->getSlugs());

			return $renderer;
		}


		/**
		 * @param Container $container
		 * @return PhpRenderer
		 */
		public function setContainer(Container $container): PhpRenderer {
			$this->container = $container;
			return $this;
		}

		/**
		 * @param Model $model
		 * @return PhpRenderer
		 */
		public function setModel(Model $model): PhpRenderer {
			$this->model = $model;
			return $this;
		}

		/**
		 * @param string[] $slugs
		 * @return PhpRenderer
		 */
		public function setSlugs(array $slugs): PhpRenderer {
			$this->slugs = $slugs;
			return $this;
		}


		/**
		 * @param string|string[] $directory Absolute path
		 * @return PhpRenderer
		 */
		public function addDirectory($directory): PhpRenderer {

			if (is_string($directory)){
				$directory = [$directory];
			}

			$this->directories = array_merge($this->directories, $directory);

			return $this;
		}

		/**
		 * @param string|string[] $extension
		 * @return PhpRenderer
		 */
		public function addFileExtension($extension): PhpRenderer {

			if (is_string($extension)){
				$extension = [$extension];
			}

			$this->extensions = array_merge($this->extensions, $extension);

			return $this;
		}


		/**
		 * @return ?Container
		 */
		public function getContainer(): ?Container {
			return $this->container;
		}

		/**
		 * @return ?Model
		 */
		public function getModel(): ?Model {
			return $this->model;
		}

		/**
		 * @return string[]
		 */
		public function getDirectories(): array {
			return $this->directories;
		}

		/**
		 * @return string[]
		 */
		public function getFileExtensions(): array {
			return $this->extensions;
		}

		/**
		 * @return string[]
		 */
		public function getSlugs(): array {
			return $this->slugs;
		}


		/**
		 * @return PhpRenderer
		 */
		public function clearDirectories(): PhpRenderer {
			$this->directories = [];
			return $this;
		}

		/**
		 * @return PhpRenderer
		 */
		public function clearFileExtensions(): PhpRenderer {
			$this->extensions = [];
			return $this;
		}

		/**
		 * @return PhpRenderer
		 */
		public function clearSlugs(): PhpRenderer {
			$this->slugs = [];
			return $this;
		}


		/**
		 * @param string $template
		 * @return ResponseInterface
		 */
		public function render(string $template): ResponseInterface {

			// get all directories
			foreach ($this->directories as $directory)
			{

				if (!file_exists($directory)){
					continue;
				}

				// get all files into the current directory
				foreach (array_diff(scandir($directory), [ '.', '..' ]) as $file)
				{

					// get all defined extensions
					foreach ($this->extensions as $extension)
					{

						// check if the file correspond the specified template name
						if ($template . '.' . $extension === $file)
						{

							$container = $this->container;
							$model   = $this->model;
							$slugs = $this->slugs;

							$path = $directory . '/' . $file;

							unset($template);
							unset($directory);
							unset($file);
							unset($extension);

							ob_start();
							include $path;

							return new Response(ob_get_clean());
						}
					}
				}
			}

			return new Response();
		}

	}
