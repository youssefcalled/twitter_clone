<?php

namespace App;

class Connection{

	//Método responsável por criar a Conexão com o banco de dados
	public static function getDb(){

		try{

			$conn = new \PDO(
				"mysql:host=localhost;dbname=twitter_clone;charset=utf8","root",""
			);

			return $conn;

		}catch(\PDOException $e){
			//tratar o erro de alguma forma
			echo '<p>'.$e->getMessage().'</p>';
		}

	}
}


?>