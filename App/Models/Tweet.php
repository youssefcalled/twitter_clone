<?php

//O namespace é constituido pelos diretórios onde o script está
namespace App\Models;

use MF\Model\Model;

class Tweet extends Model{

	private $id;
	private $id_usuario;
	private $tweet;
	private $data;

	public function __get($atributo){
		return $this->$atributo;
	}

	public function __set($atributo,$valor){
		$this->$atributo = $valor;
	}

	//Salva Tweets
	public function salvar(){

		$query = 'insert into tweets(id_usuario,tweet) values(?,?)';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('id_usuario'));
		$stmt->bindValue(2,$this->__get('tweet'));

		$stmt->execute();

		return $this;
	}


	//Recupera Tweets
	public function getAll(){

		$query = "
		select 
		t.id,
		t.id_usuario, 
		u.nome, t.tweet, 
		DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
		from 
		tweets as t 
		left join usuarios as u on (t.id_usuario = u.id)
		where 
		t.id_usuario = ?
		or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = ?)
		order by 
		t.data desc

		";

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('id_usuario'));
		$stmt->bindValue(2,$this->__get('id_usuario'));

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);


	}

	//Remove um tweet do usuário
	public function removerTweetUsuario(){

		$query = 'delete from tweets where id_usuario = ? and id = ?';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('id_usuario'));
		$stmt->bindValue(2,$this->__get('id'));

		$stmt->execute();

		return true;
	}

}

?>