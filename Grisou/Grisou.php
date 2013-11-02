<?php

if ( !defined( 'MEDIAWIKI' ) )
    die( 'Not an entry point.' );

$wgExtensionCredits['Grisou'][] = array(
    'name' => 'Grisou',
    'author' => 'Charles-David Forest-Le Noir et André Grégoire',
    'url' => '',
    'description' => 'Wiki contribution project',
    'descriptionmsg' => 'GRISOU',
    'version' => '1.0.0',
);

// Load ressources
$dir = dirname( __FILE__ ) . '/';
$wgExtensionMessagesFiles['Grisou'] = $dir . 'Grisou.i18n.php';
$wgAutoloadClasses['GrisouHooks'] = $dir . 'Grisou.hooks.php';
$wgAutoloadClasses['GrisouDiff'] = $dir . 'Grisou.diff.php';


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
