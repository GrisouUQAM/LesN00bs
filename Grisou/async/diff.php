<?php
require_once('../includes/imports/diff_match_patch.php');

class Diff{

public function dmp(){
	static $dmp = null;
	if(!$dmp){
		$dmp = new diff_match_patch();
	}
	return $dmp;
}

public function getDiff ($text1, $text2) {
	$res = $this->dmp()->diff_main(strip_tags($text1), strip_tags($text2), false);	 //strip_tags is a PHP function that removes all html tags from a string
	$newDmp = new diff_match_patch();
	$newDmp->diff_cleanupSemantic($res);
	return $res;
}

public function prettyHtml($diffs, $lengthOfText) {
	$equalCount = 0;
	$deleteCount = 0;
	$insertCount = 0;
	$sentenceMatchingRate = 0.00;
	$numberOfChanges = 0;
	$results = $this->dmp()->diff_prettyHtml($diffs);
	for ($x = 0; $x < sizeof($diffs); $x++) {
		switch ($diffs[$x][0]){
			case 0:
			$equalCount++;
			break;
			case 1:
			$insertCount++;
			break;
			case -1:
			$deleteCount++;
			break;
		}
	}
	$newDmp = new diff_match_patch();
	$numberOfChanges = $newDmp->diff_levenshtein($diffs);
	$sentenceMatchingRate = ($lengthOfText - $numberOfChanges)/$lengthOfText;
	$results .= ("<br/>");
	$results .= "Number of words deleted : ".$deleteCount."";
	$results .= ("<br/>");
	$results .= "Number of words inserted : ".$insertCount."";
	$results .= ("<br/>");
	$results .= "Text matching rate : ".$sentenceMatchingRate."";
	$results .= ("<br/>");
	$results .= ("<br/>");
	return $results;
}

public function getMatch($textToMatch, $text){
    $textLen = strlen($text);
    $threshold = 0.33; //Threshold of 0.33 is taken from the WEHA analysis document.
    $Dmp = new diff_match_patch();
    $Dmp->Match_Threshold = $threshold;
    
    //Take only the first 32 char du to API limits
    $sbLen = strlen($textToMatch);
    if($sbLen > 32){
        $sbLen = 32;
    }
    $pattern = substr($textToMatch, 0, $sbLen);
    
    $result = $Dmp->match_main($text, $pattern, $textLen);
    //$result = $Dmp->match_main($text, $textToMatch, $textLen);
    return $result > -1;
}
}
?>

