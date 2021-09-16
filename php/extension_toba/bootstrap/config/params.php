<?php

return [
 		"assets" => [
	 				"css" => [
		 						'bootstrap/css/bootstrap.min.css',
		 						'font-awesome/css/font-awesome.min.css',
		 						'../bt-assets/css/generic.css',
		 						'../bt-assets/css/global.css',
		 						'../bt-assets/css/kolla.css',
	 							'../bt-assets/css/skin_custom.css'
	 						],
	 				"js" => [ 
	 							'bt-assets/js/bt_notificacion.js',
	 							'bt-assets/js/basico.js',
	 							'bt-assets/js/buscador.js'
	 				]
 		],
		"menu"	=> [
					"inicio" => [
								"mostrar" => false,
								"id"	=>	2
							],
					"salir"	=> [
								"mostrar"	=> true,
								"id"=> 38000148
					],
					"ayuda"	=> [
								"mostrar"	=> true,
								"id"	=>  40000116
					]
		],
		"logos" => [
					 "espera" => "logo-kolla-instalador.png"
		]
 ];