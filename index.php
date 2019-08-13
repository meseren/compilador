<html>
	<?php
		if(!isset($_GET)){
	?>	
			<form action="log_analise_lexica.php" method="POST">
				
				<textarea name="codigo" id="codigo"></textarea>

				<button type="submit" id="enviar"> Enviar</button>
			</form>
	<?
		}else{
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
		}
	?>	

</html>