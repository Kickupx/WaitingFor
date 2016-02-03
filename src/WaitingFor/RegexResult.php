<?php
namespace WaitingFor;

class RegexResult {
    private $priority;
	private $regex;
    private $matches;
    private $matchesCount;
    private $current_index = 0;

    /**
     * RegexResult constructor.
     * @param $regex
     */
    public function __construct($priority, $regex, $str)
    {
        $this->priority = $priority;
        $this->regex = $regex;
        preg_match_all($regex, $str, $this->matches, PREG_OFFSET_CAPTURE);
        $this->matchesCount = count($this->matches);
    }

    function next() {
        if($this->current_index == $this->matchesCount)
            return null;
        else {
            $res = array_merge(
                $this->matches[$this->current_index][0],
                [
                    'priority' => $this->priority
                ]);
            $this->current_index++;
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