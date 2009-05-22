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




// test creation from array
$t = new lime_test(9, new lime_output_color());
$t->diag( 'Testing URL santization...' );

$params = array(
	'scheme'   => 'http',
	'host'     => 'sample.com',
);

$service_desc = new sfRESTClientServiceDescriptor( $params );

// ensure parameters were loaded properly
$t->is( $service_desc->getScheme(), $params['scheme'], 'ensuring scheme matches.');
$t->is( $service_desc->getHost(), $params['host'], 'ensuring host name matches.');
$t->is( $service_desc->getRootUri(), '/', 'ensuring root URI matches.');
$t->ok( $service_desc->hasPortNum() === false, 'ensuring missing port number is understood' );

$t->is( $service_desc->getRootURL(), 'http://sample.com/', 'ensuring root URL is accurate');


// ensure that omission of root URI is the same as being set to '/'
$params[ 'root_uri'] = '/';
$service_desc2 = new sfRESTClientServiceDescriptor( $params );
$t->is( $service_desc->getRootUri(), $service_desc2->getRootUri(), 'ensure default root URI is `/`');
unset($service_desc2);


// add port number and specificy root URI
$params[ 'port' ] = 200;
$params[ 'root_uri' ] = '/foo/bar/baz';

$service_desc = new sfRESTClientServiceDescriptor( $params );

$t->ok( $service_desc->hasPortNum(), 'should have port number now' );
$t->is( $service_desc->getRootUri(), $params[ 'root_uri' ], 'should have loaded the proper root URI');
$t->is( $service_desc->getRootURL(), 'http://sample.com:200/foo/bar/baz/', 'ensuring root URL now includes root URI');
