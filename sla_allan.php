
<?php

public class Token{
	protected $tipo;
	protected $simbolo;
	protected $letrasPermitidas = array('a', 'b', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
	protected $digitosPermitidos = array('1','2','3','4','5','6','7','8', '9');
	protected $simbolosEspeciais = array('{','}','(',')',';',',','+','-','/','*','=','<','>','!','%', '#');
	protected $palavrasReservadas = array('int', 'float', 'double', 'if', 'while', 'switch', 'boolean', 'char', 'for', 'void', 'return', 'function', 'struct', 'else', 'case', 'short', 'continue', 'break', 'main');
	

	public function __construct($tipo, $simbolo)
	{
		$this->tipo = $tipo;
		$this->simbolo = $simbolo;
	}
}

?>