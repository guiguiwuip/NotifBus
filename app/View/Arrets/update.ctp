<?php

	$output = array(
		'arrets'        => $arrets, 
		'notifications' => $notifications 
	);

	echo json_encode($output);

?>