<?php


/**
 * An interface for classes representing entities from remote services.
 *
 * This interface is useful for classes that "wrap" responses from remote
 * services (eg, a class that might provide OOP access to a response XML
 * document representing a specific type of remote entity).
 *
 * @package sfRESTClientPlutin
 * @author John Lianoglou <prometheas@gmail.com>
 */
interface sfIRESTEntityDataWrapper
{
	
	/**
	 * Populates the wrapper with data retrieved from the remote service.
	 *
	 * @param mixed $data
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function hydrate( $data );
	
	/**
	 * Returns a text representation of the data in the wrapper that
	 * should be suitable for sending to the remote service.
	 *
	 * @return string
	 * @author John Lianoglou <prometheas@gmail.com>
	 */
	public function serialize();
	
}
