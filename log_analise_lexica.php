<?php 
	
	print '<pre>';
	$codigo = str_split($_POST['codigo']);

	foreach ($codigo as $key => $value) {
		if($codigo[$key] == ' ' || $codigo[$key] == '' || $codigo[$key] == null){
			unset($codigo[$key]);
		} 
	}

	$codigo = array_values($codigo);

	print_r($codigo);
	exit;

?>