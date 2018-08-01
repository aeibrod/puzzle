<?php

	namespace Puzzle\Core;

	use Puzzle\Component\Http\Request;
	use Puzzle\Component\Http\Router;


	class Puzzle {

		/** @var string[] */
		protected $modules = [];

		/** @var Context */
		protected $context;


		/**
		 * @param string[] $modules = []
		 */
		public function __construct(array $modules = []) {
			$this->modules = $modules;
		}


		public function run(): void {
			$this->init();
			$this->load();
			$this->exec();
		}


		public function init(): void {

			$this->context = new Context();

			$request = Request::fromGlobals();
			$router = new Router();

			$router->setRequest($request);

			$this->context->register($request);
			$this->context->register($router);

		}

		public function load(): void {

			foreach ($this->modules as $module){

				if (is_subclass_of($module, Module::class)){

					if (is_string($module)){
						$module = new $module();
					}

					$module->onCreate($this->context, $this->context->getRequest());

				}
			}
		}

		public function exec(): void {
			$this->context->get('router')->execute();
		}

	}
