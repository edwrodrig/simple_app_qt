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
    public function getLibraryPathFromLibrarySection($librarySection) : string {
        $section = explode(" ", $librarySection);
        $libraryPath = $section[0] ?? null;
        return $libraryPath;
    }

    public function parse(string $line) {


        /**
         * $tokens = explode("=>", $line);
        $final_token = trim($tokens[1] ?? $tokens[0]);
        $tokens = explode(" ", $final_token);
        $lib = $tokens[0] ?? null;
        if ( is_null($lib) ) continue;
        if ( !file_exists($lib) ) continue;
        if ( strpos($lib, "/Qt/") !== FALSE ) {

        printf("\t[%s]\n", $lib);
        copy($lib, $target_dir . DIRECTORY_SEPARATOR . basename($lib));
        }
         */
    }

}