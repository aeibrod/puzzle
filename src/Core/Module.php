<?php

	namespace Puzzle\Core;

	use Puzzle\Component\Http\Response;

	use Psr\Http\Message\ServerRequestInterface;


	abstract class Module {

		/** @var Container */
		protected $container;

		/** @var ServerRequestInterface */
		protected $request;

		/** @var Controller[] */
		protected $instancedControllers = [];


		/**
		 * @param Container $container
		 * @param ServerRequestInterface $request
		 */
		public function onInitialize(Container $container, ServerRequestInterface $request): void {
			$this->container = $container;
			$this->request = $request;
		}

		public function onDestroy(): void {

			foreach ($this->instancedControllers as $controller){
				$controller->onDestroy();
			}

		}

		/**
		 * @param Container $container
		 * @param ServerRequestInterface $request
		 */
		public abstract function onCreate(Container $container, ServerRequestInterface $request): void;


		/**
		 * @param Controller|string $controller
		 * @param string[] $slugs = []
		 * @throws \InvalidArgumentException Could not load a class that not inherit from Controller
		 * @return Controller
		 */
		public function loadController($controller, array $slugs = []): Controller {

			if (!is_subclass_of($controller, Controller::class)){
				throw new \InvalidArgumentException('Could not load a class that not inherit from Controller');
			}

			if (is_string($controller)){
				$controller = new $controller();
			}

			$this->instancedControllers[] = $controller;

			$controller->onInitialize($this->container, $this->request);
			$controller->setSlugs($slugs);

			$response = $controller->onCreate($controller->getContainer(), $controller->getRequest());

			Response::send($response);

			return $controller;
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

	}
