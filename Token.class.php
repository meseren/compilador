
<?php

public class Token{
	protected $tipo;
	protected $simbolo;

	public function __construct($tipo, $simbolo)
	{
		$this->tipo = $tipo;
		$this->simbolo = $simbolo;
	}
}

?>