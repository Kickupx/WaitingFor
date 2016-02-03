<?php
namespace WaitingFor;

class Regexes {
	private $regexes = [];
	
	function add($regex, Callable $func) {
		\Enforce\enforceNotExists($regex, $this->regexes, "Regex");
		array_push($this->regexes, new Regex($regex, $func));
	}
	
	function match($str) {
		return new RegexResult($this->regexes, $str);
	}
}