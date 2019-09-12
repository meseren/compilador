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
				$Tokens[] = new Token('simbolo_especial', $codigo[$key]);
				unset($codigo[$key]);
				$key++;

				while($codigo[$key] != '"')
				{
					unset($codigo[$key]);
					$key++;
				}

				$Tokens[] = new Token('simbolo_especial', $codigo[$key]);

				unset($codigo[$key]);
				$key++;	
			}
	
			#Segunda Validação
			while($Funcoes->verificaLetraPermitida($codigo[$key]))
			{
				$token .= $codigo[$key];

				unset($codigo[$key]);
				$key++;
			}

			if($Funcoes->verificaPalavraReservada($token))
			{
				$Tokens[] = new Token('palavra_reservada', $token);
				$token = '';

			}else{
				if(!empty($token)){
					$Tokens[] = new Token('identificador', $token);
					$token = '';				
				}

				if($Funcoes->verificaSimboloComposto($codigo[$key].$codigo[$key+1]))
				{
					$Tokens[] = new Token('simbolo_composto', $codigo[$key].$codigo[$key+1]);
					$token = '';
					$key += 3;

				}else if($Funcoes->verificaSimboloEspecial($codigo[$key])){
					$Tokens[] = new Token('simbolo_especial', $codigo[$key]);
					unset($codigo[$key]);
						
					$token = '';
					$key++;
				}
			}
			
		}else{
			$caracteresInvalidos[] = $codigo[$key];
		}

	}
?>

<table>
	<thead>
		<tr>
			<th>Token</th>
			<th>Símbolo</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($Tokens as $key => $value) {
		?>	
				<tr>
					<td><?php print $Tokens[$key]->simbolo; ?></td>
					<td><?php print $Tokens[$key]->tipo; ?></td>
				</tr>
		<?php
			}
		?>
	</tbody>
</table>

<?php

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
    $tabela["G"][")"]= "$";
    $tabela["G"]["$"]= "$";
    $tabela["F"]["id"]= "id";
    $tabela["F"]["num"]= "num";
    $tabela["F"]["("]= "(E)";
    
    //Calculo
	$cadeia = "id+num";

	$tokens[0] = "id";
	$tokens[1] = "+";
	$tokens[2] = "num";
	$tokens[3] = "*";
	$tokens[4] = "id";
	$tokens[5] = "$";

	$view['PILHA'] = array();
	$view['CADEIA'] = array();
	$view['REGRA'] = array();

	$pilhaTokens = $tokens;

	$reconhecidos = array();

	$pilha = array(0 => '$', 1 => 'E');
	$i = 0;
	
	print '===== Inicio =====<br><br>';
	print 'Pilha =';
	print_r($pilha);
	print '<br>';
	print 'Pilha Tokens =';
	print_r($pilhaTokens);
	print '<br>';

	while(count($pilha) > 1) {
		
		print '<br><br>Token: '.$tokens[$i];

		if($Funcoes->verificaSimboloTerminal($pilha[count($pilha)-1])){
			if($pilha[count($pilha)-1] == $pilhaTokens[0]){
				$reconhecidos[] = $pilhaTokens[0];
				array_pop($pilha);
				unset($pilhaTokens[0]);
				$pilhaTokens = array_values($pilhaTokens);
				$i++;
				
			}else{
				print 'erro1';
				break;
			}
		}else
		if(isset($tabela[$pilha[count($pilha)-1]][$pilhaTokens[0]])){
			if($tabela[$pilha[count($pilha)-1]][$pilhaTokens[0]] == '$'){
				array_pop($pilha);
			}else 
			if($Funcoes->verificaSimboloTerminal($tabela[$pilha[count($pilha)-1]][$pilhaTokens[0]])){
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
			print $pilha[count($pilha)-1];
			print $tokens[$i];
			print 'erro2';
			break;
		}

		$view['PILHA'][] = $pilha;
		$view['CEDEIA'][] = $pilhaTokens;
		$view['REGRA'][] = $pilha[count($pilha)-1].' -> '.$tabela[$pilha[count($pilha)-1]][$pilhaTokens[0]];
		print '<br><br>i = '.$i;
		print '<br><br> Reconhecidos =';
		print_r($reconhecidos);
		print '<br><br> Pilha = ';
		print_r($pilha);
		print '<br><br> Pilha Tokens= ';
		print_r($pilhaTokens);
		print '<Br>--------------------<br>';
	}

	print '<pre>';
	print_r($view);

?>