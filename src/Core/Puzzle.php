<?php

	namespace Puzzle\Core;

	use Puzzle\Component\Http\Request;

	use Puzzle\Component\Navigation\Router;


	class Puzzle {

		/** @var string[] */
		protected $modules = [];

		/** @var Context */
		protected $context;


		/**
		 * @param Module[]|string[] $modules = []
		 */
		public function __construct(array $modules = []) {
			$this->modules = $modules;
		}

		public function __destruct() {

			foreach ($this->modules as $module){
				$module->onDestroy();
			}

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


			$this->initModules();
		}

		protected function initModules(): void {

			foreach ($this->modules as $key => $module){

				if (!is_subclass_of($module, Module::class)){
					unset($this->modules[$key]);
					continue;
				}

				if (is_string($module)){
					$this->modules[$key] = new $module();
				}

				$this->modules[$key]->onInitialize($this->context, $this->context->getRequest());

			}
		}

		public function load(): void {

			foreach ($this->modules as $module){

				if (!is_subclass_of($module, Module::class)){
					continue;
				}

				$module->onCreate($module->getContext(), $module->getRequest());

			}

		}

		public function exec(): void {
			$this->context->get('router')->execute();
		}

	}
