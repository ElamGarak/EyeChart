<?php

namespace EyeChart;

use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\Index\IndexController::class,
                        'action' => 'index'
                    ],
                    'tokenRequired' => true
                ]
            ],
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                    'tokenRequired' => false,
                ],
            ],
        ]
    ],
    'service_manager' => [
        'factories' => [
            // Adapters
            Service\Authenticate\AuthenticateAdapter::class                  => Service\Authenticate\AuthenticateAdapterFactory::class,

            // Listener
            Service\Authenticate\AuthenticateListener::class                => Service\Authenticate\AuthenticateListenerFactory::class,

            // Handlers
            Command\Handlers\Authenticate\AuthenticateHandler::class        => Command\Handlers\Authenticate\AuthenticateHandlerFactory::class,
            Command\Handlers\Email\EmailHandler::class                      => Command\Handlers\Email\EmailHandlerFactory::class,
            Command\Handlers\Session\SessionRefreshHandler::class           => Command\Handlers\Session\SessionRefreshHandlerFactory::class,

            // Models
            Model\Authenticate\AuthenticateModel::class                     => Model\Authenticate\AuthenticateModelFactory::class,
            Model\Authenticate\AuthenticateStorageModel::class              => Model\Authenticate\AuthenticateStorageModelFactory::class,
            Model\Authenticate\EncryptionModel::class                       => Model\Authenticate\EncryptionModelFactory::class,
            Model\Email\EmailModel::class                                   => Model\Email\EmailModelFactory::class,
            Model\Employee\EmployeeModel::class                             => Model\Employee\EmployeeModelFactory::class,

            // Repositories
            Repository\Authentication\AuthenticationRepository::class       => Repository\Authentication\AuthenticationRepositoryFactory::class,

            // Services
            Service\Authenticate\AuthenticateService::class                 => Service\Authenticate\AuthenticateServiceFactory::class,
            Service\Authenticate\AuthenticateStorageService::class          => Service\Authenticate\AuthenticateStorageServiceFactory::class,
            Service\Email\EmailService::class                               => Service\Email\EmailServiceFactory::class,

            // Entities
            Entity\AuthenticateEntity::class                                => InvokableFactory::class,
            Entity\Email\EmailEntity::class                                 => InvokableFactory::class,
            Entity\SessionEntity::class                                     => InvokableFactory::class,
            Entity\EmployeeEntity::class                                    => InvokableFactory::class,

            // DAOs
            DAO\Authenticate\AuthenticateDAO::class                         => DAO\Authenticate\AuthenticateDAOFactory::class,
            DAO\Authenticate\AuthenticateStorageDAO::class                  => DAO\Authenticate\AuthenticateStorageDAOFactory::class,
            DAO\Employee\EmployeeDao::class                                 => DAO\Employee\EmployeeDaoFactory::class,

        ],
        'aliases' => [
            'Zend\Session\Config\ConfigInterface'  => 'Zend\Session\Service\SessionConfigFactory',
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\Index\IndexController::class => Controller\Index\IndexControllerFactory::class,
            Controller\LoginController::class       => InvokableFactory::class
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/eye-chart/index/index.phtml',
            'eyechart/index/index' => __DIR__ . '/../view/eye-chart/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml'
        ],
        'template_path_stack' => [
            'eyechart' => __DIR__ . '/../view'
        ]
    ],
    'tactician' => [
        'handler-map' => [
            Command\Commands\AuthenticateCommand::class                     => Command\Handlers\Authenticate\AuthenticateHandler::class,
            Command\Commands\EmailCommand::class                            => Command\Handlers\Email\EmailHandler::class,
            Command\Commands\SessionRefreshCommand::class                   => Command\Handlers\Session\SessionRefreshHandler::class
        ],
    ],
];
