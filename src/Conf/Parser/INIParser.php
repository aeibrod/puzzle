<?php

	namespace Puzzle\Conf\Parser;


	class INIParser implements Parser {

		// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.Found

		/**
		 * @param string $file
		 * @param string $content
		 * @return mixed[]
		 */
		public function parse(string $file, string $content): array {

			$ini = parse_ini_file($file, true);

			if ($ini === false){
				return [];
			}

			return $ini;
		}

		// phpcs:enable


		/**
		 * @return string[]
		 */
		public function getExtensions(): array {
			return [ 'ini' ];
		}

	}
