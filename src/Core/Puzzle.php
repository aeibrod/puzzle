<?php

	namespace Puzzle\Core;

	use Puzzle\Component\Http\Request;

	use Puzzle\Component\Navigation\Router;


	class Puzzle {

		/** @var string[] */
		protected $modules = [];

		/** @var Container */
		protected $container;


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


		/**
		 * @throws \InvalidArgumentException Could not initialize a class that not inherit from Module
		 */
		public function run(): void {
			$this->init();
			$this->load();
			$this->exec();
		}


		/**
		 * @throws \InvalidArgumentException Could not initialize a class that not inherit from Module
		 */
		public function init(): void {

			$this->container = new Container();

			$request = Request::fromGlobals();
			$router = new Router();

			$router->setRequest($request);

			$this->container->register($request);
			$this->container->register($router);


			$this->initModules();
		}

		/**
		 * @throws \InvalidArgumentException Could not initialize a class that not inherit from Module
		 */
		protected function initModules(): void {

			foreach ($this->modules as $key => $module){

				if (!is_subclass_of($module, Module::class)){
					throw new \InvalidArgumentException('Could not initialize a class that not inherit from Module');
				}

				if (is_string($module)){
					$this->modules[$key] = new $module();
				}

				$this->modules[$key]->onInitialize($this->container, $this->container->getRequest());

			}
		}

		/**
		 * @throws \InvalidArgumentException Could not load a class that not inherit from Module
		 */
		public function load(): void {

			foreach ($this->modules as $module){

				if (!is_subclass_of($module, Module::class)){
					throw new \InvalidArgumentException('Could not load a class that not inherit from Module');
				}

				$module->onCreate($module->getContainer(), $module->getRequest());

			}

		}

		public function exec(): void {
			$this->container->get('router')->execute();
		}

	}
