<?php


/**
 * This class encapsulates a RESTful web services descriptor.
 *
 * @package sfRESTClientPlugin
 * @author John Lianoglou <prometheas@gmail.com>
 */
class sfRESTClientServiceDescriptor
{
	private $scheme  = 'http';
	private $host;
	private $portNum = null;
	private $rootUri = null;
	
	private $cachedRootURL = null;
	
	/**
	 * Constructor.
	 * @param mixed $params  can either be an sfParameterHolder instance, or an associative array
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function __construct( $params )
	{
		sfRESTClientToolkit::normalizeToParameterHolder( $params );
		
		$this->scheme  = $params->get('scheme');
		$this->host    = $params->get('host');
		$this->portNum = $params->get('port');
		$this->rootUri = $params->get('root_uri', '/');
	}
	
	/**
	 * Factory method that creates an instance given a service label.
	 *
	 * @param string $svc_name  the label given to the desired service in the configuration
	 * @return sfRESTClientServiceDescriptor
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public static function createFromService( $svc_name )
	{
		return new sfRESTClientServiceDescriptor( sfRESTClientToolkit::getRestServiceDefinition($svc_name) );
	}
	
	/**
	 * Gets the descriptor's host name
	 *
	 * @return string
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getHost()
	{
		return $this->host;
	}
	
	/**
	 * Gets the descriptor's root URI.
	 *
	 * @return string
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getRootUri()
	{
		return $this->rootUri;
	}
	
	/**
	 * Gets the descriptor's protocol.
	 *
	 * @return string
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getScheme()
	{
		return $this->scheme;
	}
	
	/**
	 * Alias for getScheme() method.
	 *
	 * @return string
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getProtocol()
	{
		return $this->getScheme();
	}
	
	/**
	 * Answers whether the descriptor is defined with a port number.
	 *
	 * @return bool
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function hasPortNum()
	{
		return is_numeric($this->portNum);
	}
	
	/**
	 * Returns the service descriptor's root URL string.
	 *
	 * @return string
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getRootURL()
	{
		if ( $this->cachedRootURL === null )
		{
			$url_string = sprintf(
				'%s://%s%s',
				$this->scheme,
				$this->host,
				$this->hasPortNum() ? ":{$this->portNum}" : ''
			);

			if ( !empty($this->rootUri) )
			{
				$url_string .= $this->rootUri;
			}
			
			$this->cachedRootURL = sfRESTClientToolkit::sanitizeURL( $url_string . '/' );
		}
		
		return $this->cachedRootURL;
	}
	
	/**
	 * Meant to render a parameterized URL
	 *
	 * @param string $uri 
	 * @param array $params 
	 * @return string
	 * @author John Lianoglou <prometheas@gmail.com>
	 * @todo implement this
	 */
	public function generateParameterizedUrl( $uri, $params )
	{
		//TODO: implement this
	}
	
	/**
	 * Creates a request URL given a relative URI.
	 *
	 * The service's root url will be prepended to the relative resource
	 * URI string.  If the instance's root URL is:
	 *
	 * <tt>http://sitename.com/api/rest</tt>
	 *
	 * And the <tt>$resource_uri</tt> argument is:
	 *
	 * <tt>users/25</tt>
	 *
	 * Then the resulting resource URL will return as:
	 *
	 * <tt>http://sitename.com/api/rest/users/25</tt>
	 *
	 * @param string $resource_uri  the relative resource URI
	 * @param array $param_array  optional associative array of GET params
	 *
	 * @return string
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function composeResourceURL( $resource_uri, $param_array=null )
	{
		if (is_array($param_array))
		{
			$resource_uri .= '?' . http_build_query($param_array);
		}
		
		return sfRESTClientToolkit::sanitizeURL( "{$this->getRootURL()}/{$resource_uri}" );
	}
	
}
