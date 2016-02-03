<?php
namespace WaitingFor;

class RegexResult {
    private $priority;
    private $delegate;
	private $regex;
    private $matches;
    private $matchesCount;

    /**
     * RegexResult constructor.
     * @param $regex
     */
    public function __construct($priority, $regex, Callable $delegate, $str)
    {
        $this->priority = $priority;
        $this->delegate = $delegate;
        $this->regex = $regex;
        preg_match_all($regex, $str, $this->matches, PREG_OFFSET_CAPTURE);
        $this->matchesCount = count($this->matches);
    }

    function next($min_index) {
        foreach($this->matches[0] as $match) {
            if($match[1] < $min_index) { continue; }

            $res = array_merge(
                $match,
                [
                    'priority' => $this->priority,
                    'delegate' => $this->delegate
                ]);
            return $res;
        }

    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return mixed
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * @return mixed
     */
    public function getCurrentIndex()
    {
        return $this->current_index;
    }
}