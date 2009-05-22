<?php

$plugin_dir = dirname(dirname(dirname(__FILE__)));
require $plugin_dir.'/lib/sfRESTClientToolkit.class.php';

// Autofind the first available app environment
$sf_root_dir = realpath(dirname(__FILE__).'/../../../../');
$apps_dir = glob($sf_root_dir.'/apps/*', GLOB_ONLYDIR);
$app = substr($apps_dir[0], 
              strrpos($apps_dir[0], DIRECTORY_SEPARATOR) + 1, 
              strlen($apps_dir[0]));
if (!$app)
{
  throw new Exception('No app has been detected in this project');
}

// Symfony test env bootstrap
require_once($sf_root_dir.'/test/bootstrap/functional.php');
require_once($sf_symfony_lib_dir.'/vendor/lime/lime.php');


//
// start tests
//



// test URL sanitization
$t = new lime_test(9, new lime_output_color());
$t->diag( 'Testing URL santization...' );

$good_url_string = 'http://sample.com/foo/bar/baz';

// make sure good URL strings aren't altered
$t->is( $good_url_string, sfRESTClientToolkit::sanitizeURL( $good_url_string ), 'good URL string should not be modified by santization');

// make sure that bad URL strings are properly altered
$bad_url_strings = array(
	'http://sample.com//foo/bar/baz',
	'http://sample.com////foo/bar/baz',
	'http://sample.com//foo///bar//////baz',
	'http://sample.com/foo/bar///////baz'
);

foreach ( $bad_url_strings as $bad_url_string )
{
	$t->isnt( $bad_url_string, sfRESTClientToolkit::sanitizeURL( $bad_url_string ), 'sanitized URL should differ from original' );
	$t->is( $good_url_string, sfRESTClientToolkit::sanitizeURL( $bad_url_string ), 'sanitized URL should match good version' );
}
