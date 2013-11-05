<?php



final class GrisouHooks {

	public static function onBeforePageDisplay( &$out, $skin = false ) {
		$wOut = $out->getOutput();
		$wOut->addModules( 'ext.grisouCss' );
		$wOut->addModules( 'ext.grisouJs' );
		
		return $out;
	}


	public static function loadInfos( $contributor ) {
	
		
$jsonurl = $completeUrl."/w/api.php?action=query&list=usercontribs&format=json&ucuser=".$contributor."&ucnamespace=0%7C4%7C6%7C8&ucprop=ids%7Ctitle%7Ctitle&converttitles=";
		return true;
	}
	
	public static function onSkinTemplateNavigation( &$skin, &$contentActions ) {
		$contentActions['views']['grisou'] = array(
			'class' => false,
			'text' => "GISOU",
			'href' => 'http://grisou.charlesforest.com/index.php/Special:Grisou'
		);
		return true;
	}
	
	
}
