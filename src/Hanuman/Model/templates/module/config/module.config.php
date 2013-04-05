<?php
return array(
    'controllers' => array(
        'invokables' => array(
            '##MOUDLE_NAME##\Controller\Index' => '##MOUDLE_NAME##\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            '##MOUDLE_NAME##' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/##MOUDLE_NAME##',
                    'defaults' => array(
                        '__NAMESPACE__' => '##MOUDLE_NAME##\Controller',
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
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            '##MOUDLE_NAME##' => __DIR__ . '/../view',
        ),
    ),
);
