<?php
namespace WaitingFor;

class Regex {
	private $m_regex;
	private $m_delegate;
	
	function __construct($regex, Callable $delegate) {
		$this->m_regex = $regex;
		$this->m_delegate = $delegate;
	}
	
	function regex() { return m_regex; }
	function delegate() { return m_delegate; }
}