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
	'router' => array(
		'routes' => array(
			'hanuman' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/hanuman[/:controller[/:action]]',
					'constraints' => array(
						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Hanuman\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            )
		)
	),
);