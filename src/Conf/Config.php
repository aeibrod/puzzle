<?php

	namespace Puzzle\Conf;

	use Puzzle\Exception\ItemNotFoundException;


	class Config {

		/** @var Config */
		protected static $instance;


		/** @var string[] */
		protected $files = [];

		/** @var Parser[] */
		protected $parsers = [];

		/** @var mixed[] */
		protected $configuration = [];


		/**
		 * @param string|string[] $file = '' Absolute path to configuration file
		 * @throws \RuntimeException Config is a singleton class
		 */
		public function __construct($file = '') {

			if (Config::isInstanced()){
				throw new \RuntimeException('Config is a singleton class');
			}

			if (is_string($file)){
				$file = [$file];
			}


			// add default config
			array_unshift($file, dirname(dirname(__DIR__)) . '/config.json');

			$this->files = $file;

			$this->parsers = [
				new \Puzzle\Conf\Parser\JSONParser(),
				new \Puzzle\Conf\Parser\INIParser(),
				new \Puzzle\Conf\Parser\PHPParser()
			];

			$this->parse();

			self::$instance = $this;

		}


		/**
		 * @return Config
		 */
		public static function instance(): Config {

			if (!Config::isInstanced()){
				new Config();
			}

			return self::$instance;
		}

		/**
		 * @return bool
		 */
		public static function isInstanced(): bool {
			return self::$instance !== null;
		}


		protected function parse(): void {

			foreach ($this->files as $file){

				if (!file_exists($file)){
					continue;
				}

				[ 'extension' => $extension ] = pathinfo($file);

				foreach ($this->parsers as $parser){

					if (!in_array($extension, $parser->getExtensions())){
						continue;
					}

					$this->merge($parser->parse($file, file_get_contents($file)));

				}
			}
		}


		/**
		 * @param string $key
		 * @throws ItemNotFoundException No entry was found
		 * @return mixed|mixed[]
		 */
		public function get(string $key) {

			$keys = explode('.', strtolower($key));
			$result = $this->configuration;

			foreach ($keys as $key){

				if (array_key_exists($key, $result)){
					$result = $result[$key];
				}

				else {
					throw new ItemNotFoundException('No entry was found');
				}

			}

			return $result;
		}

		/**
		 * @param string $key
		 * @return bool
		 */
		public function has(string $key): bool {

			$keys = explode('.', strtolower($key));
			$result = $this->configuration;

			foreach ($keys as $key){

				if (array_key_exists($key, $result)){
					$result = $result[$key];
				}

				else {
					return false;
				}

			}

			return true;
		}

		/**
		 * @param mixed[] $config
		 */
		public function merge(array $config): void {

			$config = array_change_key_case($config, CASE_LOWER);

			$this->configuration = array_merge($this->configuration, $config);
		}

	}
