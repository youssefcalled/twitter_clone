<?php

//O namespace é constituido pelos diretórios onde o script está
namespace App\Models;

use MF\Model\Model;

class Usuario extends Model{

	private $id;
	private $nome;
	private $email;
	private $senha;

	public function __get($atributo){
		return $this->$atributo;
	}

	public function __set($atributo,$valor){

		$this->$atributo = $valor;

	}


	//Salvar novos usuários
	public function salvar(){

		$query = 'insert into usuarios(nome,email,senha) values(?,?,?)';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('nome'));
		$stmt->bindValue(2,$this->__get('email'));
		$stmt->bindValue(3,$this->__get('senha')); //md5() -> hash de 32 caracteres

		$stmt->execute();

		return $this;

	}



	//Validar se um cadastro pode ser feito
	public function validarCadastro(){
		
		$valido = true;

		if(strlen($this->__get('nome')) <= 3){
			$valido = false;
		}
		if(strlen($this->__get('email')) <= 3){
			$valido = false;
		}
		if(strlen($this->__get('senha')) <= 3){
			$valido = false;
		}

		return $valido;

	}



	//Recuperar um usuário por e-mail
	public function getUsuarioPorEmail(){
		$query = 'select nome, email from usuarios where email = ?';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('email'));

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}

	//Método para autenticar Usuário
	public function autenticar(){

		$query = 'select id,nome,email from usuarios where email = ? and senha = ?';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('email'));
		$stmt->bindValue(2,$this->__get('senha'));

		$stmt->execute();

		//Pegar o primeiro registro, o que é esperado, apenas um único registro
		$usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

		//Se o usuário existir, $usuario['id'] e usuario['nome'] não serão vazios e os atributos da Classe Usuario id e nome serão setados com esses valores
		if($usuario['id'] != '' && $usuario['nome'] != ''){

			$this->__set('id',$usuario['id']);
			$this->__set('nome',$usuario['nome']);

		}

	//Retorna o próprio objeto
		return $this;
		
	}


	//Método que pesquisa usuários
	public function getAll(){

		$query = 'select u.id,u.nome,u.email,
		(
		select 
		count(*) 
		from 
		usuarios_seguidores as us 
		where 
		us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
		) as seguindo_sn
		from 
		usuarios as u
		where 
		u.nome like :nome and id != :id_usuario';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':nome','%'.$this->__get('nome').'%');
		$stmt->bindValue(':id_usuario',$this->__get('id'));

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function seguirUsuario($id_usuario_seguindo){

		$query = 'insert into usuarios_seguidores(id_usuario,id_usuario_seguindo) values(?,?)';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('id'));

		$stmt->bindValue(2,$id_usuario_seguindo);

		$stmt->execute();

		return true;
	}

	public function deixarSeguirUsuario($id_usuario_seguindo){

		$query = 'delete from usuarios_seguidores where id_usuario = ? and id_usuario_seguindo = ?';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('id'));

		$stmt->bindValue(2,$id_usuario_seguindo);

		$stmt->execute();

		return true;

	}

	//Informações do Usuário
	public function getInfoUsuario(){
		$query = 'select nome from usuarios where id = ?';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('id'));

		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);

	}

	//Total de Tweets
	public function getTotalTweets(){

		$query = 'select count(*) as total_tweets from tweets where id_usuario = ?';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('id'));

		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	//Total de Usuários que estamos seguindo
	public function getTotalSeguindo(){

		$query = 'select count(*) as total_seguindo from usuarios_seguidores where id_usuario = ?';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('id'));

		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	//Total de Seguidores
	public function getTotalSeguidores(){

		$query = 'select count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = ?';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(1,$this->__get('id'));

		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}


}

?>