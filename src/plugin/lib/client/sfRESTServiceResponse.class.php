<?php


/**
 * This class encapsulates the response data from the remote REST service.
 *
 * @package plugins
 * @subpackage sfRESTClientPlugin
 * @author John Lianoglou <prometheas@gmail.com>
 */
class sfRESTServiceResponse
{
	private
		$xml,
		$isError,
		$responseCode,
		$responseHeaders,
		$responseMessage,
		
		$requestUrl,
		$requestMethod;
	
	/**
	 * Constructor.
	 * @param sfWebBrowser $browser
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function __construct( $browser, $url=null, $method='GET' )
	{
		try
		{
			$this->xml = $browser->getResponseXml();
		}
		catch( Exception $e )
		{
			// swallow this one
		}
		
		$this->isError = $browser->responseIsError();
		$this->responseCode = $browser->getResponseCode();
		$this->responseHeaders = $browser->getResponseHeaders();
		$this->responseMessage = $browser->getResponseMessage();
		
		$this->requestUrl = $url;
		$this->requestMethod = $method;
	}
	
	/**
	 * Gets the HTTP method used to make the request that generated this
	 * response instance.
	 *
	 * @return string
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getRequestMethod()
	{
		return $this->requestMethod;
	}
	
	/**
	 * Gets the URL that was queried to generate this request instance.
	 *
	 * @return string
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getRequestUrl()
	{
		return $this->requestUrl;
	}
	
	/**
	 * Get a SimpleXML version of the response 
	 *
	 * @return SimpleXMLElement The reponse contents
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getXml()
	{
		return $this->xml;
	}
	
	/**
	 * Returns true if server response is an error.
	 * 
	 * @return bool
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function isError()
	{
		return $this->isError;
	}
	
	/**
	 * Get the response code
	 *
	 * @return string The response code
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getResponseCode()
	{
		return $this->responseCode;
	}
	
	/**
	 * Answers whether the response code matches the specified one.
	 * @param numeric $code
	 * @return bool
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function isResponseCode( $code )
	{
		return intval($code) === intval($this->getResponseCode());
	}
	
	/**
	 * Get the response headers
	 *
	 * @return array The response headers
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getResponseHeaders()
	{
		return $this->responseHeaders;
	}
	
	/**
	 * Get a response header
	 *
	 * @param string The response header name
	 *
	 * @return string The response header value
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getResponseHeader( $key )
	{
		$normalized_key = $this->normalizeHeaderName($key);
		return (isset($this->responseHeaders[$normalized_key])) ? $this->responseHeaders[$normalized_key] : '';
	}
	
	/**
	 * Returns the response message (the 'Not Found' part in 'HTTP/1.1 404 Not Found')
	 * 
	 * @return string 
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getResponseMessage()
	{
		return $this->responseMessage;
	}
	
}
