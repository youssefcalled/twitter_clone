<?php

namespace App\Controllers;

//Recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action{

	public function timeline(){

		$this->validaAutenticacao();

		//Recuperação dos tweets
		$tweet = Container::getModel('Tweet');

		$tweet->__set('id_usuario',$_SESSION['id']);

		$tweets = $tweet->getAll();

		$this->view->tweets = $tweets;

		$usuario = Container::getModel('Usuario');

		$usuario->__set('id',$_SESSION['id']);

		$this->view->info_usuario = $usuario->getInfoUsuario();
		$this->view->total_tweets = $usuario->getTotalTweets();
		$this->view->total_seguindo = $usuario->getTotalSeguindo();
		$this->view->total_seguidores = $usuario->getTotalSeguidores();

		$this->render('timeline');
		
	}

	public function tweet(){

		$this->validaAutenticacao();

		$tweet = Container::getModel('Tweet');

		$tweet->__set('tweet',$_POST['tweet']);
		$tweet->__set('id_usuario',$_SESSION['id']);

		$tweet->salvar();

		header('Location: /timeline');

		
	}

	public function validaAutenticacao(){

		session_start();
		
		if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
			
			header('Location: /?login=erro');
		}

		
	}

	//Action que mostra pesquisa de outras pessoas para seguir
	public function quemSeguir(){
		$this->validaAutenticacao();

		$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);

		$this->view->info_usuario = $usuario->getInfoUsuario();
		$this->view->total_tweets = $usuario->getTotalTweets();
		$this->view->total_seguindo = $usuario->getTotalSeguindo();
		$this->view->total_seguidores = $usuario->getTotalSeguidores();


		$usuarios = array();

		if(!empty($pesquisarPor)){
			
			$usuario->__set('nome',$pesquisarPor);
			$usuarios = $usuario->getAll();
		}

		$this->view->usuarios = $usuarios;

		$this->render('quemSeguir');
	}

	//Action que executa o método de seguir ou deixar de seguir um usuário
	public function acao(){

		$this->validaAutenticacao();

		$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
		$id_usuario_seguindo = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : '';


		$usuario = Container::getModel('Usuario');

		$usuario->__set('id',$_SESSION['id']);

		if($acao == 'seguir'){

			$usuario->seguirUsuario($id_usuario_seguindo);

		}elseif ($acao == 'deixarSeguir') {
			$usuario->deixarSeguirUsuario($id_usuario_seguindo);
		}

		header('Location: /quem_seguir');

	}

	//Remover tweets do usuário
	public function removerTweet(){

		$this->validaAutenticacao();

		$tweet = Container::getModel('Tweet');

		$idTweet = isset($_POST['idTweet']) ? $_POST['idTweet'] : '';

		$tweet->__set('id_usuario',$_SESSION['id']);
		$tweet->__set('id',$idTweet);
		
		$tweet->removerTweetUsuario();

		header('Location: /timeline');
		

	}


}

?>