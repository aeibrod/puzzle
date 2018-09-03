<?php

	namespace Puzzle\Core;

	use Puzzle\Component\Http\Response;

	use Psr\Http\Message\ServerRequestInterface;
	use Psr\Http\Message\ResponseInterface;


	abstract class View {

		/** @var Container */
		protected $container;

		/** @var ServerRequestInterface */
		protected $request;

		/** @var Model */
		protected $model;

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
		 * @return ResponseInterface
		 */
		public abstract function onCreate(Container $container, ServerRequestInterface $request): ResponseInterface;


		/**
		 * @param Model $model
		 */
		public function setModel(Model $model): void {
			$this->model = $model;
		}

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
		 * @return ?Model
		 */
		public function getModel(): ?Model {
			return $this->model;
		}

		/**
		 * @return string[]
		 */
		public function getSlugs(): array {
			return $this->slugs;
		}

	}
