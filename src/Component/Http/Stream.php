<?php

	namespace Puzzle\Component\Http;

	use Psr\Http\Message\StreamInterface;


	class Stream implements StreamInterface {


		/** @var resource */
		protected $stream;

		/** @var bool */
		protected $readable = true;

		/** @var bool */
		protected $writable = true;

		/** @var bool */
		protected $seekable = true;


		public function __construct() {
			$this->stream = fopen('php://temp', 'r+');
		}

		public function __destruct() {
			$this->close();
		}


		public function close(): void {

			if (!isset($this->stream)){
				return;
			}

			fclose($this->stream);

			$this->detach();
		}

		/**
		 * @return ?resource
		 */
		public function detach() {

			if ($this->stream === null){
				return null;
			}


			$result = $this->stream;

			unset($this->stream);
			$this->readable = $this->writable = $this->seekable = false;

			return $result;
		}


		/**
		 * @return ?int
		 */
		public function getSize(): ?int {
			return null;
		}


		/**
		 * @throws RuntimeException
		 * @return int
		 */
		public function tell(): int {

			if (!isset($this->stream)){
				throw new \RuntimeException('Stream is detached');
			}

			$result = ftell($this->stream);

			if ($result === false){
				throw new \RuntimeException('Unable to get the position of the pointer');
			}

			return $result;
		}

		/**
		 * @return bool
		 */
		public function eof(): bool {

			if (!isset($this->stream)){
				return false;
			}

			return feof($this->stream);
		}


		/**
		 * @param int $length
		 * @throws RuntimeException
		 * @return string
		 */
		public function read($length): string {

			if (!isset($this->stream)){
				throw new \RuntimeException('Stream is detached');
			}

			if (!$this->readable){
				throw new \RuntimeException('Stream is not readable');
			}


			$result = fread($this->stream, $length);

			if ($result === false){
				throw new \RuntimeException('Unable to read the stream');
			}

			return $result;
		}

		/**
		 * @throws RuntimeException
		 * @return string
		 */
		public function getContents(): string {

			if (!isset($this->stream)){
				throw new \RuntimeException('Stream is detached');
			}

			if (!$this->readable){
				throw new \RuntimeException('Stream is not readable');
			}


			$result = stream_get_contents($this->stream);

			if ($result === false){
				throw new \RuntimeException('Unable to read the stream');
			}

			return $result;
		}


		/**
		 * @param string $content
		 * @throws RuntimeException
		 * @return int
		 */
		public function write($content): int {

			if (!isset($this->stream)){
				throw new \RuntimeException('Stream is detached');
			}

			if (!$this->writable){
				throw new \RuntimeException('Stream is not writable');
			}


			$result = fwrite($this->stream, $content);

			if ($result === false){
				throw new \RuntimeException('Unable to write the stream');
			}

			return $result;
		}


		/**
		 * @param int $offset
		 * @param int $whence = SEEK_SET
		 * @throws RuntimeException
		 */
		public function seek($offset, $whence = SEEK_SET): void {

			if (!isset($this->stream)){
				throw new \RuntimeException('Stream is detached');
			}

			if (!$this->seekable){
				throw new \RuntimeException('Stream is not seekable');
			}


			$result = fseek($this->stream, $offset, $whence);

			if ($result === -1){
				throw new \RuntimeException('Unable to seek the stream');
			}
		}


		/**
		 * @throws RuntimeException
		 */
		public function rewind(): void {
			$this->seek(0);
		}


		/**
		 * @return bool
		 */
		public function isReadable(): bool {
			return $this->writable;
		}

		/**
		 * @return bool
		 */
		public function isWritable(): bool {
			return $this->writable;
		}

		/**
		 * @return bool
		 */
		public function isSeekable(): bool {
			return $this->seekable;
		}


		/**
		 * @param string $key = null
		 * @throws RuntimeException
		 * @return ?mixed
		 */
		public function getMetadata($key = null) {

			if (!isset($this->stream)){
				return null;
			}

			$metadata = stream_get_meta_data($this->stream);

			if ($key === null){
				return $metadata;
			}

			if (array_key_exists($key, $metadata)){
				return $metadata[$key];
			}

			return null;
		}


		/**
		 * @return string
		 */
		public function __toString(): string {

			try {

				$this->rewind();
				return $this->getContents();

			} catch (\Exception $e){
				return '';
			}

		}

	}
