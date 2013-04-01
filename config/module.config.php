<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Hanuman\Controller\Index' => 'Hanuman\Controller\IndexController', 
			'Hanuman\Controller\Modules' => 'Hanuman\Controller\ModulesController', 
            'index' => 'Hanuman\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'hanuman' => __DIR__ . '/../view',
        ),
		'template_map' => array(
			'layout/hanuman' => __DIR__ . '/../view/layout/layout.phtml',
		)
    ),
);