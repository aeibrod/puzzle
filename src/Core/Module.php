<?php

	namespace Puzzle\Core;

	use Psr\Http\Message\ServerRequestInterface;


	abstract class Module {

		/** @var Context */
		protected $context;

		/** @var ServerRequestInterface */
		protected $request;


		/**
		 * @param Context $context
		 * @param ServerRequestInterface $request
		 */
		public function onCreate(Context $context, ServerRequestInterface $request): void {
			$this->context = $context;
			$this->request = $request;
		}


		/**
		 * @param Controller|string $controller
		 * @param string[] $matches = []
		 * @return ?Controller
		 */
		public function loadController($controller, array $matches = []): ?Controller {

			if (!is_subclass_of($controller, Controller::class)){
				return null;
			}

			if (is_string($controller)){
				$controller = new $controller();
			}


			$controller->setMatches($matches);
			$response = $controller->onCreate($this->context, $this->request);

			header('HTTP/' . $response->getProtocolVersion() . ' ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());

			foreach ($response->getHeaders() as $header => $value){
				header($header . ': ' . implode(', ', $value));
			}

			echo $response->getBody()->__toString();

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
