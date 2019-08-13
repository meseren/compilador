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
			}else{

				$token .= $codigo[$key];
			}

			#Segunda Validação
			if($Funcoes->verificaPalavraReservada($token))
			{
				if($codigo[$key+1] == ' ' || $codigo[$key+1] == '(')
				{
					$Tokens[] = new Token('palavra_reservada', $token);
					$token = '';
				}
				
			}else{

				if(($codigo[$key+1] == ' ' || $codigo[$key+1] == '=') && !$Funcoes->verificaSimboloEspecial($codigo[$key]))
				{
					$Tokens[] = new Token('identificador', $token);
					$token = '';
				}
				else if($Funcoes->verificaSimboloComposto($codigo[$key].$codigo[$key+1]))
				{
					$Tokens[] = new Token('simbolo_composto', $codigo[$key].$codigo[$key+1]); 
					$key++;
					$token = '';
				}
				else if($Funcoes->verificaSimboloEspecial($codigo[$key]))
				{
					$Tokens[] = new Token('simbolo_especial', $codigo[$key]);
					$token = '';
				}else{
					$caracteresInvalidos[] = $token;
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