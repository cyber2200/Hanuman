<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Hanuman\Controller\Index' => 'Hanuman\Controller\IndexController', 
			'Hanuman\Controller\Modules' => 'Hanuman\Controller\ModulesController', 
            'index' => 'Hanuman\Controller\IndexController',
        	'Hanuman\Controller\Cms' => 'Hanuman\Controller\CmsController',
			'Hanuman\Controller\Config' => 'Hanuman\Controller\ConfigController',
		),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'hanuman' => __DIR__ . '/../view',
        ),
		'template_map' => array(
			'layout/hanuman' => __DIR__ . '/../view/layout/layout.phtml',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
    ),
	'router' => array(
		'routes' => array(
			'hanuman' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/Hanuman[/:controller[/:action[/:param1]]]',
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
                            'route'    => '/[:controller[/:action[/:param1]]]',
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