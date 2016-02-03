<?php
namespace WaitingFor;

class RegexResult {
	private $regexMatches = [];
	private $regexIndices = [];
	private $regexMeta = [];
	
	function __construct(array $regexes, $str) {
		$this->regexMeta = $regexes;
	
		foreach($regexes as $regex) {
			$res = [];
			preg_match_all($regex->regex(), $str, $res, PREG_OFFSET_CAPTURE);
			array_push($this->regexMatches, $res[0]);
			array_push($this->regexIndices, 0);
		}
	}
	
	function next() {
		if(!count($this->regexMatches)) return false;
		
		$nextMatch = $this->findNext();
		$delegate = $nextMatch["delegate"];
		$delegate($nextMatch["match"]);
		return true;
	}
	
	function all() {
		while($this->next()) {}
	}
	
	private function findNext($matchesIndex = 0) {
		//Current capture, from current regex index, and regex indice
		$match = $this->regexMatches[$matchesIndex][$this->regexIndices[$matchesIndex]];
		
		//TODO: Get preg_match_all capture offset field
		$startIndex = $match[1];
		$endIndex = $startIndex + $match[0];
		
		//Check if there are any earlier matches and filter out overlapping
		for($i = $matchesIndex + 1; $this->regexMatches; $i++) {
			$lower_match = $this->regexMatches[$i][$this->regexIndices[$i]];
			
			//Check if is before
			if($lower_match[1] < $startIndex) {
			
				//TODO: How to get actual hit in preg_match_all
				//Check if overlapping
				if(strlen($lower_match[0]) + $lower_match[1] < $startIndex)
					return $this->findNext($i);
			}
			
			//Increase match index for regex and remove if match string is empty
			$matchLength = count($this->regexMatches[$matchesIndex]);
			do {
                $this->regexIndices[$i]++;
                if (count($this->regexIndices[$i]) == $matchLength) {
                    //TODO: Remove index $i from matches_indexes and $matches
                }
            } while($this->regexIndices[$i] <= $endIndex);
		}
		
		//Increase matches index
		$this->regexIndices[$matchesIndex]++;
		
		return [
			"match" => $match,
			"delegate" => $this->regexMeta[$matchesIndex]->delegate()
		];
	}
}