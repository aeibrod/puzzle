<?php

	namespace Puzzle\Component\Http;

	use Psr\Http\Message\StreamInterface;


	class Stream implements StreamInterface {

		// phpcs:disable Generic.WhiteSpace.DisallowSpaceIndent.SpacesUsed

		/** @var string[] */
		public const READ_MODES = [
			'r', 'rw', 'r+', 'rb', 'r+b', 'rt', 'r+t',
			     'wr', 'w+',       'w+b',       'w+t',
			           'x+',       'x+b',       'x+t',
			           'c+',       'c+b',       'c+t',
			           'a+'
		];

		/** @var string[] */
		public const WRITE_MODES = [
			'w', 'wr', 'w+', 'wb', 'w+b', 'wt', 'w+t',
			     'rw', 'r+',       'r+b',       'r+t',
			           'x+',       'w+b',       'w+t',
			           'c+',       'c+b',       'c+t',
			           'a+'
		];

		// phpcs:enable


		/** @var resource */
		protected $resource;

		/** @var string */
		protected $uri;

		/** @var int */
		protected $size;

		/** @var bool */
		protected $readable = true;

		/** @var bool */
		protected $writable = true;

		/** @var bool */
		protected $seekable = true;


		/**
		 * @param resource $resource = null
		 * @throws \InvalidArgumentException Stream must be instantiated with a valid resource
		 */
		public function __construct($resource = null) {

			if ($resource === null){
				$resource = fopen('php://temp', 'r+');
			}

			if (!is_resource($resource)){
				throw new \InvalidArgumentException('Stream must be instantiated with a valid resource');
			}


			$meta = stream_get_meta_data($resource);
			$mode = $meta['mode'];

			$this->readable = in_array($mode, self::READ_MODES);
			$this->writable = in_array($mode, self::WRITE_MODES);
			$this->seekable = $meta['seekable'];

			$this->uri = $meta['uri'];

			$this->resource = $resource;
		}

		public function __destruct() {
			$this->close();
		}


		/**
		 * @param string $filename
		 * @param string $mode = 'r+'
		 * @throws \InvalidArgumentException Stream must be instanciated with a valid resource
		 * @return StreamInterface
		 */
		public static function fromFile(string $filename, string $mode = 'r+'): StreamInterface {
			return new Stream(fopen($filename, $mode));
		}


		public function close(): void {

			if (!isset($this->resource)){
				return;
			}

			fclose($this->resource);

			$this->detach();
		}

		/**
		 * @return ?resource
		 */
		public function detach() {

			if (!isset($this->resource)){
				return null;
			}


			$result = $this->resource;

			unset($this->resource);

			$this->readable = $this->writable = $this->seekable = false;
			$this->size = $this->uri = null;

			return $result;
		}


		/**
		 * @return ?int
		 */
		public function getSize(): ?int {

			if (!isset($this->resource)){
				return null;
			}

			if ($this->size !== null){
				return $this->size;
			}


			clearstatcache(true, $this->uri);

			$stat = fstat($this->resource);

			if (!isset($stat['size'])){
				return null;
			}

			$this->size = $stat['size'];

			return $this->size;
		}


		/**
		 * @throws \RuntimeException Stream is detached
		 * @throws \RuntimeException Unable to get the position of the pointer
		 * @return int
		 */
		public function tell(): int {

			if (!isset($this->resource)){
				throw new \RuntimeException('Stream is detached');
			}

			$result = ftell($this->resource);

			if ($result === false){
				throw new \RuntimeException('Unable to get the position of the pointer');
			}

			return $result;
		}

		/**
		 * @return bool
		 */
		public function eof(): bool {

			if (!isset($this->resource)){
				return false;
			}

			return feof($this->resource);
		}


		/**
		 * @param int $length
		 * @throws \RuntimeException Stream is detached
		 * @throws \RuntimeException Stream is not readable
		 * @return string
		 */
		public function read($length): string {

			if (!isset($this->resource)){
				throw new \RuntimeException('Stream is detached');
			}

			if (!$this->readable){
				throw new \RuntimeException('Stream is not readable');
			}


			$result = fread($this->resource, $length);

			if ($result === false){
				throw new \RuntimeException('Unable to read the stream');
			}

			return $result;
		}

		/**
		 * @throws \RuntimeException Stream is detached
		 * @throws \RuntimeException Stream is not readable
		 * @return string
		 */
		public function getContents(): string {

			if (!isset($this->resource)){
				throw new \RuntimeException('Stream is detached');
			}

			if (!$this->readable){
				throw new \RuntimeException('Stream is not readable');
			}


			$result = stream_get_contents($this->resource);

			if ($result === false){
				throw new \RuntimeException('Unable to read the stream');
			}

			return $result;
		}


		/**
		 * @param string $content
		 * @throws \RuntimeException Stream is detached
		 * @throws \RuntimeException Stream is not writable
		 * @return int
		 */
		public function write($content): int {

			if (!isset($this->resource)){
				throw new \RuntimeException('Stream is detached');
			}

			if (!$this->writable){
				throw new \RuntimeException('Stream is not writable');
			}


			$result = fwrite($this->resource, $content);
			$this->size = null;

			if ($result === false){
				throw new \RuntimeException('Unable to write the stream');
			}

			return $result;
		}


		/**
		 * @param int $offset
		 * @param int $whence = SEEK_SET
		 * @throws \RuntimeException Stream is detached
		 * @throws \RuntimeException Stream is not seekable
		 */
		public function seek($offset, $whence = SEEK_SET): void {

			if (!isset($this->resource)){
				throw new \RuntimeException('Stream is detached');
			}

			if (!$this->seekable){
				throw new \RuntimeException('Stream is not seekable');
			}


			$result = fseek($this->resource, $offset, $whence);

			if ($result === -1){
				throw new \RuntimeException('Unable to seek the stream');
			}
		}


		/**
		 * @throws \RuntimeException Stream is detached
		 * @throws \RuntimeException Stream is not seekable
		 */
		public function rewind(): void {
			$this->seek(0);
		}


		/**
		 * @return bool
		 */
		public function isReadable(): bool {

			if (!isset($this->resource)){
				return false;
			}

			return $this->readable;
		}

		/**
		 * @return bool
		 */
		public function isWritable(): bool {

			if (!isset($this->resource)){
				return false;
			}

			return $this->writable;
		}

		/**
		 * @return bool
		 */
		public function isSeekable(): bool {

			if (!isset($this->resource)){
				return false;
			}

			return $this->seekable;
		}


		/**
		 * @param string $key = null
		 * @return ?mixed
		 */
		public function getMetadata($key = null) {

			if (!isset($this->resource)){
				return null;
			}

			$metadata = stream_get_meta_data($this->resource);

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
