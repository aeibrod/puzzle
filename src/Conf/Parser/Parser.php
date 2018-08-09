<?php

	namespace Puzzle\Conf\Parser;


	interface Parser {

		/**
		 * @param string $file Absolute path
		 * @param string $content
		 * @return mixed[]
		 */
		public function parse(string $file, string $content): array;

		/**
		 * @return string[]
		 */
		public function getExtensions(): array;

	}
