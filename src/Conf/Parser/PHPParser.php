<?php

	namespace Puzzle\Conf\Parser;


	class PHPParser implements Parser {

		// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.Found

		/**
		 * @param string $file
		 * @param string $content
		 * @return mixed[]
		 */
		public function parse(string $file, string $content): array {

			ob_start();

			$php = require $file;

			ob_end_clean();


			return $php;
		}

		// phpcs:enable


		/**
		 * @return string[]
		 */
		public function getExtensions(): array {
			return [ 'php' ];
		}

	}
