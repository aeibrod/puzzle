<?php

	namespace Puzzle\Core;

	use Puzzle\Component\Http\Response;

	use Psr\Http\Message\ServerRequestInterface;
	use Psr\Http\Message\ResponseInterface;


	abstract class Controller {

		/** @var Context */
		protected $context;

		/** @var ServerRequestInterface */
		protected $request;

		/** @var Model */
		protected $model;

		/** @var View[] */
		protected $instancedViews = [];

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

		public function onDestroy(): void {

			if ($this->model !== null){
				$this->model->onDestroy();
			}

			foreach ($this->instancedViews as $view){
				$view->onDestroy();
			}

		}

		/**
		 * @param Context $context
		 * @param ServerRequestInterface $request
		 * @return ResponseInterface
		 */
		public abstract function onCreate(Context $context, ServerRequestInterface $request): ResponseInterface;


		/**
		 * @param Model|string $model
		 * @throws \InvalidArgumentException Could not load a class that not inherit from Model
		 * @throws \RuntimeException The controller has already loaded a Model
		 * @return Model
		 */
		public function loadModel($model): Model {

			if (!is_subclass_of($model, Model::class)){
				throw new \InvalidArgumentException('Could not load a class that not inherit from Model');
			}

			if ($this->model !== null){
				throw new \RuntimeException('The controller has already loaded a Model');
			}

			if (is_string($model)){
				$model = new $model();
			}

			$this->model = $model;

			$this->model->onInitialize($this->context, $this->request);
			$this->model->setSlugs($this->slugs);
			$this->model->onCreate($this->model->getContext(), $this->model->getRequest());

			return $this->model;
		}

		/**
		 * @param View|string $view
		 * @throws \InvalidArgumentException Could not load a class that not inherit from View
		 * @return ResponseInterface
		 */
		public function loadView($view): ResponseInterface {

			if (!is_subclass_of($view, View::class)){
				throw new \InvalidArgumentException('Could not load a class that not inherit from View');
			}

			if (is_string($view)){
				$view = new $view();
			}

			if ($this->model !== null){
				$view->setModel($this->model);
			}

			$this->instancedViews[] = $view;

			$view->onInitialize($this->context, $this->request);
			$view->setSlugs($this->slugs);

			$response = $view->onCreate($view->getContext(), $view->getRequest());

			return $response;
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
