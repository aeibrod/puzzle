<?php

	namespace Puzzle\Component\Link;

	use Psr\Link\LinkInterface;


	class Link implements LinkInterface {

		/** @var string */
		protected $href = '';

		/** @var string[] */
		protected $rels = [];

		/** @var mixed[] */
		protected $attributes = [];

		/** @var bool */
		protected $templated = false;


		/**
		 * @param string $href
		 * @param string[] $rels = []
		 * @param mixed[] $attributes = []
		 * @param bool $templated = false
		 */
		public function __construct(string $href, array $rels = [], array $attributes = [], bool $templated = false) {
			$this->href = $href;
			$this->rels = $rels;
			$this->attributes = $attributes;
			$this->templated = $templated;
		}


		/**
		 * @return LinkInterface
		 */
		public static function baseUrl(): LinkInterface {

			if (PHP_SAPI === 'cli'){
				return new Link('http://localhost/');
			}

			return new Link(
				$_SERVER['REQUEST_SCHEME'] . '://' .
				$_SERVER['SERVER_NAME'] . '/'
			);
		}


		/**
		 * @return string
		 */
		public function getHref(): string {
			return $this->href;
		}

		/**
		 * @return string[]
		 */
		public function getRels(): array {
			return $this->rels;
		}

		/**
		 * @return mixed[]
		 */
		public function getAttributes(): array {
			return $this->attributes;
		}


		/**
		 * @return bool
		 */
		public function isTemplated(): bool {
			return $this->templated;
		}

	}
