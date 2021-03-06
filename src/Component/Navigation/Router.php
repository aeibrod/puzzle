<?php

	namespace Puzzle\Component\Navigation;

	use Puzzle\Core\Entity;

	use Puzzle\Component\Http\Message;

	use Psr\Http\Message\RequestInterface;
	use Psr\Link\LinkInterface;


	class Router implements Entity {

		/** @var RequestInterface */
		protected $request;

		/** @var mixed[] */
		protected $rules = [];

		/** @var Route[] */
		protected $routes = [];

		/** @var callable[] */
		protected $errors = [];

		/** @var Route */
		protected $who;

		/** @var bool */
		protected $hasCalled = false;


		/**
		 * @param RequestInterface $request
		 */
		public function setRequest(RequestInterface $request): void {
			$this->request = $request;
		}


		/**
		 * @param string|string[] $methods
		 * @param Route $route
		 * @param callable $callback
		 */
		public function match($methods, Route $route, callable $callback): void {

			if ($methods === '*'){
				$methods = Message::HTTP_METHODS;
			}

			if (is_string($methods)){
				$methods = [$methods];
			}


			foreach ($methods as $method){
				$this->rules[strtoupper($method)][] = [
					'route' => $route,
					'callback' => $callback
				];
			}

			if ($route->canBeGenerated()){
				$this->routes[strtolower($route->getName())] = $route;
			}

		}


		/**
		 * @param Route $route
		 * @param callable $callback
		 */
		public function any(Route $route, callable $callback): void {
			$this->match('*', $route, $callback);
		}

		/**
		 * @param Route $route
		 * @param callable $callback
		 */
		public function get(Route $route, callable $callback): void {
			$this->match('get', $route, $callback);
		}

		/**
		 * @param Route $route
		 * @param callable $callback
		 */
		public function head(Route $route, callable $callback): void {
			$this->match('head', $route, $callback);
		}

		/**
		 * @param Route $route
		 * @param callable $callback
		 */
		public function post(Route $route, callable $callback): void {
			$this->match('post', $route, $callback);
		}

		/**
		 * @param Route $route
		 * @param callable $callback
		 */
		public function put(Route $route, callable $callback): void {
			$this->match('put', $route, $callback);
		}

		/**
		 * @param Route $route
		 * @param callable $callback
		 */
		public function delete(Route $route, callable $callback): void {
			$this->match('delete', $route, $callback);
		}

		/**
		 * @param Route $route
		 * @param callable $callback
		 */
		public function connect(Route $route, callable $callback): void {
			$this->match('connect', $route, $callback);
		}

		/**
		 * @param Route $route
		 * @param callable $callback
		 */
		public function options(Route $route, callable $callback): void {
			$this->match('options', $route, $callback);
		}

		/**
		 * @param Route $route
		 * @param callable $callback
		 */
		public function trace(Route $route, callable $callback): void {
			$this->match('trace', $route, $callback);
		}

		/**
		 * @param Route $route
		 * @param callable $callback
		 */
		public function patch(Route $route, callable $callback): void {
			$this->match('patch', $route, $callback);
		}


		/**
		 * @param callable $callback
		 */
		public function set404(callable $callback): void {
			$this->errors['404'] = $callback;
		}


		/**
		 * @return ?Route
		 */
		public function who(): ?Route {
			return $this->who;
		}


		/**
		 * @param string $name
		 * @param string[] $slugs = []
		 * @throws \InvalidArguementException The route does not exist
		 * @return LinkInterface
		 */
		public function generate(string $name, array $slugs = []): LinkInterface {

			if (!array_key_exists(strtolower($name), $this->routes)){
				throw new \InvalidArgumentException('The route does not exist');
			}

			return $this->routes[strtolower($name)]->generate($slugs);

		}


		/**
		 * @param callable $callback
		 * @param Route $route = null
		 */
		protected function call(callable $callback, Route $route = null): void {

			$values = [];

			if ($route !== null){

				foreach ($route->getSlugs() as $slug){
					$values[] = $slug->getValue();
				}

				$this->who = $route;

			}

			$this->hasCalled = true;
			call_user_func_array($callback, array_merge([$values], $values));

		}


		public function execute(): void {

			$method = $this->request->getMethod();

			if (!array_key_exists($method, $this->rules)){
				return;
			}


			foreach ($this->rules[$method] as $rule){

				$route    = $rule['route'];
				$callback = $rule['callback'];

				if ($route->correspond($this->request)){
					$this->call($callback, $route);
					return;
				}
			}


			if (!$this->hasCalled && isset($this->errors['404'])){
				$this->call($this->errors['404']);
			}

		}


		/**
		 * @return string
		 */
		public function getId(): string {
			return 'router';
		}

	}
