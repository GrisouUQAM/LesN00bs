<?php

if ( !defined( 'MEDIAWIKI' ) )
    die( 'Not an entry point.' );

// Load ressources
$dir = dirname( __FILE__ ) . '/';
$wgExtensionMessagesFiles['Grisou'] = $dir . 'Grisou.i18n.php';
$wgAutoloadClasses['GrisouHooks'] = $dir . 'Grisou.hooks.php';
$wgAutoloadClasses['GrisouDiff'] = $dir . 'Grisou.diff.php';



$wgExtensionCredits['Grisou'][] = array(
    'name' => 'Grisou',
    'author' => 'LesN00bs',
    'url' => '',
    'description' => 'Wiki contribution project',
    'descriptionmsg' => 'GRISOU',
    'version' => '1.0.0',
);

// Add the navigation Tab
$wgHooks['SkinTemplateNavigation'][] = 'GrisouHooks::onSkinTemplateNavigation';



//Setting Ressource Loader
$wgResourceModules['ext.grisouJs'] = array(
        'scripts' => 'contributions.js',
		'position' => 'top',
        'localBasePath' => $dir . "includes/js",
        'remoteExtPath' => 'Grisou/js',
);
$wgResourceModules['ext.grisouCss'] = array(
        'styles' => 'style.css',
		'position' => 'top',
        'localBasePath' => $dir . "includes/css",
        'remoteExtPath' => 'Grisou/css',
);
$wgHooks['BeforePageDisplay'][] = 'GrisouHooks::onBeforePageDisplay';


// Add Special Page
$wgExtensionCredits[ 'specialpage' ][] = array(
        'path' => __FILE__,
        'name' => 'Grisou',
        'author' => 'Charles',
        'url' => 'https://www.mediawiki.org/wiki/Extension:Grisou',
        'descriptionmsg' => 'grisou-desc',
        'version' => '0.1.0',
);
 
$wgAutoloadClasses[ 'SpecialGrisou' ] = __DIR__ . '/SpecialGrisou.php'; # Location of the SpecialGrisou class (Tell MediaWiki to load this file)
$wgSpecialPages[ 'Grisou' ] = 'SpecialGrisou'; # Tell MediaWiki about the new special page and its class name