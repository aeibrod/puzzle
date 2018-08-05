<?php

	namespace Puzzle\Component\Navigation;


	class Slug {

		/** @var string */
		protected const REGEX_DECODE_SLUG = '/{([^}?:]+)((?:[?]){0,1})(?:[:]([^}]+)){0,1}}/';


		/** @var string */
		protected $definition;

		/** @var string */
		protected $value = '';

		/** @var string */
		protected $name = '';

		/** @var string */
		protected $acceptedChars = '[a-zA-Z0-9-_]+';

		/** @var bool */
		protected $isOptional = false;


		/**
		 * @param string $definition
		 */
		public function __construct(string $definition) {
			$this->definition = $definition;

			$this->parse();

		}


		protected function parse(): void {

			preg_match(self::REGEX_DECODE_SLUG, $this->definition, $matches);

			$this->name = $matches[1];
			$this->isOptional = $matches[2] === '?';

			if (isset($matches[3])){
				$this->acceptedChars = $matches[3];
			}

		}


		/**
		 * @return string
		 */
		public function makeRegexGroup(): string {

			$group = $this->acceptedChars;

			if (!$this->isOptional){
				$group = '(' . $group . ')';
			}

			else {
				$group = '(?:(' . $group . ')*)';
			}

			return $group;
		}


		/**
		 * @param string $value
		 */
		public function setValue(string $value): void {
			$this->value = $value;
		}


		/**
		 * @return string
		 */
		public function getDefinition(): string {
			return $this->definition;
		}

		/**
		 * @return string
		 */
		public function getValue(): string {
			return $this->value;
		}

		/**
		 * @return string
		 */
		public function getName(): string {
			return $this->name;
		}

		/**
		 * @return string
		 */
		public function getAcceptedChars(): string {
			return $this->acceptedChars;
		}

		/**
		 * @return bool
		 */
		public function isOptional(): bool {
			return $this->isOptional;
		}

	}
