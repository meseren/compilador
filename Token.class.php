
<?php

class Token{
	public $tipo;
	public $simbolo;

	public function __construct($tipo, $simbolo)
	{
		$this->tipo = $tipo;
		$this->simbolo = $simbolo;
	}

	public function getTipo()
	{
		return $this->tipo;
	}

	public function getSimbolo()
	{
		return $this->simbolo;
	}
}

?>