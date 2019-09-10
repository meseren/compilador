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
    //Regras
    $tabela[1][0]= "E";
    $tabela[2][0]= "T";
    $tabela[3][0]= "S";
    $tabela[4][0]= "F";
    $tabela[5][0]= "G";

    //Tokens
    $tabela[0][1]= "id";
    $tabela[0][2]= "num";
    $tabela[0][3]= "+";
    $tabela[0][4]= "-";
    $tabela[0][5]= "*";
    $tabela[0][6]= "/";
    $tabela[0][7]= "(";
    $tabela[0][8]= ")";
    $tabela[0][9]= "$";

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
	$tokens[2] = "id";
	$tokens[3] = "$";

	$pilha = array(0 => '$', 1 => 'E');
	$i = 0;
	
	foreach ($tokens as $k => $value) {
		if( isset($tabela[$pilha[count($pilha)-1]][$value])){
			$producao = strrev($tabela[$pilha[count($pilha)-1]][$value]);
			array_pop($pilha);
	
			for ($j=0; $j < strlen($producao); $j++)
				array_push($pilha, $producao[$j]);
		}
	}

	print_r($pilha)
	


?>