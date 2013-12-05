<?php
class Frame{
	public $url = "";
	
	function __construct($wikiUrl){
		$this->url = $this->fixUrl($wikiUrl);
	}
	
	public function formatPage($data){
		
		$pages = $data["query"]["pages"];
		$output = "";
		foreach($pages as $p){
			$revs = $p["revisions"];
			$output .= "<table class='grisouTables'>";
			foreach($revs as $rev){
				foreach($rev as $k=>$v){
					$block = "";
					$block .= "<tr>";
					$block .= "	<td>";
					$block .= 	$k;
					$block .= "	</td>";
					$block .= "	<td>";
					$block .= 	$v;
					$block .= "	</td>";
					$block .= "</tr>";
					
					$output .= $block;
				}
			}
			$output .= "</table>";
		}
		return $output;
	}
	
	public function formatContribs($data){
		$output = "";
		$contributions = $data["query"]["usercontribs"];
		if(is_array($contributions)){
			foreach($contributions as $k=>$v){
				$time = explode('T', $v["timestamp"]);
				$block = "<a class='grisouLinks' href='{$v['pageid']}'>";
				$block .= "<div class='element'>";
					$block .= "<user>{$v["user"]}</user>";
					$block .= "<elem>{$v["title"]}</elem>";
					$block .= "<date>{$time[0]}</date>";
				$block .= "</div>"; 
				$block .= "</a>";
				$block .= "<div id='grisou_{$v['pageid']}' style='display:none;'></div>";
				
				$output .= $block ;
			}
		}else{
			$output .= "<div class='element'> Aucun résultat</div>";
		}
		
		return $output;
	}
	
	private function fixUrl($url){
		$ws = explode('/', $url);
		if($ws[0] != "http:"){
			$url = "http://" . $ws[0];
		}else if($ws[0] != "https:"){
			$url = "https://" . $ws[0];
		}else{
			$url = "http://" . $ws[2];
		}
		return $url;
	}
}