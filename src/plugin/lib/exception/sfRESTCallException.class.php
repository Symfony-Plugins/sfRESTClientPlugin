<?php


/**
 * An exception the sfRESTClientPlugin throws during REST communications
 * errors.
 *
 * @package plugins.sfRESTClientPlugin
 * @author John Lianoglou <prometheas@gmail.com>
 */
class sfRESTCallException extends sfRESTException
{
	private $restRequest;
	
	/**
	 * Constructor.
	 *
	 * @param mixed $input
	 */
	public function __construct( $request, $msg=null, $code=0 )
	{
		if ( is_string($request) )
		{
			$msg = $request;
		}
		
		$this->restRequest = $request;
		
		if ( !is_string($msg) )
		{
			$msg = sprintf('{sfRESTClientPlugin} %s %s %s',
				$this->restRequest->getServiceName(),
				$this->restRequest->getMethod(),
				$this->restRequest->getUrl()
			);
		}
		
		parent::__construct($msg, $code);
	}
	
	/**
	 * Supplies a reference to the sfRESTRequest instance used for
	 * the request.
	 *
	 * @return sfRESTRequest
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function getRestRequest()
	{
		return $this->restRequest;
	}
	
}
