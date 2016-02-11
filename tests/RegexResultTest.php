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
    function test1() {
        $input = "token tok toker";
        $index = 0;
        $callCount = 0;
        $regexes = new Regexes();
        $regexes->add("/token/", function(array $capture) use(&$index, &$callCount) {
            $this->assertEquals("token", $capture[0]);
            $index = $capture[1];
            $callCount++;
        });

        $regexes->add("/t/", function(array $capture) use(&$index, &$callCount) {
            $this->assertTrue($capture[1] > $index);
            $callCount++;
        });

        $regexes->add("/ker/", function(array $capture) use(&$index, &$callCount) {
            $this->assertTrue(true);
            $callCount++;
        });

        $regexes->add("/toker/", function(array $capture) use(&$index, &$callCount) {
            $this->assertTrue(false);
            $callCount++;
        });

        $regexes->match($input)->all();
        $this->assertEquals(4, $callCount);
    }

    function test2() {
        $input =
            "
            ======
            ";
        $ran = false;

        $regexes = new Regexes();
        $regexes->add('/=====[=]*/', function(array $capture) use(&$ran) {
            $ran = true;
        });
        $regexes->match($input)->all();

        $this->assertTrue($ran);
    }

    function test3() {
        $input =
            "
            =====
            ";
        $ran = false;

        $regexes = new Regexes();
        $regexes->add('/=====[=]*/', function(array $capture) use(&$ran) {
            $ran = true;
        });
        $regexes->add("/\n\r|\r\n|\n|\r/", function(array $capture) {});
        $regexes->match($input)->all();
        $this->assertTrue($ran);
    }
}
