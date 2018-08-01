<?php

	namespace Puzzle\Component\Http;

	use Puzzle\Core\Entity;

	use Psr\Http\Message\ResponseInterface;


	class Response extends Message implements ResponseInterface, Entity {

		/** @var int */
		protected $statusCode = 200;

		/** @var string */
		protected $reasonPhrase = 'OK';


		/**
		 * @param string $content = ''
		 */
		public function __construct(string $content = '') {
			$this->body = new Stream();

			$this->body->write($content);

		}


		/**
		 * @return int
		 */
		public function getStatusCode(): int {
			return $this->statusCode;
		}

		/**
		 * @return string
		 */
		public function getReasonPhrase(): string {
			return $this->reasonPhrase;
		}


		/**
		 * @param int $code
		 * @param string $reasonPhrase = ''
		 * @throws InvalidArgumentException Status code does not exist
		 * @return ResponseInterface
		 */
		public function withStatus($code, $reasonPhrase = ''): ResponseInterface {

			if (!array_key_exists($code, self::HTTP_STATUS)){
				throw new \InvalidArgumentException('Status code does not exist');
			}

			if ($reasonPhrase === ''){
				$reasonPhrase = self::HTTP_STATUS[$code];
			}


			$new = clone $this;

			$new->statusCode = $code;
			$new->reasonPhrase = $reasonPhrase;

			return $new;
		}


		/**
		 * @return string
		 */
		public function getId(): string {
			return ResponseInterface::class;
		}

	}
