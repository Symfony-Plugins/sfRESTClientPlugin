<?php


/**
 * This abstract behavior class defines the required hooks for remote
 * entities.
 *
 * @package plugins.sfRESTClientPlugin
 * @author John Lianoglou <prometheas@gmail.com>
 */
abstract class sfRESTClientRemoteEntityBehavior
{
	private $isEnabled = true;
	
	/**
	 * Creates a remote entity for this new object, using HTTP POST.
	 *
	 * @param Object $object
	 * @return int  the remote ID of the created object
	 * @throws sfRESTCallException  when the remote service is unable to create the new item
	 */
	abstract protected function postRemoteObject( $object );
	
	/**
	 * Updates the remote entity, using HTTP PUT.
	 *
	 * @param Object $object
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	abstract protected function putRemoteObject( $object );
	
	/**
	 * Gets the remote entity.
	 *
	 * @param Object $object
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	abstract protected function getRemoteObject( &$object);
	
	/**
	 * Deletes the remote object
	 *
	 * @param string $remote_id
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	abstract protected function deleteRemoteObject( $object );
	
	/**
	 * This hook is called before the local entity is deleted.
	 *
	 * @param Object $object
	 * @param Connection $con
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function preDelete( $object, $con=null )
	{
		if ( $this->isEnabled() )
		{
			$this->preDeleteExecute( $object );
		}
	}
	
	/**
	 * Default pre-delete measures.
	 *
	 * @param Object $object   a Propel model entity instance
	 * @param Connection $con  database connection
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	protected function preDeleteExecute( $object, $con=null )
	{
		// override to implement
		$this->deleteRemoteObject( $object );
	}
	
	/**
	 * This method is called after the local entity is deleted.
	 *
	 * @param Object $object
	 * @param Connection $con
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function postDelete( $object, $con=null )
	{
		if ( self::isEnabled() )
		{
			$this->postDeleteExecute( $object, $con );
		}
	}
	
	/**
	 * Default post-delete measures.
	 *
	 * @param BaseObject $object   a propel model entity instance
	 * @param Connection $con      database connection
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	protected function postDeleteExecute( $object, $con=null )
	{
		// override to implement
	}
	
	/**
	 * This method is called before the local entity is saved.
	 *
	 * @param Object $object
	 * @param Connection $con
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function preSave($object, $con=null)
	{
		if ( $this->isEnabled() )
		{
			$this->preSaveExecute( $object, $con );
		}
	}
	
	/**
	 * Default pre-save measures.
	 *
	 * @param BaseObject $object  a propel model entity instance
	 * @param Connection $con     database connection
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	protected function preSaveExecute( $object, $con=null )
	{
		if ( !$con instanceof Connection )
		{
			throw new Exception(
				"Saving of remote entities must be conducted within a transaction"
			);
		}

		try
		{
			// invalidate cache ahead of remote save
			// $object->setCachedOn( null );
			if ( $object->isNew() )
			{
				// new objects should be saved remotely first, so we can
				// get its remote_id
				$remote_id = $this->postRemoteObject( $object );
				$object->setRemoteId( $remote_id );
			}
			else
			{
				$this->putRemoteObject( $object );
			}

			$object->setCachedOn( 'now' );
		}
		catch ( Exception $e )
		{
			// capturing to log it
			if (sfConfig::get('sf_logging_enabled'))
			{
				sfContext::getInstance()->getLogger()->err('{sfRESTClientPlugin} '. $e->getMessage());
			}
			
			throw $e;
		}
	}
	
	/**
	 * This method is called after the local entity is saved.
	 *
	 * @param Object $object
	 * @param Connection $con
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function postSave( $object, $con=null )
	{
		if ( self::isEnabled() )
		{
			$this->postSaveExecute( $object, $con );
		}
	}
	
	/**
	 * Default post-delete measures.
	 *
	 * @param BaseObject $object  a propel model entity instance
	 * @param Connection $con     database connection
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	protected function postSaveExecute( $object, $con=null )
	{
		// override to implement
	}
	
	/**
	 * Disables the remote interactions.
	 *
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function disable()
	{
		$this->isEnabled = false;
	}
	
	/**
	 * Enables the remote interactions.
	 *
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function enable()
	{
		$this->isEnabled = true;
	}
	
	/**
	 * Answers whether remote interactions are enabled.
	 *
	 * @return void
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function isEnabled()
	{
		return $this->isEnabled;
	}
	
}
