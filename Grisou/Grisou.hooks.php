<?php



final class GrisouHooks {

	public static function onBeforePageDisplay( &$out, $skin = false ) {
		$wOut = $out->getOutput();
		$wOut->addModules( 'ext.grisouCss' );
		$wOut->addModules( 'ext.grisouJs' );
		
		
		$wOut->addHtml("<form id='grisou-form'>
				<div id='question'>
					<h2>Enter the username of the contributor:</h2>
					<input type='text' name='user' />
					<h2>Enter the url for the wikipedia web site the contributor has contributed to :</h2>
					<input type='text' name='wiki' value='fr.wikipedia.org' />
					<input type='button' value='Soumettre' id='grisou-submit' />
				</div>
			</form>");
		
		$wOut->addHtml("<div id='grisou-result'></div>");
		
		return true;
	}


	public static function loadInfos( $contributor ) {
	
		
$jsonurl = $completeUrl."/w/api.php?action=query&list=usercontribs&format=json&ucuser=".$contributor."&ucnamespace=0%7C4%7C6%7C8&ucprop=ids%7Ctitle%7Ctitle&converttitles=";
		return true;
	}
	
	public static function onSkinTemplateNavigation( &$skin, &$contentActions ) {
		$contentActions['views']['grisou'] = array(
			'class' => false,
			'text' => "GISOU",
			'href' => 'http://grisou.charlesforest.com/index.php/Extension:Grisou'
		);
		return true;
	}
	
	
}
