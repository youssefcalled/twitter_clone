<?php

namespace App;

use MF\Init\Bootstrap;


class Route extends Bootstrap{
	
	//Método que configura as rotas do projeto
	protected function initRoutes(){
		
		//Exemplo de uma rota configurada, a rota recebe um array, com o caminho da rota, o controller responsável pela rota e a action a ser realizada
		$routes['home'] = array
		('route' => '/',
			'controller' =>'IndexController',
			'action' => 'index'
		);

		$routes['inscreverse'] = array
		('route' => '/inscreverse',
			'controller' =>'IndexController',
			'action' => 'inscreverse'
		);

		$routes['registrar'] = array
		('route' => '/registrar',
			'controller' =>'IndexController',
			'action' => 'registrar'
		);

		$routes['autenticar'] = array
		('route' => '/autenticar',
			'controller' =>'AuthController',
			'action' => 'autenticar'
		);

		$routes['timeline'] = array
		('route' => '/timeline',
			'controller' =>'AppController',
			'action' => 'timeline'
		);

		$routes['sair'] = array
		('route' => '/sair',
			'controller' =>'AuthController',
			'action' => 'sair'
		);

		$routes['tweet'] = array
		('route' => '/tweet',
			'controller' =>'AppController',
			'action' => 'tweet'
		);

		$routes['quem_seguir'] = array
		('route' => '/quem_seguir',
			'controller' =>'AppController',
			'action' => 'quemSeguir'
		);

		$routes['acao'] = array
		('route' => '/acao',
			'controller' =>'AppController',
			'action' => 'acao'
		);

		$routes['remover_tweet'] = array
		('route' => '/remover_tweet',
			'controller' =>'AppController',
			'action' => 'removerTweet'
		);

		$this->setRoutes($routes);

	}

	
}

?>