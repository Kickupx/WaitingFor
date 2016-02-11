<?php
/**
 * Created by PhpStorm.
 * User: Adminiator
 * Date: 2016-02-03
 * Time: 12:56
 */

namespace WaitingFor;


class RegexResults
{
    private $regexes;
    private $current_index = 0;

    function __construct(array $regexes, $str)
    {
        $priority = 9999999;
        $this->regexes = \Functional\map($regexes, function(Regex $r) use($str, &$priority)
        {
            return new RegexResult($priority--, $r->regex(), $r->delegate(), $str);
        });
    }

    function next() {
        $res = $this->find();
        if(!$res) return false;
        else {
            $dg = $res['delegate'];
            $dg($res);
            return true;
        }
    }

    function all() {
        while($this->next()) {}
    }

    private function find() {
        $results = \Functional\map($this->regexes, function(RegexResult $r) {
            return $r->next($this->current_index);
        });
        usort($results, function($a, $b) {
            if($a === null && $b === null) return 0;
            else if($a === null) return 1;
            else if($b === null) return -1;

            $aStart = $a[1];
            $aEnd = $a[1] + strlen($a[0]);

            $bStart = $b[1];
            $bEnd = $b[1] + strlen($b[0]);

            if($aStart <= $bStart && $aEnd > $bStart) {
                if ($a['priority'] < $b['priority']) return 1;
                else if ($a['priority'] > $b['priority']) return -1;
                else return 0;

                /**
                 * Bug 1 fixed:
                 *  bEnd and aStart does not overlap if they are equal. Because end of string is one more than actual
                 *  like normal array indexing.
                 */
            } else if($bStart <= $aStart && $bEnd > $aStart) {
                if ($a['priority'] < $b['priority']) return -1;
                else if ($a['priority'] > $b['priority']) return 1;
                else return 0;
            }
            if($aStart < $bStart) return -1;
            else if($aStart > $bStart) return 1;
            else return 0;
        });

        $res = $results[0];
        if($res) $this->current_index = $res[1] + strlen($res[0]);
        return $res;
    }
}