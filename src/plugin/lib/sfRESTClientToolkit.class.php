<?php


/**
 * Utility methods for the sfRESTClientPlugin
 *
 * @package plugins.sfRESTClientPlugin
 * @author John Lianoglou <prometheas@gmail.com>
 */
class sfRESTClientToolkit
{
	
	/**
	 * Loads all the REST service configurations.
	 *
	 * @return array
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public static function getAllRestServiceDefinitions()
	{
		include(sfConfigCache::getInstance()->checkConfig('config/sfRESTClientPlugin.yml'));
		return sfConfig::get('sfRESTClientPlugin_services');
	}
	
	/**
	 * Retrieves the REST service configuration details of the named service.
	 *
	 * @param string $svc_name
	 * @return sfParameterHolder
	 *
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public static function getRestServiceDefinition( $svc_name )
	{
		$services = self::getAllRestServiceDefinitions();
		
		if (!isset($services[$svc_name]))
		{
			throw new sfRESTClientUnknownServiceException("Cannot find service {$svc_name}");
		}
		
		$svc_params = $services[$svc_name];
		sfRESTClientToolkit::normalizeToParameterHolder( $svc_params );
		
		return $svc_params;
	}
	
	/**
	 * This converts any parameter data into an sfParameterHolder instance.
	 *
	 * <strong>WARNING:</strong> this method may change the data type of
	 * the parameter.
	 *
	 * @param mixed $params
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public static function normalizeToParameterHolder( &$params )
	{
		if ( !$params instanceof sfParameterHolder)
		{
			if ( is_array($params) )
			{
				$params_array = $params;
				$params = new sfParameterHolder();
				$params->add( $params_array );
				unset($params_array);
			}
			else
			{
				throw new Exception("Unknown parameter source");
			}
		}
	}
	
	/**
	 * Sanitizes a URL string
	 *
	 * @param string $url_string
	 * @return string
	 * @author John Lianoglou <john {at} prometheas {dot} com>
	 */
	public static function sanitizeURL( $url_string )
	{
		return preg_replace('/([^:])\/\/+/', '\1/', $url_string );
	}
	
	/**
	 * Answers whether the sfRESTClientPlugin should take debugging
	 * actions.
	 *
	 * @return bool
	 */
	public static function isDebug()
	{
		return ( sfConfig::get('sf_debug') );
	}
	
}
