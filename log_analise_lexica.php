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
    $tabela["S"]["3"]= "+TS";
    $tabela["S"]["-"]= "-TS";
    $tabela["S"][")"]= "Vazio";
    $tabela["S"]["$"]= "Vazio";
    $tabela["G"]["+"]= "Vazio";
    $tabela["G"]["G"]= "Vazio";
    $tabela["G"]["*"]= "*FG";
    $tabela["G"]["/"]= "/FG";
    $tabela["G"][")"]= "Vazio";
    $tabela["G"]["$"]= "Vazio";
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
				
			}else{
				print 'erro';
			}
		}else
		if( isset($tabela[$pilha[count($pilha)-1]][$tokens[$i]])){
			if($tabela[$pilha[count($pilha)-1]][$tokens[$i]] == 'Vazio'){

			}else 
			if($Funcoes->verificaSimboloTerminal($tabela[$pilha[count($pilha)-1]][$tokens[$i]])){
				$producao = $tabela[$pilha[count($pilha)-1]][$tokens[$i]];	
				array_pop($pilha);
				array_push($pilha, $producao);
			}else{
				$producao = strrev($tabela[$pilha[count($pilha)-1]][$tokens[$i]]);
				array_pop($pilha);
				for ($j=0; $j < strlen($producao); $j++)
				array_push($pilha, $producao[$j]);
			}
			
		}

		print '<br><br> Reconhecidos =';
		print_r($reconhecidos);
		print '<br><br> Pilha = ';
		print_r($pilha);
		print '<br><br> Pilha Tokens= ';
		print_r($pilhaTokens);
		print '<Br>--------------------<br>';
		$i++;
	}

	


?>