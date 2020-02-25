<?php

namespace MF\Init;

abstract class Bootstrap{
	private $routes;

	abstract protected function initRoutes();

	public function __construct(){
		$this->initRoutes();
		$this->run($this->getUrl());
	}

	public function getRoutes(){
		return $this->routes;
	}

	public function setRoutes(array $routes){
		$this->routes = $routes;
	}


	//Método para criar e executar dinamicamente  o objeto(Controller) e método(action)
	protected function run($url){

		//Percorre o array de Rotas para verificar se existe alguma rota válida
		foreach ($this->getRoutes() as $key => $route) {
			
			//Verifica de existe uma rota válida pela url recebida
			if($url == $route['route']){

				//Se existir, cria uma classe dinâmica do Controller Responsável por essa rota
				$class = "App\\Controllers\\".$route['controller'];

				//Instância a classe criada dinamicamente, é a mesma coisa do que criar $controller = new App\Controllers\IndexController.php
				$controller = new $class;

				//Pega a action no array
				$action = $route['action'];

				//Cria o método da action dinamicamente também
				$controller->$action();
			}
		}
	}

	protected function getUrl(){

		//Função para retornar os componentes de uma url em um array
		//Constante PHP_URL_PATH faz com que o retorno seja apenas a string relacionada ao path
		return parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
	}
}


?>