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
				$tokens[] = $codigo[$key];
				unset($codigo[$key]);
				$key++;

				while($codigo[$key] != '"')
				{
					unset($codigo[$key]);
					$key++;
				}

				$tokens[] = $codigo[$key];

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

			if($Funcoes->verificaPalavraReservada($token))
			{
				$tokens[] = $token;
				$tiposTokens[] = new Token('palavra_reservada', $token);
				$token = '';

			}else{
				if(!empty($token)){
					$tokens[] = $token;
					$tiposTokens[] = new Token('identificador', $token);
					$token = '';				
				}

				if($Funcoes->verificaSimboloComposto($codigo[$key].$codigo[$key+1]))
				{
					$tokens[] = $codigo[$key].$codigo[$key+1];
					$tiposTokens[] = new Token('simbolo_composto', $token);
					$token = '';
					$key += 3;

				}else if($Funcoes->verificaSimboloEspecial($codigo[$key])){
					$tokens[] = $codigo[$key];
					$tiposTokens[] = new Token('simbolo_especial', $codigo[$key]);
					unset($codigo[$key]);
						
					$token = '';
					$key++;
				}
			}
			
		}else{
			$caracteresInvalidos[] = $codigo[$key];
		}

	}

	$tokens[count($tokens)] = '$';

    //Preencher
    $tabela["E"]["id"]= "TS";
    $tabela["E"]["num"]= "TS";
    $tabela["E"]["("]= "TS";
    $tabela["T"]["id"]= "FG";
    $tabela["T"]["num"]= "FG";
    $tabela["T"]["("]= "FG";
    $tabela["S"]["+"]= "+TS";
    $tabela["S"]["-"]= "-TS";
    $tabela["S"][")"]= "$";
    $tabela["S"]["$"]= "$";
    $tabela["G"]["+"]= "$";
    $tabela["G"]["G"]= "$";
    $tabela["G"]["*"]= "*FG";
    $tabela["G"]["/"]= "/FG";
    $tabela["G"]["-"]= "$";
    $tabela["G"][")"]= "$";
    $tabela["G"]["$"]= "$";
    $tabela["F"]["id"]= "id";
    $tabela["F"]["num"]= "num";
    $tabela["F"]["("]= "(E)";

	$view['PILHA'] = array();
	$view['CADEIA'] = array();
	$view['REGRA'] = array();

	$pilhaTokens = $tokens;

	$reconhecidos = array();

	$pilha = array(0 => '$', 1 => 'E');
	$i = 0;
	
	while(count($pilha) > 1) {

		if($Funcoes->verificaSimboloTerminal($pilha[count($pilha)-1])){
			if($pilha[count($pilha)-1] == $pilhaTokens[0]){
				$reconhecidos[] = $pilhaTokens[0];
				array_pop($pilha);
				unset($pilhaTokens[0]);
				$pilhaTokens = array_values($pilhaTokens);
				$i++;
				
			}else{
				$erro = true;
				break;
			}

		}else
			if(isset($tabela[$pilha[count($pilha)-1]][$pilhaTokens[0]]))
			{
				if($tabela[$pilha[count($pilha)-1]][$pilhaTokens[0]] == '$'){
					array_pop($pilha);
				}else 
					if($Funcoes->verificaSimboloTerminal($tabela[$pilha[count($pilha)-1]][$pilhaTokens[0]]))
					{
						$producao = $tabela[$pilha[count($pilha)-1]][$pilhaTokens[0]];	
						array_pop($pilha);
						array_push($pilha, $producao);
					}else{
						$producao = strrev($tabela[$pilha[count($pilha)-1]][$pilhaTokens[0]]);
						array_pop($pilha);

						for ($j=0; $j < strlen($producao); $j++)
							array_push($pilha, $producao[$j]);
					}
			}else{
				$erro = true;
				break;
			}

		
		$view['PILHA'][] = $pilha;
		$view['CADEIA'][] = $pilhaTokens;
		if(!empty($tabela[$pilha[count($pilha)-1]][$pilhaTokens[0]])){
			$view['REGRA'][] = $pilha[count($pilha)-1].' <i style="font-size: 12px;" class="fa fa-arrow-right" aria-hidden="true"></i> '.$tabela[$pilha[count($pilha)-1]][$pilhaTokens[0]];
		}else{
			$view['REGRA'][] = 'RECONHECIDO <i style="font-size: 12px;" class="fa fa-check" aria-hidden="true"></i>';
		}
	}

	$view['REGRA'][count($view['REGRA'])-1] = 'SUCESSO'; 

	?>

	<?php

		if(!$erro){
	?>

			<head>
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
				<link rel="stylesheet" href="css/font-awesome.css">
			</head>

			<div class="row" style="width: 100%;">
				<div class="col-md-2">
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

				<div class="col-md-2"></div>

				<div class="col-md-6">
					<table class="table table-striped">

						<thead>
							<tr>
								<th>PILHA</th>
								<th>CADEIA</th>
								<th>REGRA</th>
							</tr>
						</thead>

					<?php
						foreach ($view['PILHA'] as $key => $value) {
							?>

							<tr>
								
								<td>
									<?php 

										foreach($view['PILHA'][$key] as $i => $val)
										{
											print $val;
										}
									?>
									
								</td>
								
								<td>
									<?php 

										foreach($view['CADEIA'][$key] as $i => $val)
										{
											print $val;
										}
									?>
									
								</td>
								<td><?php print $view['REGRA'][$key]?></td>
							</tr>

							<?php
						}
					?>
					</table>

					<br><br>

					<a href="sintatico.php"><button type="button" class="btn btn-primary">Voltar</button></a>

					<br><br>
				</div>

			</div>
	<?php
		}else{
			?>
				<head>
					<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
				</head>

				<div style="position: absolute;left: 50%;top: 50%;margin-left: -110px;margin-top: -40px;">
					<h4>Ops... Erro!</h4>
					<br><br>

					<a href="sintatico.php"><button type="button" class="btn btn-primary">Tentar novamente</button></a>

					<br><br>
				</div>
			<?php
		}
	?>