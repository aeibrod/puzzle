<?php

	namespace Puzzle\Component\Http;

	use Puzzle\Core\Entity;

	use Psr\Http\Message\UriInterface;


	class Uri implements UriInterface, Entity {

		/** @var string[] */
		public const SCHEME_DEFAULT_PORT = [
			'file'   => null,
			'ftp'    => 21,
			'git'    => 9418,
			'gopher' => 70,
			'http'   => 80,
			'https'  => 443,
			'imap'   => 143,
			'irc'    => 194,
			'pop'    => 110,
			'ssh'    => 22,
			'svn'    => 3690,
			'telnet' => 23,
			'ws'     => 80,
			'wss'    => 443
		];


		/** @var string */
		protected $scheme = '';

		/** @var string */
		protected $userName = '';

		/** @var string */
		protected $userPass = '';

		/** @var string */
		protected $host = '';

		/** @var int */
		protected $port;

		/** @var string */
		protected $path = '';

		/** @var string */
		protected $query = '';

		/** @var string */
		protected $fragment = '';


		/**
		 * @return UriInterface
		 */
		public static function fromGlobals(): UriInterface {

			return (new Uri())

			->withScheme($_SERVER['REQUEST_SCHEME'])

			->withHost($_SERVER['SERVER_NAME'])

			->withPort((int) $_SERVER['SERVER_PORT'])

			->withPath(
				strpos($_SERVER['REQUEST_URI'], '?') !== false
				? substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'))
				: $_SERVER['REQUEST_URI']
			)

			->withQuery($_SERVER['QUERY_STRING']);

		}


		/**
		 * @return string
		 */
		public function getScheme(): string {
			return $this->scheme;
		}

		/**
		 * @return string
		 */
		public function getAuthority(): string {

			if ($this->host === ''){
				return '';
			}

			$authority = '';

			if ($this->getUserInfo() !== ''){
				$authority .= $this->getUserInfo() . '@';
			}

			$authority .= $this->host;

			if ($this->port !== null && $this->port !== self::SCHEME_DEFAULT_PORT[$this->scheme]){
				$authority .= ':' . $this->port;
			}

			return $authority;
		}

		/**
		 * @return string
		 */
		public function getUserInfo(): string {

			$result = $this->userName;

			if ($this->userPass !== ''){
				$result .= ':' . $this->userPass;
			}

			return $result;
		}

		/**
		 * @return string
		 */
		public function getHost(): string {
			return $this->host;
		}

		/**
		 * @return ?int
		 */
		public function getPort(): ?int {
			return $this->port;
		}

		/**
		 * @return string
		 */
		public function getPath(): string {
			if ($this->path === ''){
				return '/';
			}

			return $this->path;
		}

		/**
		 * @return string
		 */
		public function getQuery(): string {
			return $this->query;
		}

		/**
		 * @return string
		 */
		public function getFragment(): string {
			return $this->fragment;
		}


		/**
		 * @param string $scheme
		 * @return UriInterface
		 */
		public function withScheme($scheme): UriInterface {
			$new = clone $this;
			$new->scheme = strtolower($scheme);
			return $new;
		}

		/**
		 * @param string $user
		 * @param string $password = null
		 * @return UriInterface
		 */
		public function withUserInfo($user, $password = null): UriInterface {
			$new = clone $this;
			$new->userName = $user;
			$new->userPass = $password;
			return $new;
		}

		/**
		 * @param string $host
		 * @return UriInterface
		 */
		public function withHost($host): UriInterface {
			$new = clone $this;
			$new->host = $host;
			return $new;
		}

		/**
		 * @param int|null $port
		 * @return UriInterface
		 */
		public function withPort($port): UriInterface {
			$new = clone $this;
			$new->port = $port;
			return $new;
		}

		/**
		 * @param string $path
		 * @return UriInterface
		 */
		public function withPath($path): UriInterface {
			$new = clone $this;
			$new->path = $path;
			return $new;
		}

		/**
		 * @param string $query
		 * @return UriInterface
		 */
		public function withQuery($query): UriInterface {
			$new = clone $this;
			$new->query = $query;
			return $new;
		}

		/**
		 * @param string $fragment
		 * @return UriInterface
		 */
		public function withFragment($fragment): UriInterface {
			$new = clone $this;
			$new->fragment = $fragment;
			return $new;
		}


		/**
		 * @return string
		 */
		public function getId(): string {
			return UriInterface::class;
		}


		/**
		 * @return string
		 */
		public function __toString(): string {

			$uri = '';

			if ($this->getScheme() !== ''){
				$uri .= $this->getScheme() . ':';
			}

			if ($this->getAuthority() !== ''){
				$uri .= '//' . $this->getAuthority();
			}

			$uri .= $this->getPath();

			if ($this->getQuery() !== ''){
				$uri .= '?' . $this->getQuery();
			}

			if ($this->getFragment() !== ''){
				$uri .= '#' . $this->getFragment();
			}

			return $uri;
		}

	}
