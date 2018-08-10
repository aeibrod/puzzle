<?php

	namespace Puzzle\Core;

	use Puzzle\Component\Http\Response;

	use Psr\Http\Message\ServerRequestInterface;


	abstract class Module {

		/** @var Context */
		protected $context;

		/** @var ServerRequestInterface */
		protected $request;

		/** @var Controller[] */
		protected $instancedControllers = [];


		/**
		 * @param Context $context
		 * @param ServerRequestInterface $request
		 */
		public function onInitialize(Context $context, ServerRequestInterface $request): void {
			$this->context = $context;
			$this->request = $request;
		}

		public function onDestroy(): void {

			foreach ($this->instancedControllers as $controller){
				$controller->onDestroy();
			}

		}

		/**
		 * @param Context $context
		 * @param ServerRequestInterface $request
		 */
		public abstract function onCreate(Context $context, ServerRequestInterface $request): void;


		/**
		 * @param Controller|string $controller
		 * @param string[] $slugs = []
		 * @return ?Controller
		 */
		public function loadController($controller, array $slugs = []): ?Controller {

			if (!is_subclass_of($controller, Controller::class)){
				return null;
			}

			if (is_string($controller)){
				$controller = new $controller();
			}

			$this->instancedControllers[] = $controller;

			$controller->onInitialize($this->context, $this->request);
			$controller->setSlugs($slugs);

			$response = $controller->onCreate($controller->getContext(), $controller->getRequest());

			Response::send($response);

			return $controller;
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

	}
