<?php

	namespace Puzzle\Core;

	use Puzzle\Component\Http\Response;

	use Psr\Http\Message\ServerRequestInterface;
	use Psr\Http\Message\ResponseInterface;


	abstract class View {

		/** @var Context */
		protected $context;

		/** @var ServerRequestInterface */
		protected $request;

		/** @var Model */
		protected $model;

		/** @var string[] */
		protected $slugs = [];


		/**
		 * @param Context $context
		 * @param ServerRequestInterface $request
		 */
		public function onInitialize(Context $context, ServerRequestInterface $request): void {
			$this->context = $context;
			$this->request = $request;
		}

		public function onDestroy(): void { }

		/**
		 * @param Context $context
		 * @param ServerRequestInterface $request
		 * @return ResponseInterface
		 */
		public abstract function onCreate(Context $context, ServerRequestInterface $request): ResponseInterface;


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
		 * @return ?Context
		 */
		public function getContext(): ?Context {
			return $this->context;
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
