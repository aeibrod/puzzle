<?php

	namespace Puzzle\Component\Http;

	use Psr\Http\Message\MessageInterface;
	use Psr\Http\Message\StreamInterface;


	abstract class Message implements MessageInterface {

		/** @var string[] */
		public const HTTP_METHODS = [ 'GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH' ];

		/** @var string[] */
		public const HTTP_VERSIONS = [ '0.9', '1.0', '1.1', '2' ];

		/** @var string[] */
		public const HTTP_STATUS = [
			100 => 'Continue',
			101 => 'Switching Protocol',
			102 => 'Processing',
			103 => 'Early Hints',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			207 => 'Multi-Status',
			208 => 'Already Reported',
			226 => 'IM Used',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => 'Switch Proxy',
			307 => 'Temporary Redirect',
			308 => 'Permanent Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentification Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Payload Too Large²',
			414 => 'URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Range Not Satisfiable',
			417 => 'Expectation Failed',
			421 => 'Misdirect Request',
			422 => 'Unprocessable Entity',
			423 => 'Locked',
			424 => 'Failed Dependency',
			425 => 'Too Early',
			426 => 'Upgrade Required',
			428 => 'Precondition Required',
			429 => 'Too Many Requests',
			431 => 'Request Header Fields Too Large',
			451 => 'Unvailable For Legal Reasons',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unvailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			506 => 'Variant Also Negotiates',
			507 => 'Insufficient Storage',
			508 => 'Loop Detect',
			510 => 'Not Extend',
			511 => 'Network Authentification Required',
		];


		/** @var string */
		protected $protocolVersion = '1.1';

		/** @var string[][] */
		protected $headers = [];

		/** @var string[] */
		protected $headersName = [];

		/** @var StreamInterface */
		protected $body;


		/**
		 * @return MessageInterface
		 */
		protected function initFromGlobals(): MessageInterface {

			$this->protocolVersion = substr($_SERVER['SERVER_PROTOCOL'], strpos($_SERVER['SERVER_PROTOCOL'], '/') + 1);

			foreach ($_SERVER as $name => $value){
				if (strpos(strtolower($name), 'http_') === 0){

					$name  = substr($name, strlen('http_'));
					$value = explode(',', $value);

					$this->headers[$name] = $value;
					$this->headersName[strtolower($name)] = $name;

				}
			}

			return $this;
		}


		/**
		 * @return string
		 */
		public function getProtocolVersion(): string {
			return $this->protocolVersion;
		}

		/**
		 * @param string $version
		 * @return MessageInterface
		 */
		public function withProtocolVersion($version): MessageInterface {
			$new = clone $this;
			$new->protocolVersion = $version;
			return $new;
		}


		/**
		 * @return string[][]
		 */
		public function getHeaders(): array {
			return $this->headers;
		}

		/**
		 * @param string $name
		 * @return string[]
		 */
		public function getHeader($name): array {

			if (!$this->hasHeader($name)){
				return [];
			}

			return $this->headers[ $this->headersName[strtolower($name)] ];
		}

		/**
		 * @param string $name
		 * @return string
		 */
		public function getHeaderLine($name): string {
			return implode(', ', $this->getHeader($name));
		}

		/**
		 * @param string $name
		 * @return bool
		 */
		public function hasHeader($name): bool {
			return array_key_exists(strtolower($name), $this->headersName);
		}

		/**
		 * @param string $name
		 * @param string|string[] $value
		 * @return MessageInterface
		 */
		public function withHeader($name, $value): MessageInterface {

			if (!is_array($value)){
				$value = [$value];
			}

			$new = clone $this;

			if ($new->hasHeader($name)){
				$new->withoutHeader($name);
			}

			$new->headers[$name] = $value;
			$new->headersName[strtolower($name)] = $name;

			return $new;
		}

		/**
		 * @param string $name
		 * @param string|string[] $value
		 * @return MessageInterface
		 */
		public function withAddedHeader($name, $value): MessageInterface {

			if (!is_array($value)){
				$value = [$value];
			}

			$new = clone $this;

			if (!$new->hasHeader($name)){
				$new->headers[$name] = [];
				$new->headersName[strtolower($name)] = $name;
			}

			$new->headers[$name] = array_merge($new->headers[$name], $value);

			return $new;
		}

		/**
		 * @param string $name
		 * @return MessageInterface
		 */
		public function withoutHeader($name): MessageInterface {

			if (!$this->hasHeader($name)){
				return $this;
			}

			$new = clone $this;

			unset($new->headers[$new->headersName[strtolower($name)]]);
			unset($new->headersName[strtolower($name)]);

			return $new;
		}


		/**
		 * @return ?StreamInterface
		 */
		public function getBody(): ?StreamInterface {
			return $this->body;
		}

		/**
		 * @param StreamInterface $body
		 * @return MessageInterface
		 */
		public function withBody(StreamInterface $body): MessageInterface {
			$new = clone $this;
			$new->body = $body;
			return $new;
		}

	}
