<?php

	namespace Puzzle\Component\Hyperlink;

	use Psr\Link\EvolvableLinkInterface;


	class EvolvableLink extends Link implements EvolvableLinkInterface {

		/**
		 * @param string $href
		 * @return EvolvableLinkInterface
		 */
		public function withHref($href): EvolvableLinkInterface {
			$new = clone $this;
			$new->href = $href;
			return $new;
		}


		/**
		 * @param string $rel
		 * @return EvolvableLinkInterface
		 */
		public function withRel($rel): EvolvableLinkInterface {

			if (in_array($rel, $this->rels)){
				return $this;
			}

			$new = clone $this;
			$new->rels[] = $rel;
			return $new;
		}

		/**
		 * @param string $rel
		 * @return EvolvableLinkInterface
		 */
		public function withoutRel($rel): EvolvableLinkInterface {
			$new = clone $this;

			if (($key = array_search($rel, $new->rels)) !== false){
				unset($new->rels[$key]);
				$new->rels = array_values($new->rels);
			}

			return $new;
		}


		/**
		 * @param string $attribute
		 * @param mixed $value
		 * @return EvolvableLinkInterface
		 */
		public function withAttribute($attribute, $value): EvolvableLinkInterface {
			$new = clone $this;
			$new->attributes[$attribute] = $value;
			return $new;
		}

		/**
		 * @param string $attribute
		 * @return EvolvableLinkInterface
		 */
		public function withoutAttribute($attribute): EvolvableLinkInterface {

			if (!array_key_exists($attribute, $this->attributes)){
				return $this;
			}

			$new = clone $this;
			unset($new->attributes[$attribute]);
			return $new;
		}

	}
