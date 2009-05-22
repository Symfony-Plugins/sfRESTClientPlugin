
<div class="cache_status_admin">
	<?php echo include_partial('cache_status', array('object' => $object)); ?>
	<?php echo button_to('Update Now', sprintf(
		'forceCacheUpdate?class=%s&remoteId=%s',
		get_class($object),
		$object->getRemoteId()
	)) ?>
</div>
