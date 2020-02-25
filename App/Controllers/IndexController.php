<?php

namespace App\Controllers;

//Recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action{

	public function index(){

		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';

		$this->render('index','layout');

	}

	public function inscreverse(){
		//Se acessarmos a rota direto, setamos como vazio os parâmetros passados quando tentamos registrar e recuperamos os valores do campo, essa é um das formas de fazer, mas optei por fazer a checagem na view 
		/*$this->view->usuario = array(
				'nome' => '',
				'email' => '',
				'senha' => ''
			);*/

			$this->view->erroCadastro = false;
			$this->render('inscreverse');
		}

		public function registrar(){
			
    //Receber os dados do Formulário
			

			$usuario = Container::getModel('Usuario');

			$usuario->__set('nome',$_POST['nome']);
			$usuario->__set('email',$_POST['email']);
			$usuario->__set('senha',$_POST['senha']);
			
		/*echo '<pre>';
		print_r($usuario);
		echo '</pre>';*/

		//Verifica se o método validarCadastro retorna true e Verifica se o e-mail recebido no cadastro já foi cadastrado antes
		if($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0){

			$usuario->__set('senha',md5($_POST['senha']));
			$usuario->salvar();

			$this->render('cadastro');
			
		//Erro
		}else{

			$this->view->usuario = array(
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha']
			);

			$this->view->erroCadastro = true;

			$this->render('inscreverse');
		}
		
		
	}

	

}


?>