<?php

	namespace Puzzle\Tests\Component\Http;

	use Puzzle\Component\Http\Stream;


	class StreamTest extends \PHPUnit\Framework\TestCase {

		public function testReadableWritableSeekable(): void {

			$stream = new Stream();

			$this->assertTrue($stream->isReadable());
			$this->assertTrue($stream->isWritable());
			$this->assertTrue($stream->isSeekable());

			$stream->close();

			$this->assertFalse($stream->isReadable());
			$this->assertFalse($stream->isWritable());
			$this->assertFalse($stream->isSeekable());

		}


		public function testRead(): void {

			$stream = new Stream();

			$stream->write('there is someone here ?');

			$this->assertSame($stream->getContents(), '');

			$stream->rewind();

			$this->assertSame($stream->read(5), 'there');
			$this->assertSame($stream->read(4), ' is ');
			$this->assertSame($stream->getContents(), 'someone here ?');
			$this->assertSame($stream->__toString(), 'there is someone here ?');

		}

		public function testWrite(): void {

			$stream = new Stream();

			$stream->write('there ');
			$stream->write('is ');
			$stream->write('someone ');
			$stream->write('here ?');

			$stream->rewind();

			$this->assertSame($stream->getContents(), 'there is someone here ?');

		}

		public function testSeek(): void {

			$stream = new Stream();

			$stream->write('my name is alex');
			$stream->seek(11);
			$stream->write('adri');

			$stream->rewind();

			$this->assertSame($stream->getContents(), 'my name is adri');

		}


		public function testTell(): void {

			$stream = new Stream();

			$stream->write('a sentence');
			$this->assertSame($stream->tell(), 10);

			$stream->write('a big sentence');
			$this->assertSame($stream->tell(), 24);

		}

		public function testSize(): void {

			$stream = new Stream();

			$stream->write('allo');
			$this->assertSame($stream->getSize(), 4);

			$stream->write('there is someone here ?');
			$this->assertSame($stream->getSize(), 27);

		}

		public function testMetadata(): void {

			$stream = new Stream();

			$this->assertNotEmpty($stream->getMetadata());
			$this->assertArrayHasKey('uri', $stream->getMetadata());
			$this->assertSame($stream->getMetadata('uri'), 'php://temp');

		}



		public function testReadAfterClose(): void {

			$this->expectException(\RuntimeException::class);

			$stream = new Stream();

			$stream->close();
			$stream->read(5);

		}

		public function testGetContentsAfterClose(): void {

			$this->expectException(\RuntimeException::class);

			$stream = new Stream();

			$stream->close();
			$stream->getContents();

		}

		public function testToStringAfterClose(): void {

			$stream = new Stream();

			$stream->write('allo');
			$stream->close();

			$this->assertSame($stream->__toString(), '');

		}


		public function testWriteAfterClose(): void {

			$this->expectException(\RuntimeException::class);

			$stream = new Stream();

			$stream->close();
			$stream->write('allo');

		}

		public function testSeekAfterClose(): void {

			$this->expectException(\RuntimeException::class);

			$stream = new Stream();

			$stream->write('allo');
			$stream->close();

			$stream->seek(3);

		}


		public function testTellAfterClose(): void {

			$this->expectException(\RuntimeException::class);

			$stream = new Stream();

			$stream->write('allo');
			$stream->close();

			$stream->tell();

		}

		public function testSizeAfterClose(): void {

			$stream = new Stream();

			$stream->write('allo');
			$stream->close();

			$this->assertNull($stream->getSize());

		}

		public function testMetadataAfterClose(): void {

			$stream = new Stream();
			$stream->close();

			$this->assertNull($stream->getMetadata());

		}

	}
