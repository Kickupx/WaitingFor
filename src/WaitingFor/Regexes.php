<?php
namespace WaitingFor;

class Regexes {
	private $regexes = [];
	
	function add($regex, Callable $func) {
		enforceNotExists($regex, $this->regexes, "Regex");
		$this->regexes .= new Regex($regex, $func);
	}
	
	function match($str) {
		return new RegexResult($this->regexes, $str);
	}
}