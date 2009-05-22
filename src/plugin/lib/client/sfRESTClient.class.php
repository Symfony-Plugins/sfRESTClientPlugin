<?php 


/**
 * This class provides the basic client functionality to a REST service.
 *
 * A discreet instance must be created for each REST service that shall need
 * to be interacted with.
 *
 * @package plugins
 * @subpackage sfRESTClientPlugin
 * @author John Lianoglou <prometheas@gmail.com>
 */
class sfRESTClient
{
	private $host;
	private $rootUri;
	
	private $browser;
	private $serviceConf;
	private $serviceDescriptor;
	
	private $logger;
	
	/**
	 * Constructor.
	 *
	 * @param string $rest_service_id
	 * @param string $adapter_class
	 * @param array  $adapter_options
	 *
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function __construct( $rest_service_id, $adapter_class=null, $adapter_options=array() )
	{
		$this->serviceConf = sfRESTClientToolkit::getRestServiceDefinition( $rest_service_id );
		$this->serviceDescriptor = sfRESTClientServiceDescriptor::createFromService( $rest_service_id );
		
		if ( sfConfig::get('sf_logging_enabled') )
		{
			$logging_conf = $this->serviceConf->get('logging', array('enabled' => false));
			if ( $logging_conf['enabled'] )
			{
				$this->prepareLogger( $logging_conf );
			}
		}
		
		// instantiate the web browser object
		$this->browser = new sfRESTClientWebBrowser(
			$this->serviceConf->get('headers'),
			$adapter_class,
			$adapter_options
		);
		
		if ( $this->serviceConf->has('user') )
		{
			$user = $this->serviceConf->get('user');
			$pass = $this->serviceConf->get('password');
			$this->browser->setAuthCredentials( $user, $pass );
		}
	}
	
	/**
	 * Performs an HTTP GET request of the specified URI
	 *
	 * @param string $entity_uri
	 * @param array  $params
	 *
	 * @return sfRESTServiceResponse
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function get( $entity_uri, $params=null )
	{
		$url = $this->serviceDescriptor->composeResourceUrl( $entity_uri, $params );
		$this->browser->get( $url );
		return new sfRESTServiceResponse( $this->browser, $url, 'GET' );
	}
	
	/**
	 * Performs an HTTP PUT request of the specified URI
	 *
	 * @param string $entity_uri
	 * @param string $data
	 * @param array  $params
	 *
	 * @return sfRESTServiceResponse
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function put( $entity_uri, $data, $params=null )
	{
		$url = $this->serviceDescriptor->composeResourceUrl( $entity_uri, $params );
		$this->browser->put( $url, $data );
		return new sfRESTServiceResponse( $this->browser, $url, 'PUT' );
	}
	
	/**
	 * Performs an HTTP POST request of the specified URI
	 *
	 * @param string $entity_uri
	 * @param string $data
	 * @param array  $params
	 *
	 * @return sfRESTServiceResponse
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function post( $entity_uri, $data, $params=null )
	{
		$url = $this->serviceDescriptor->composeResourceUrl( $entity_uri, $params );
		$this->browser->post( $url, $data );
		return new sfRESTServiceResponse( $this->browser, $url, 'POST' );
	}
	
	/**
	 * Performs an HTTP DELETE request of the specified URI
	 *
	 * @param string $entity_uri
	 * @param array  $params
	 *
	 * @return sfRESTServiceResponse
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function delete( $entity_uri, $params=null )
	{
		$url = $this->serviceDescriptor->composeResourceUrl( $entity_uri, $params );
		$this->browser->delete( $url );
		return new sfRESTServiceResponse( $this->browser, $url, 'DELETE' );
	}
	
	public function extractDataXmlToString( $data_xml )
	{
		$xml_string = null;
		
		if ( !is_string($data_xml) )
		{
			if ( is_array($data_xml) )
			{
				if ( isset($data_xml['module']) && isset($data_xml['action']) )
				{
					$controller = sfContext::getInstance()->getController();
					//$controller->getAction($data_xml['module'], $data_xml['action'])->getRequest()->getParameterHolder()->add( $data_xml['params'], 'sfRESTClient' );
					$xml_string = $controller->getPresentationFor($data_xml['module'], $data_xml['action'], $data_xml['params']);
				}
			}
		}
		
		if ( !is_string($xml_string) )
		{
			throw new Exception("Cannot extract XML string from supplied source");
		}
		
		return $xml_string;
	}
	
	/**
	 * Prepares the logging mechanism.
	 *
	 * @param sfParameterHolder $logging_conf
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	protected function prepareLogger( $logging_conf )
	{
		$file = isset($logging['file']) ? $logging['file'] : sfConfig::get('sf_logging_dir').DIRECTORY_SEPARATOR.'rest_client_'.SF_ENVIRONMENT.'.log';
		$this->logger = new sfFileLogger($file);
	}
	
	/**
	 * Answers whether or not REST interactions are being discreetly logged.
	 *
	 * @return bool
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function isLogging()
	{
		return $this->logger instanceof sfFileLogger;
	}
	
	/**
	 * Gets the sfLogger instance, or <code>null</code> if the client is not
	 * configured to keep its own logs.
	 *
	 * @return sfFileLogger
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getLogger()
	{
		return $this->logger;
	}
	
}
