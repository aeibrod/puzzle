<?php

	namespace Puzzle\Conf\Parser;


	class JSONParser implements Parser {

		// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.Found

		/**
		 * @param string $file
		 * @param string $content
		 * @return mixed[]
		 */
		public function parse(string $file, string $content): array {

			$json =  json_decode($content, true);

			if ($json === null){
				return [];
			}

			return $json;
		}

		// phpcs:enable


		/**
		 * @return string[]
		 */
		public function getExtensions(): array {
			return [ 'json' ];
		}

	}
