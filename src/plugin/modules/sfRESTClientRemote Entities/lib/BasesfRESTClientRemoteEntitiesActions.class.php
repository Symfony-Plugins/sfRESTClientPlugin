<?php



class BasesfRESTClientRemoteEntitiesActions extends sfActions
{
	
	/**
	 * Forces an update of the locally cached information from
	 * the remote entity's service.
	 *
	 * @rparam class      the class name of the entity cache
	 * @rparam remoteId   the remote ID of the entity
	 *
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	function executeForceCacheUpdate()
	{
		$class_name = $this->getRequestParameter('class');
		$remote_id  = $this->getRequestParameter('remoteId');
		
	}
	
}
