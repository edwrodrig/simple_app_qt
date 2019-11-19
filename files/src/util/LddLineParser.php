<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\util;

class LddLineParser
{
    /**
     * Get library section from line
     *
     * lines can have a => or not
     * Examples:
     * libc.so.6 => /lib/x86_64-linux-gnu/libc.so.6 (0x00007f13c153a000)
     * /lib64/ld-linux-x86-64.so.2 (0x00007f13c2e9c000)
     * @param string $line
     * @return string
     */
    public function getLibrarySectionFromLine(string $line) : string {
        $section = explode("=>", $line);
        $librarySection = trim($section[1] ?? $section[0]);
        return $librarySection;
    }

    /**
     * Examples:
     * (0x00007ffcebe5f000)
     * /lib/x86_64-linux-gnu/libexpat.so.1 (0x00007f13bd7c5000)
     * @param $librarySection
     * @return string
     */
    public function getLibraryPathFromLibrarySection($librarySection) : ?string {
        $section = explode(" ", $librarySection);
        $libraryPath = trim($section[0] ?? "(");
        if ( strpos($libraryPath, "(") === 0 ) {
            return null;
        }
        return $libraryPath;
    }

    public function isQtLib(?string $libraryPath) : bool {
        if ( is_null($libraryPath) ) return false;
        if ( strpos($libraryPath, "/Qt/") !== FALSE ) return true;
        return false;
    }

    public function parse(string $line) : ?string {
        $section = $this->getLibrarySectionFromLine($line);
        $libraryPath = $this->getLibraryPathFromLibrarySection($section);
        if ( $this->isQtLib($libraryPath) ) return $libraryPath;
        return null;
    }

}