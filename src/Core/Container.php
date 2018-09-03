<?php

	namespace Puzzle\Core;

	use Puzzle\Component\Link\Link;

	use Puzzle\Exception\ItemNotFoundException;

	use Psr\Container\ContainerInterface;
	use Psr\Http\Message\RequestInterface;
	use Psr\Link\LinkInterface;


	class Container implements ContainerInterface {

		/** @var mixed[] */
		protected $entities = [];

		/** @var RequestInterface */
		protected $request;


		/**
		 * @param string $id
		 * @param mixed $value
		 */
		public function set(string $id, $value): void {
			$this->entities[$id] = $value;

			if ($value instanceof RequestInterface){
				$this->request = $value;
			}
		}

		/**
		 * @param Entity $entity
		 */
		public function register(Entity $entity): void {
			$this->set($entity->getId(), $entity);
		}


		/**
		 * @param string $id
		 * @throws Psr\Container\NotFoundExceptionInterface No entry was found
		 * @return mixed
		 */
		public function get($id) {

			if (!$this->has($id)){
				throw new ItemNotFoundException('No entry was found');
			}

			return $this->entities[$id];
		}

		/**
		 * @return ?RequestInterface
		 */
		public function getRequest(): ?RequestInterface {
			return $this->request;
		}


		/**
		 * @return string[]
		 */
		public function keys(): array {
			return array_keys($this->entities);
		}


		/**
		 * @param mixed $id
		 * @return bool
		 */
		public function has($id): bool {
			return array_key_exists($id, $this->entities);
		}

	}
