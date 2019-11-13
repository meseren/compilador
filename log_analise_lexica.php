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

	#Auxiliares para o tradutor, mais conhecido como gambiarra
	$tradutor = '';
	$declaracao_variavel = false;
	$qt_variavel = 0;
	$leitura = false;
	$impressao = false;
	$atribuicao = false;
	$linha_nova = false;
	$operador = array();

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
				#Elimina o '>'
				unset($codigo[$key]);
				
				$key++;
			}

			#Remove strings
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
				$tiposTokens[] = new Token('constante', $token);

				if($atribuicao)
					$tradutor .= 'CRCT '.strtoupper($token)."\n";

				$token = '';

			}else{

				if($Funcoes->verificaPalavraReservada($token))
				{
					$tiposTokens[] = new Token('palavra_reservada', $token);

					switch ($token) {
						case 'main':
							$tradutor .= 'INPP'."\n";
							$declaracao_variavel = false;
							break;

						case 'int':
							$declaracao_variavel = true;
							break;
						
						case 'scanf':
							$tradutor .= 'LEIT'."\n";
							$leitura = true;
							break;

						case 'printf':
							$impressao = true;
							break;

						default:
							# code...
							break;
					}

					$token = '';

				}else{
					if(!empty($token)){
						$tiposTokens[] = new Token('identificador', $token);

						if($declaracao_variavel)
							$qt_variavel++;

						if($leitura){
							$leitura = false;
							$tradutor .= 'ARMZ '.strtoupper($token)."\n";
						}

						if($impressao)
							$tradutor .= 'CRVL '.strtoupper($token)."\nIMPR \n";
						
						if($atribuicao)
							$tradutor .= 'CRVL '.strtoupper($token)."\n";

						if(!$variavel){
							$var = $token;
						}

						$variavel = true;


						
						$token = '';				
					}

					if($Funcoes->verificaSimboloComposto($codigo[$key].$codigo[$key+1]))
					{
						$tiposTokens[] = new Token('simbolo_composto', $codigo[$key].$codigo[$key+1]);
						$token = '';
						$key += 3;

					}else if($Funcoes->verificaSimboloEspecial($codigo[$key]) && !($Funcoes->verificaSimboloComposto($codigo[$key-1].$codigo[$key]))){
						$tiposTokens[] = new Token('simbolo_especial', $codigo[$key]);

						if($atribuicao){
							if($Funcoes->verificaOperador($codigo[$key])){
								$operador[] = $Funcoes->verificaOperador($codigo[$key]);
							}
						}

						if($declaracao_variavel && $codigo[$key] == ';'){
							$tradutor .= 'AMEM '.$qt_variavel."\n";
						}

						if($codigo[$key] == ';'){
							$declaracao_variavel = false;
							$impressao = false;
							$linha_nova = true;

							if($atribuicao){
								foreach ($operador as $key => $value) {
									$tradutor .= $value."\n".'ARMZ '.strtoupper($var)."\n";
								}
							}

							$atribuicao = false;
						}

						if($variavel && $codigo[$key] == '='){
							$atribuicao = true;
						}
						
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

	print '<pre>';
	print $tradutor;
	//print_r($tiposTokens);
	exit;
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
