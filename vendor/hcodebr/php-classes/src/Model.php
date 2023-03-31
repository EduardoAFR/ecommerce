<?php

namespace Hcode; 

class Model {

	private $values = []; 

	public function __call($name,$args) //Recebe o metodo inserido (get ou set) e o nome do campo que é o alvo da operação. 
	{
		$method = substr($name,0,3);
		$fieldName = substr($name,3,strlen($name));

		var_dump($method,$fieldName); 

		switch($method)
		{
			case "get":
				return $this->values[$fieldName]; 
			break; 

			case "set":
				$this->values[$fieldName] = $args[0];
			break;
		}
	}

public function setData($data = array()){ //Recebe o array data com todos os parametros vindos do banco de dados e usa um foreach para criar os respectivos atributos e atribui-los (set). 

	foreach($data as $key => $value){

		$this->{"set".$key}($value); 
	}

}

public function getValues()
{

	return $this->values; 

}

}
?>