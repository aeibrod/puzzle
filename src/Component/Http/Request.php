<?php

	namespace Puzzle\Component\Http;

	use Puzzle\Core\Entity;

	use Psr\Http\Message\ServerRequestInterface;
	use Psr\Http\Message\UriInterface;


	class Request extends Message implements ServerRequestInterface, Entity {

		/** @var string */
		protected $requestTarget;

		/** @var string */
		protected $method = '';

		/** @var UriInterface */
		protected $uri;

		/** @var string[] */
		protected $serverParams = [];

		/** @var string[] */
		protected $cookiesParams = [];

		/** @var string[] */
		protected $queryParams = [];

		/** @var UploadedFileInterface[] */
		protected $uploadedFiles = [];

		/** @var null|array|object */
		protected $parsedBody;

		/** @var string[] */
		protected $attributes = [];


		/**
		 * @param UriInterface $uri
		 * @param string[] $serverParams = []
		 */
		public function __construct(UriInterface $uri, array $serverParams = []) {
			$this->uri = $uri;
			$this->serverParams = $serverParams;
		}


		/**
		 * @return ServerRequestInterface
		 */
		public static function fromGlobals(): ServerRequestInterface {

			return (new Request(Uri::fromGlobals(), $_SERVER))

			->initFromGlobals()

			->withMethod($_SERVER['REQUEST_METHOD'])

			->withCookieParams($_COOKIE)

			->withQueryParams($_GET);

		}


		/**
		 * @return string
		 */
		public function getRequestTarget(): string {

			if ($this->requestTarget !== null){
				return $this->requestTarget;
			}

			if ($this->uri === null){
				return '/';
			}


			$target = $this->uri->getPath();

			if ($target === ''){
				$target = '/';
			}

			if ($this->uri->getQuery() !== ''){
				$target .= '?' . $this->uri->getQuery();
			}

			return $target;
		}

		/**
		 * @param string $requestTarget
		 * @return ServerRequestInterface
		 */
		public function withRequestTarget($requestTarget): ServerRequestInterface {
			$new = clone $this;
			$new->requestTarget = $requestTarget;
			return $new;
		}


		/**
		 * @return string
		 */
		public function getMethod(): string {
			return $this->method;
		}

		/**
		 * @param string $method
		 * @throws InvalidArgumentException Invalid HTTP method
		 * @return ServerRequestInterface
		 */
		public function withMethod($method): ServerRequestInterface {

			if (!in_array(strtoupper($method), self::HTTP_METHOD)){
				throw new \InvalidArgumentException('Invalid HTTP method');
			}

			$new = clone $this;
			$new->method = strtoupper($method);
			return $new;
		}


		/**
		 * @return ?UriInterface
		 */
		public function getUri(): ?UriInterface {
			return $this->uri;
		}

		/**
		 * @param UriInterface $uri
		 * @param bool $preserveHost = false
		 * @return ServerRequestInterface
		 */
		public function withUri(UriInterface $uri, $preserveHost = false): ServerRequestInterface {
			$new = clone $this;
			$new->uri = $uri;
			return $new;
		}


		/**
		 * @return string[]
		 */
		public function getServerParams(): array {
			return $this->serverParams;
		}

		/**
		 * @return string[]
		 */
		public function getCookieParams(): array {
			return $this->cookiesParams;
		}

		/**
		 * @return string[]
		 */
		public function getQueryParams(): array {
			return $this->queryParams;
		}


		/**
		 * @param string[] $cookies
		 * @return ServerRequestInterface
		 */
		public function withCookieParams(array $cookies): ServerRequestInterface {
			$new = clone $this;
			$new->cookiesParams = $cookies;
			return $new;
		}

		/**
		 * @param string[] $query
		 * @return ServerRequestInterface
		 */
		public function withQueryParams(array $query): ServerRequestInterface {
			$new = clone $this;
			$new->queryParams = $query;
			return $new;
		}


		/**
		 * @return UploadedFileInterface[]
		 */
		public function getUploadedFiles(): array {
			return $this->uploadedFiles;
		}

		/**
		 * @param string[] $uploadedFiles
		 * @return ServerRequestInterface
		 */
		public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface {
			$new = clone $this;
			$new->uploadedFiles = $uploadedFiles;
			return $new;
		}


		/**
		 * @return null|array|object
		 */
		public function getParsedBody() {
			return $this->parsedBody;
		}

		/**
		 * @param null|array|object $data
		 * @return ServerRequestInterface
		 */
		public function withParsedBody($data): ServerRequestInterface {
			$new = clone $this;
			$new->parsedBody = $data;
			return $new;
		}


		/**
		 * @return string[]
		 */
		public function getAttributes(): array {
			return $this->attributes;
		}

		/**
		 * @param string $name
		 * @param mixed $default = null
		 * @return mixed
		 */
		public function getAttribute($name, $default = null) {

			if (!array_key_exists($name, $this->attributes)){
				return $default;
			}

			return $this->attributes[$name];
		}

		/**
		 * @param string $name
		 * @param mixed $value
		 * @return ServerRequestInterface
		 */
		public function withAttribute($name, $value): ServerRequestInterface {
			$new = clone $this;
			$new->attributes[$name] = $value;
			return $new;
		}

		/**
		 * @param string $name
		 * @return ServerRequestInterface
		 */
		public function withoutAttribute($name): ServerRequestInterface {

			if (!array_key_exists($name, $this->attributes)){
				return $this;
			}

			$new = clone $this;
			unset($new->attributes[$name]);
			return $new;
		}


		/**
		 * @return string
		 */
		public function getId(): string {
			return ServerRequestInterface::class;
		}

	}
