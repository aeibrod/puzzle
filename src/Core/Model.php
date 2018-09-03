<?php

	namespace Puzzle\Core;

	use Psr\Http\Message\ServerRequestInterface;


	abstract class Model {

		/** @var Container */
		protected $container;

		/** @var ServerRequestInterface */
		protected $request;

		/** @var string[] */
		protected $slugs = [];


		/**
		 * @param Container $container
		 * @param ServerRequestInterface $request
		 */
		public function onInitialize(Container $container, ServerRequestInterface $request): void {
			$this->container = $container;
			$this->request = $request;
		}

		public function onDestroy(): void { }

		/**
		 * @param Container $container
		 * @param ServerRequestInterface $request
		 */
		public abstract function onCreate(Container $container, ServerRequestInterface $request): void;


		/**
		 * @param string[] $slugs
		 */
		public function setSlugs(array $slugs): void {
			$this->slugs = $slugs;
		}


		/**
		 * @return ?Container
		 */
		public function getContainer(): ?Container {
			return $this->container;
		}

		/**
		 * @return ?ServerRequestInterface
		 */
		public function getRequest(): ?ServerRequestInterface {
			return $this->request;
		}

		/**
		 * @return string[]
		 */
		public function getSlugs(): array {
			return $this->slugs;
		}

		/**
		 * @return string[][]
		 */
		public function getCSS(): array {
			return [ ];
		}

		/**
		 * @return string[][]
		 */
		public function getJS(): array {
			return [ ];
		}


		/**
		 * @param int $tab = 2
		 * @param bool $write = true
		 * @return string
		 */
		public function importCSS(int $tab = 2, bool $write = true): string {

			$result = '';
			$getcss = $this->getCSS();

			foreach ($getcss as $index => $css){

				$result .= '<link ';

				if (!array_key_exists('rel', $css)){
					$result .= 'rel="stylesheet" ';
				}

				foreach ($css as $key => $value){

					if (is_int($key)){
						$result .= htmlspecialchars($value) . ' ';
					}

					else {
						$result .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
					}

				}

				$result .= '/>' . "\n";

				for ($i = 0; $i < $tab; $i++){
					$result .= $index !== count($getcss) - 1  ? "\t" : '';
				}

			}

			if ($write){
				echo $result;
			}

			return $result;
		}

		/**
		 * @param int $tab = 2
		 * @param bool $write = true
		 * @return string
		 */
		public function importJS(int $tab = 2, bool $write = true): string {

			$result = '';
			$getjs = $this->getJS();

			foreach ($getjs as $index => $js){

				$result .= '<script ';

				foreach ($js as $key => $value){

					if (is_int($key)){
						$result .= htmlspecialchars($value) . ' ';
					}

					else {
						$result .= htmlspecialchars($key) . '="' . htmlspecialchars($value) . '" ';
					}

				}

				$result .= '></script>' . "\n";

				for ($i = 0; $i < $tab; $i++){
					$result .= $index !== count($getjs) - 1 ? "\t" : '';
				}

			}

			if ($write){
				echo $result;
			}

			return $result;
		}

	}
