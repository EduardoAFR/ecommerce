<?php

namespace Hcode\Model; 

use \Hcode\DB\Sql; 
use \Hcode\Model;
use \Hcode\Mailer;
//use \Hcode\Product;
use \Hcode\Model\User; 

class Cart extends Model {

	const SESSION = "Cart"; //Enquanto a sessão estiver ativa, sabemos que o carrinho é o mesmo. 

public static function getFromSession()
{

	$cart = new Cart(); 

	if (isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart'] >0) {

		$cart->get((int)$_SESSION[Cart::SESSION]['idcart']); 
	} else {

		$cart->getFromSessionID(); 

		if(!(int)$cart->getidcart()>0) {
			$data = array(
				"dessessionid"=>session_id()
			);

			

			if(User::checkLogin(false) === true){
				$user = User::getFromSession(); 
			
				$data['iduser'] = $user->getiduser(); 
			}

			$cart->setData($data); 

			//var_dump($cart->getValues()); 
			//exit;

			$cart->save(); 

			$cart->setToSession();
		}

	}

	return $cart;

}

public function setToSession()
{

	$_SESSION[Cart::SESSION] = $this->getValues(); 

}

public function getFromSessionID()
{

	$sql = new Sql();

	$results = $sql->select("SELECT * FROM tb_carts WHERE dessessionid = :dessessionid",[
		':dessessionid'=>session_id() 
	]);

	if(count($results) > 0 ){
	$this->setData($results[0]); 
	}

}


public function get($idcart)
{

	$sql = new Sql();

	$results = $sql->select("SELECT * FROM tb_carts WHERE idcart = :idcart",[
		':idcart'=>$idcart 
	]);

	if(count($results) > 0 ){
	$this->setData($results[0]); 
	}
}

public function save()
{

	$sql = new Sql();

	$results = $sql->select("CALL sp_carts_save(:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)",[
		':idcart'=>$this->getidcart(), 
		':dessessionid'=>$this->getdessessionid(),
		':iduser'=>$this->getiduser(),
		':deszipcode'=>$this->getdeszipcode(),
		':vlfreight'=>$this->getvlfreight(),
		':nrdays'=>$this->getnrdays()
	]);

	$this->setData($results[0]); 

}
	

}


?>