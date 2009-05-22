<?php


/**
 * This class presently handles the HTTP calls for the sfRESTClientPlugin
 *
 * @package default
 * @author John Lianoglou <prometheas@gmail.com>
 */
class sfRESTClientWebBrowser extends sfWebBrowser
{
	private static $timerIndex = 1;
	
	/**
	 * Overriding the sfWebBrowser's method to inject some debug code.
	 *
	 * @return sfWebBrowser
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function call($uri, $method = 'GET', $parameters = array(), $headers = array(), $changeStack = true)
	{
		$headers[ 'X-sfRESTClientPlugin-sfWebBrowserAdapter'] = get_class($this->adapter);
		
		// get our timers ready
		if (sfConfig::get('sf_debug'))
		{
			$toolbar_timer = sfTimerManager::getTimer( $this->generateTimerName( $method, $uri ) );
			$logging_timer = new sfTimer();
		}
		
		$browser = parent::call($uri, $method, $parameters, $headers, $changeStack );
		
		if ( sfRESTClientToolkit::isDebug() )
		{
			$toolbar_timer->addTime();
			$elapsed_time = $logging_timer->getElapsedTime();
			
			if (sfConfig::get('sf_logging_enabled'))
			{
				$log_message = sprintf("%sms %s %s %s",
					$elapsed_time,
					$browser->getResponseCode(),
					$method,
					$uri
				);

				sfContext::getInstance()->getLogger()->info('{sfRESTClientWebBrowser} '. $log_message);
				sfContext::getInstance()->getLogger()->debug("\n============\n{$parameters}\n============");
				sfContext::getInstance()->getLogger()->debug("\n============\n{$browser->getResponseText()}\n============");
			}
		}
		
		return $browser;
	}
	
	private function generateTimerName( $action, $uri )
	{
		return sprintf('REST %s: %s %s', self::$timerIndex++, $action, $uri );
	}
	
}
