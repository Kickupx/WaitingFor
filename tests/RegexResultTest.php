<?php
use WaitingFor\Regexes;

/**
 * Created by PhpStorm.
 * User: Adminiator
 * Date: 2016-02-02
 * Time: 16:30
 */
class RegexResultTest extends PHPUnit_Framework_TestCase
{
    function testOne() {
        $input = "token tok toker";
        $index = 0;
        $regexes = new Regexes();
        $regexes->add("/token/", function(array $capture) use(&$index) {
            $this->assert($capture[0] == "token");
            $index = $capture[1];
        });

        $regexes->add("/t/", function(array $capture) use(&$index) {
            $this->assert($capture[1] > $index);
        });

        $regexes->add("/ker/", function(array $capture) use(&$index) {
            $this->assert(true);
        });

        $regexes->add("/toker/", function(array $capture) use(&$index) {
            $this->assert(false);
        });

        $regexes->match($input)->all();
    }
}
