<?php

	namespace Puzzle\Component\Navigation;

	use Puzzle\Component\Link\Link;

	use Psr\Http\Message\RequestInterface;
	use Psr\Link\LinkInterface;


	class Route {

		/** @var string */
		protected const REGEX_SLUG = '/{[^}?]+(?:[?]){0,1}(?:[:][^}]+){0,1}}/';


		/** @var string */
		protected $expression;

		/** @var string */
		protected $name;

		/** @var Slug[] */
		protected $slugs = [];

		/** @var string */
		protected $regex;


		/**
		 * @param string $expression
		 * @param string $name = ''
		 */
		public function __construct(string $expression, string $name = '') {
			$this->expression = $expression;
			$this->name = $name;

			$this->parse();

		}


		protected function parse(): void {

			$this->extractSlugs($this->expression);
			$this->makeRegex();

		}


		/**
		 * @return Slugs[]
		 */
		public function getSlugs(): array {
			return $this->slugs;
		}

		/**
		 * @return string
		 */
		public function getName(): string {
			return $this->name;
		}


		/**
		 * @return bool
		 */
		public function canBeGenerated(): bool {
			return $this->name !== '';
		}


		/**
		 * @param RequestInterface $request
		 * @return bool
		 */
		public function correspond(RequestInterface $request): bool {

			$correspond = preg_match_all($this->regex, $request->getRequestTarget(), $matches, PREG_SET_ORDER);

			if (!$correspond){
				return false;
			}

			$arguments = array_slice($matches[0], 1);
			$index = 0;

			foreach ($arguments as $argument){
				$this->slugs[$index++]->setValue($argument);
			}

			return true;
		}


		/**
		 * @param string[] $arguments = []
		 * @throws \InvalidArgumentException Unable to replace the slug
		 * @return LinkInterface
		 */
		public function generate(array $arguments = []): LinkInterface {

			$uri = $this->expression;

			foreach ($this->slugs as $slug){

				$pos = strpos($uri, $slug->getDefinition());
				$replace = '';

				if (array_key_exists($slug->getName(), $arguments)){
					$replace = $arguments[$slug->getName()];
				}

				if (!preg_match('/^' . $slug->getAcceptedChars() . '$/', $replace) && !$slug->isOptional()){
					throw new \InvalidArgumentException('Unable to replace the slug');
				}


				$uri = substr_replace($uri, $replace, $pos, strlen($slug->getDefinition()));

			}

			return new Link(Link::base() . $uri);
		}


		/**
		 * @param string $source
		 * @return Slug[]
		 */
		protected function extractSlugs(string $source): void {

			preg_match_all(self::REGEX_SLUG, $source, $slugs, PREG_SET_ORDER);

			foreach ($slugs as $slug){
				$this->slugs[] = new Slug($slug[0]);
			}

		}

		protected function makeRegex(): void {

			$regex = $this->expression;

			foreach ($this->slugs as $slug){

				$pos = strpos($regex, $slug->getDefinition());

				$regex = substr_replace($regex, $slug->makeRegexGroup(), $pos, strlen($slug->getDefinition()));

			}

			$this->regex = '/^' . str_replace('/', '\/', $regex) . '$/';
		}

	}
