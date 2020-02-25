<?php

namespace MF\Controller;

abstract class Action{
	
	protected $view;


	public function __construct(){

		$this->view = new \stdClass();
	}

	protected function render($view,$layout = 'layout'){
		
		$this->view->page = $view;

		//Se o layout existir, retorna o conteúdo da view dentro do layout
		if(file_exists("../App/Views/".$layout.".phtml")){

			require_once "../App/Views/".$layout.".phtml";

		//Se não existir, retorna apenas o conteudo da view
		}else{
			$this->content();
		}
		
	}

	protected function content(){
		//Pega o caminho da classe com o nome do controller
		$classeAtual = get_class($this); 
		//Se encontrar o caminho App\\Controllers\\, substitui para vazio, para ficar somente o nome do Controller Selecionado
		$classeAtual = str_replace('App\\Controllers\\', '', $classeAtual);

		//Pega somente o nome da pasta que contem as actions do Controller requisitado
		$classeAtual = strtolower(str_replace('Controller', '', $classeAtual));

		//echo $classeAtual;

		require_once "../App/Views/".$classeAtual."/".$this->view->page.".phtml";
	}

}

?>