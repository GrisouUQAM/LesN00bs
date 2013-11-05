<?php
include_once( dirname(__FILE__) . '/diff.php');

$contributor = $_GET["user"];
$wikiUrl = fixUrl($_GET["wiki"]);
$diff = new Diff();

function fixUrl($url){
	$withoutSlash = explode('/', $url);
	
	if($withoutSlash[0] != "http:"){
		$url = "http://" . $withoutSlash[0];
	}else{
		$url = "http://" . $withoutSlash[2];
	}

	return $url;
}


function showGoogleDiff($text1, $text2) {
	$diff = new Diff();
	$result = $diff->getDiff($text1, $text2); //Return an array of Diff objects
	$output = $diff->prettyHtml($result, strlen(utf8_decode($text1)));
	return $output;
}

// A user agent is required by MediaWiki API
//ini_set('user_agent', 'ProjetGrisou/1.1 (http://grisou.uqam.ca; grisou.science@gmail.com)');

?>
<h1>Articles which <?php echo $contributor; ?> contributed to</h1>
            <table>
				<tr>            
					<th>Articles from <?php echo $wikiUrl; ?> </th>
					<th>Has the contribution survived?</th>
					<th>Edits</th>
					<th>What is the value of the contribution?</th>					
				</tr>
<?php

///////////////////////////////////////////////////////Articles//////////////////////////////////////////////////////////////////////////////////////////
# http://fr.wikipedia.org/w/api.php?action=query&list=usercontribs&format=json&ucuser=Mike&ucnamespace=0%7C4%7C6%7C8&ucprop=ids%7Ctitle%7Ctitle&converttitles=
$jsonurl = $wikiUrl."/w/api.php?action=query&list=usercontribs&format=json&ucuser=".$contributor."&ucnamespace=0%7C4%7C6%7C8&ucprop=ids%7Ctitle%7Ctitle&converttitles=";
$json = file_get_contents($jsonurl, true);

$obj = json_decode($json, true);

$usercontributions = $obj['query']['usercontribs'];

	
foreach ($usercontributions as $contribution) {
?>
	<tr><td> <?php echo $contribution['title'] ; ?> </td>
<?php
	$pageId = $contribution['pageid'];
	
	#
	#	Listing des activitées du contributeur.
	#
	# http://fr.wikipedia.org/w/api.php?action=query&prop=revisions&format=json&rvprop=ids%7Ctimestamp%7Cuser&rvuser=Mike&pageids=107569

	$revurl = $wikiUrl."/w/api.php?action=query&prop=revisions&format=json&rvprop=ids%7Ctimestamp%7Cuser&rvuser=".$contributor."&pageids=".$pageId;
	$json = file_get_contents($revurl, true);
	$obj = json_decode($json, true);
#	print_r("Query 1 :<br>");
#	print_r($obj);
	
	$queries = $obj['query'];
	$pages = $queries['pages'];
	$revision = $pages[$pageId];
	$userrevision = $revision['revisions'];
	foreach($userrevision as $temp) {
		$oldVersion = $temp['parentid'];
		$userVersion = $temp['revid'];
		$usertimestamp = $temp['timestamp'];
	}	
	
	
	#
	#	Listing des articles affectés par le contributeur. ???
	#
	
	$oldRevisionContent = $wikiUrl."/w/api.php?action=parse&format=json&oldid=".$oldVersion."&prop=text";
	$jsonOld = file_get_contents($oldRevisionContent, true);
	$oldTextDecoded = json_decode($jsonOld, true);	
	
#	print_r("Query 2 :<br>");
#	print_r($oldTextDecoded);
	
	$parsedOldText = $oldTextDecoded['parse'];
	$oldTextText = $parsedOldText['text'];
	$oldText = $oldTextText['*'];
	$userRevisionContent = $wikiUrl."/w/api.php?action=parse&format=json&oldid=".$userVersion."&prop=text";
	$jsonNew = file_get_contents($userRevisionContent, true);
	$newTextDecoded = json_decode($jsonNew, true);	
	
#	print_r("Query 2 :<br>");
#	print_r($newTextDecoded);
	
	$parsedNewText = $newTextDecoded['parse'];
	$newTextText = $parsedNewText['text'];
	$newText = $newTextText['*'];
	$analysisTable = showGoogleDiff($oldText, $newText);
	
	/////////////////////////// Does the contribution survive? ///////////////////////////////////
        
        //Return the lastest revision of an article
        $lastestRevisionQueryString = $wikiUrl.'/w/api.php?action=query&prop=revisions&format=json&rvprop=ids%7Ctimestamp%7Cuser%7Cuserid%7Ccontent&rvlimit=1&rvdir=older&rvparse=&pageids='.$pageId;
        
        $lastestRevisionJson = file_get_contents($lastestRevisionQueryString,true);
        $lastestRevisionDecoded = json_decode($lastestRevisionJson,true);
        
        //JSON Obj Path: query:pages:$pageId:revisions[0] <-- only if the pageid exists
        $lastestRevisionProps = $lastestRevisionDecoded['query']['pages'][$pageId]["revisions"][0];
        
        $lastestRevisionId = $lastestRevisionProps["revid"];
        $lastestRevisionTimestamp = new DateTime($lastestRevisionProps["timestamp"]);
        $lastestRevisionContent = $lastestRevisionProps['*'];
        
        $survive = FALSE;
        
        if($userVersion === $lastestRevisionId){
            
        } else {
            //$result.='<td>'.'yes/no'.'</td>';
            $diff_result = $diff->getDiff($newText, $lastestRevisionContent);
            for($i = 0; $i<sizeof($diff_result);++$i)
                switch ($diff_result[$i][0]){
                    case 1: //If an insertion, check if still exists
                        $survive = $diff->getMatch($lastestRevisionContent, $diff_result[$i][1]);
                        break;
                    default:
                        continue;
            }
            if($survive) break;
        }
        
        if($survive){
            $result.= '<td>'.'Yes'.'</td>';
        } else {
            $result.= '<td>'.'No'.'</td>';
        }
	
        //Replaced by: Does the contribution survive?
	/////////////////////////// Timestamps comparisons on articles/////////////////////////////////////				
	
//	$oldVersionTimeUrl = $wikiUrl."/w/api.php?action=query&prop=info&format=json&inprop=notificationtimestamp&revids=".$oldVersion."&converttitles=";
//	$jsonSecondTimeQuery = file_get_contents($oldVersionTimeUrl, true);	
//	$object = json_decode($jsonSecondTimeQuery, true);
//	$queries = $object['query'];
//	$pages = $queries['pages'];
//	$revision = $pages[$pageId];
//	$oldTimeStamp = $revision['touched'];
//
//	$time1 = new DateTime($usertimestamp);
//	$time2 = new DateTime($oldTimestamp);	
//
//	$dateDifference = date_diff($time1, $time2);
//	$timeDiffString = " ".$dateDifference->format('%D:%M:%S')." ";
//	$result .= '<td>'.$timeDiffString.'</td>';
        
        
	$result .= '<td>'.$analysisTable.'</td>';
	$result .= '<td>Score quelconque</td></tr>';
}

$result .= '</table>
			<h2>Total score</h2>
			<br/>
			<br/>';
			
////////////////////////////////////////////////////////////Talk////////////////////////////////////////////////////////////////////////////////
$jsonurlTalk = $wikiUrl."/w/api.php?action=query&list=usercontribs&format=json&ucuser=".$contributor."&ucnamespace=1%7C3%7C5%7C9&ucprop=ids%7Ctitle%7Ccomment&converttitles=";
$jsonTalk = file_get_contents($jsonurlTalk, true);

$objTalk = json_decode($jsonTalk, true);

$queriesTalk = $objTalk['query'];
$userTalks = $queriesTalk['usercontribs'];

$result .= '<h1>Talks which '.$contributor.' contributed to</h1>
            <table>
				<tr>            
					<th>Articles talked about</th>
					<th>Title of the contribution-talk (topic)</th>				
				</tr>';
				
foreach ($userTalks as $talk) {
	$result .= '<tr><td>'.$talk['title'].'</td>';

	$result .= '<td>'.$talk['comment'].'</td></tr>';
}

$result .= '</table>
			<h2>Total score</h2>';

 ## echo $result;


