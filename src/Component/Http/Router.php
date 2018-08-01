<?php

	namespace Puzzle\Component\Http;

	use Puzzle\Core\Entity;

	use Psr\Http\Message\RequestInterface;


	class Router implements Entity {

		/** @var RequestInterface */
		protected $request;

		/** mixed[][] */
		protected $rules = [];

		/** callable[] */
		protected $errors = [];

		/** bool */
		protected $hasCalled = false;


		/**
		 * @param RequestInterface $request
		 */
		public function setRequest(RequestInterface $request): void {
			$this->request = $request;
		}


		/**
		 * @param string|string[] $methods
		 * @param string $regex
		 * @param callable $callback
		 */
		public function match($methods, string $regex, callable $callback): void {

			if ($methods === '*'){
				$methods = implode('|', Message::HTTP_METHOD);
			}

			if (is_array($methods)){
				$methods = implode('|', $methods);
			}


			foreach (explode('|', $methods) as $method){
				$this->rules[strtoupper($method)][] = [
					'regex' => $regex,
					'callback' => $callback
				];
			}

		}

		/**
		 * @param string $regex
		 * @param callable $callback
		 */
		public function all(string $regex, callable $callback): void {
			$this->match('*', $regex, $callback);
		}


		/**
		 * @param string $regex
		 * @param callable $callback
		 */
		public function get(string $regex, callable $callback): void {
			$this->match('get', $regex, $callback);
		}

		/**
		 * @param string $regex
		 * @param callable $callback
		 */
		public function head(string $regex, callable $callback): void {
			$this->match('head', $regex, $callback);
		}

		/**
		 * @param string $regex
		 * @param callable $callback
		 */
		public function post(string $regex, callable $callback): void {
			$this->match('post', $regex, $callback);
		}

		/**
		 * @param string $regex
		 * @param callable $callback
		 */
		public function put(string $regex, callable $callback): void {
			$this->match('put', $regex, $callback);
		}

		/**
		 * @param string $regex
		 * @param callable $callback
		 */
		public function delete(string $regex, callable $callback): void {
			$this->match('delete', $regex, $callback);
		}

		/**
		 * @param string $regex
		 * @param callable $callback
		 */
		public function connect(string $regex, callable $callback): void {
			$this->match('connect', $regex, $callback);
		}

		/**
		 * @param string $regex
		 * @param callable $callback
		 */
		public function options(string $regex, callable $callback): void {
			$this->match('options', $regex, $callback);
		}

		/**
		 * @param string $regex
		 * @param callable $callback
		 */
		public function trace(string $regex, callable $callback): void {
			$this->match('trace', $regex, $callback);
		}

		/**
		 * @param string $regex
		 * @param callable $callback
		 */
		public function patch(string $regex, callable $callback): void {
			$this->match('patch', $regex, $callback);
		}


		/**
		 * @param string|string[] $method
		 * @param callable $callback
		 */
		public function isIndex($method, callable $callback): void {
			foreach (str_split($this->request->getUri()->getPath()) as $char){
				if ($char !== '/'){
					return;
				}
			}

			$this->match($method, '.*', $callback);
		}


		/**
		 * @param callable $callback
		 */
		public function set404(callable $callback): void {
			$this->errors['404'] = $callback;
		}


		/**
		 * @param callable $callback
		 * @param string[] $matches = []
		 */
		protected function call(callable $callback, array $matches = []): void {

			$this->hasCalled = true;
			$callback($matches);

		}

		public function execute(): void {

			$method = $this->request->getMethod();
			$target = $this->request->getRequestTarget();

			$rules = [];

			if (array_key_exists($method, $this->rules)){
				$rules = $this->rules[$method];
			}


			foreach ($rules as $rule){

				if (preg_match('/'.str_replace('/', '\/', $rule['regex']).'/', $target, $matches)){
					$this->call($rule['callback'], $matches);
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
