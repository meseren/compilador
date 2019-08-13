<?php 
	include __DIR__.'\Funcoes.class.php';

	$Funcoes = new Funcoes();

	print '<pre>';

	
	$codigo = str_split($_POST['codigo']);
	

	$token = '';

	//foreach ($codigo as $key => $value) {
	for ($key=0; $key < 2; $key++) { 
		$token .= $codigo[$key];

		if($Funcoes->verificaValidadeCaractere($codigo[$key])){
			
			//Primeira validação: Elimina os includes
			$pos = $key;
			if($codigo[$key] == '#')
			{
				while ($codigo[$key] != '>') {
					unset($codigo[$key]);
					$key++;
				}

				unset($codigo[$key]);
				
				$key++;
				$key = $pos;
			}
		}

	}

	print_r($codigo);
		exit;

	$codigo = array_values($codigo);

	print_r($codigo);
	exit;

?>