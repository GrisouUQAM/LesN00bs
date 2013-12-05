<?php
class Base {
	public $user = "";
	public $url = "";
	
	function __construct($wikiUrl, $contributor){
		$this->url = $this->fixUrl($wikiUrl);
		$this->user = $contributor;
	}
	
	public function getPage($pageid){
		$url = $this->url . "/w/api.php?action=query&format=json";
		$url .= "&prop=revisions";
		$url .= "&pageids=$pageid";
		$url .= "&rvuser=" . $this->user;
		$json = $this->loadJson($url);
		
		return json_decode($json, true);
	}
	
	public function getContribs(){
		$url = $this->url . "/w/api.php?action=query&format=json";
		$url .= "&list=usercontribs";
		$url .= "&ucuser=" . $this->user;
		$json = $this->loadJson($url);
		
		return json_decode($json, true);
	}
	
	private function loadJson($url){
		$ch =  curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		
		if(empty($result)){
			$result = file_get_contents($url);
		}
		return $result;
	}
	
	private function fixUrl($url){
		$ws = explode('/', $url);
		if($ws[0] == "http:"){
			$url = "http://" . $ws[2];
		}else if($ws[0] == "https:"){
			$url = "https://" . $ws[2];
		}else{
			$url = "http://" . $ws[0];
		}
		return $url;
	}

}