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
            return new RegexResult($priority--, $r->delegate(), $r->regex(), $str);
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
        $priority_order = usort($results, function($a, $b) {
            if($a === null && $b === null) return 0;
            else if($a === null) return -1;
            else return 1;

            $aStart = $a[1];
            $aEnd = $a[1] + strlen($a[0]);

            $bStart = $b[1];
            $bEnd = $b[1] + strlen($b[0]);

            if($aStart <= $bStart && $aEnd >= $bStart)
                if($a['priority'] < $b['priority']) return -1;
                else if($a['priority'] > $b['priority']) return 1;
                else return 0;
            if($aStart < $bStart) return -1;
            else if($aStart > $bStart) return 1;
            else return 0;
        });

        $res = $priority_order[0];
        if($res) $this->current_index += strlen($res[0]);
        return $res;
    }
}