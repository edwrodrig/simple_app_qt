<?php
declare(strict_types=1);


namespace test\edwrodrig\qt_app_builder\util;

use edwrodrig\qt_app_builder\util\LddLineParser;
use PHPUnit\Framework\TestCase;

class LddLineParserTest extends TestCase
{
    /**
     * @testWith  ["/lib/x86_64-linux-gnu/libc.so.6 (0x00007f13c153a000)", "libc.so.6 => /lib/x86_64-linux-gnu/libc.so.6 (0x00007f13c153a000)"]
     *             ["/lib64/ld-linux-x86-64.so.2 (0x00007f13c2e9c000)", "/lib64/ld-linux-x86-64.so.2 (0x00007f13c2e9c000)"]
     */
    public function testGetLibrarySectionFromLine($expected, $line) {
        $parser = new LddLineParser();
        $actual = $parser->getLibrarySectionFromLine($line);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @testWith [null, "(0x00007ffcebe5f000)"]
     *           ["/lib/x86_64-linux-gnu/libexpat.so.1", "/lib/x86_64-linux-gnu/libexpat.so.1 (0x00007f13bd7c5000)"]
     * @param $expected
     * @param $line
     */
    public function testGetLibraryPathFromLibrarySection($expected, $line) {
        $parser = new LddLineParser();
        $actual = $parser->getLibraryPathFromLibrarySection($line);
        $this->assertEquals($expected, $actual);
    }

}
