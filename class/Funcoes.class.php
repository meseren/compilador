
<?php

class Funcoes
{

	protected $letrasPermitidas = array('a', 'b', 'd','c','e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
	protected $digitosPermitidos = array('0','1','2','3','4','5','6','7','8', '9', '10', '11', '12', '13', '14');
	protected $simbolosEspeciais = array('{','}','(',')',';',',','+','-','/','*','=','<','>','!','%', '#', '"');
	protected $operadoresAritmeticos = array('+', '*', '/', '-');
	protected $simbolosCompostos = array('+=', '-=', '/=', '++', '--', '*=', '<=', '>=');
	protected $palavrasReservadas = array('int', 'main', 'float', 'double', 'if', 'else', 'for', 'while', 'do', 'printf', 'scanf');
	protected $simbolosTerminais = array('id', 'num', '+', '-', '/', '*', '(', ')', '$');
	protected $simbolosRegras = array('E', 'S', 'G', 'T', 'F');

	public function verificaOperador($token) 
	{
		switch ($token) {
			case '+':
				return 'SOMA';

			case '-':
				return 'SUBT';

			case '*':
				return 'MULT';

			case '/':
				return 'DIVI';

			case '==':
				return 'CMIG';

			case '!=':
				return 'CMDG';

			case '<=':
				return 'CMEG';

			case '>=':
				return 'CMAG';

			case '>':
				return 'CMMA';

			case '<':
				return 'CMME';

			default:
				return false;
		}

		return false;
	}

	public function verificaLetraPermitida($token) 
	{
		if(in_array($token, $this->letrasPermitidas))
			return true;

		return false;
	}

	public function verificaNumeroPermitida($token) 
	{
		if(in_array($token, $this->digitosPermitidos))
			return true;

		return false;
	}

	public function verificaSimboloTerminal($token) 
	{
		if(in_array($token, $this->simbolosTerminais))
			return true;

		return false;
	}

	public function verificaPalavraReservada($token)
	{
		if(in_array($token, $this->palavrasReservadas))
			return true;

		return false;
	}

	public function verificaValidadeCaractere($char)
	{
		if(in_array($char, $this->letrasPermitidas))
			return true;
		else if(in_array($char, $this->digitosPermitidos))
			return true;
		else if (in_array($char, $this->simbolosEspeciais))
			return true;

		return false;
	}

	public function verificaSimboloEspecial($token)
	{
		if(in_array($token, $this->simbolosEspeciais))
			return true;

		return false;
	}

	public function verificaSimboloComposto($token)
	{
		if(in_array($token, $this->simbolosCompostos))
			return true;

		return false;
	}

}

?>