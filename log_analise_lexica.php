<?php 
	error_reporting(0);
	ini_set('display_errors', 1);

	include __DIR__.'\class\Funcoes.class.php';
	include __DIR__.'\class\Token.class.php';

	$Funcoes = new Funcoes();

	$codigo = str_split($_POST['codigo']);
	$caracteresInvalidos = array();
	$Tokens = array();
	$token = '';
	$cont = 0;
	$ignora = false;

	foreach ($codigo as $key => $value) 
	{ 

		if($Funcoes->verificaValidadeCaractere(strtolower($codigo[$key]))){
			
			#Primeira validação: Elimina os includes
			if($codigo[$key] == '#')
			{
				$tiposTokens[] = new Token('diretiva', $codigo[$key]);

				while ($codigo[$key] != '>') {
					unset($codigo[$key]);

					$key++;
				}
				//Elimina o '>'
				unset($codigo[$key]);
				
				$key++;
			}

			//Remove strings
			if($codigo[$key] == "\"")
			{	
				$tiposTokens[] = new Token('simbolo_especial', $codigo[$key]);

				unset($codigo[$key]);
				$key++;

				while($codigo[$key] != '"')
				{
					unset($codigo[$key]);
					$key++;
				}

				$tiposTokens[] = new Token('simbolo_especial', $codigo[$key]);

				unset($codigo[$key]);
				$key++;	
			}
	
			#Segunda Validação
			while($Funcoes->verificaLetraPermitida($codigo[$key]) || $Funcoes->verificaNumeroPermitida($codigo[$key]))
			{
				$token .= $codigo[$key];

				unset($codigo[$key]);
				$key++;
			}

			if($Funcoes->verificaNumeroPermitida($token)){
				$tiposTokens[] = new Token('constante_inteira', $token);
				$token = '';
			}else{

				if($Funcoes->verificaPalavraReservada($token))
				{
					$tiposTokens[] = new Token('palavra_reservada', $token);
					$token = '';

				}else{
					if(!empty($token)){
						$tiposTokens[] = new Token('identificador', $token);
						$token = '';				
					}

					if($Funcoes->verificaSimboloComposto($codigo[$key].$codigo[$key+1]))
					{
						$tiposTokens[] = new Token('simbolo_composto', $codigo[$key].$codigo[$key+1]);
						$token = '';
						$key += 3;

					}else if($Funcoes->verificaSimboloEspecial($codigo[$key]) && !($Funcoes->verificaSimboloComposto($codigo[$key-1].$codigo[$key]))){
						$tiposTokens[] = new Token('simbolo_especial', $codigo[$key]);
						unset($codigo[$key]);
							
						$token = '';
						$key++;
					}
				}

			}
		}else{
			$caracteresInvalidos[] = $codigo[$key];
		}


	}	
	?>

	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="css/font-awesome.css">
	</head>

	<div class="row" style="width: 100%;">
		<div class="col-md-6">
			<table class="table table-striped">

				<thead>
					<tr>
						<th>TOKEN</th>
						<th>TIPO</th>
					</tr>
				</thead>

				<?php
					foreach ($tiposTokens as $key => $token) {
						?>
						<tr>
							<td><?php print $token->simbolo; ?></td>
							<td><?php print $token->tipo; ?></td>
						</tr>
						<?php
					}
				?>
			</table>
		</div>
	</div>
