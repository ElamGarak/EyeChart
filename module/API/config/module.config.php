<?php
return array(
    'controllers' => array(
        'factories' => array(
            'API\\V1\\Rpc\\Login\\Controller' => 'API\\V1\\Rpc\\Login\\LoginControllerFactory',
            'API\\V1\\Rpc\\Logout\\Controller' => 'API\\V1\\Rpc\\Logout\\LogoutControllerFactory',
            'API\\V1\\Rpc\\CheckSessionStatus\\Controller' => 'API\\V1\\Rpc\\CheckSessionStatus\\CheckSessionStatusControllerFactory',
            'API\\V1\\Rpc\\RefreshSession\\Controller' => 'API\\V1\\Rpc\\RefreshSession\\RefreshSessionControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'api.rpc.login' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/login',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rpc\\Login\\Controller',
                        'action' => 'login',
                    ),
                ),
            ),
            'api.rpc.logout' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/logout',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rpc\\Logout\\Controller',
                        'action' => 'logout',
                    ),
                ),
            ),
            'api.rpc.edit-application-type' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/editApplicationType',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rpc\\EditApplicationType\\Controller',
                        'action' => 'editApplicationType',
                    ),
                ),
            ),
            'api.rpc.check-session-status' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/checkSessionStatus',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rpc\\CheckSessionStatus\\Controller',
                        'action' => 'checkSessionStatus',
                    ),
                ),
            ),
            'api.rpc.refresh-session' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/refreshSession',
                    'defaults' => array(
                        'controller' => 'API\\V1\\Rpc\\RefreshSession\\Controller',
                        'action' => 'refreshSession',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'api.rpc.login',
            1 => 'api.rpc.logout',
            2 => 'api.rpc.check-session-status',
            3 => 'api.rpc.refresh-session',
        ),
    ),
    'zf-rpc' => array(
        'API\\V1\\Rpc\\Login\\Controller' => array(
            'service_name' => 'Login',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.login',
        ),
        'API\\V1\\Rpc\\Logout\\Controller' => array(
            'service_name' => 'Logout',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.logout',
        ),
        'API\\V1\\Rpc\\CheckSessionStatus\\Controller' => array(
            'service_name' => 'CheckSessionStatus',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.check-session-status',
        ),
        'API\\V1\\Rpc\\RefreshSession\\Controller' => array(
            'service_name' => 'RefreshSession',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.refresh-session',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'API\\V1\\Rpc\\Login\\Controller' => 'Json',
            'API\\V1\\Rpc\\Logout\\Controller' => 'Json',
            'API\\V1\\Rpc\\CheckSessionStatus\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'API\\V1\\Rpc\\Login\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'API\\V1\\Rpc\\Logout\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'API\\V1\\Rpc\\CheckSessionStatus\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'API\\V1\\Rpc\\RefreshSession\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'API\\V1\\Rpc\\Login\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'API\\V1\\Rpc\\Logout\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'API\\V1\\Rpc\\RefreshSession\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'API\\V1\\Rpc\\GetFuelCard\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(),
    ),
    'zf-content-validation' => array(
        'API\\V1\\Rpc\\CheckSessionStatus\\Controller' => array(
            'input_filter' => 'API\\V1\\Rpc\\CheckSessionStatus\\Validator',
        ),
        'API\\V1\\Rpc\\Login\\Controller' => array(
            'input_filter' => 'API\\V1\\Rpc\\Login\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'API\\V1\\Rpc\\CheckSessionStatus\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'token',
                'field_type' => 'string',
                'allow_empty' => true,
                'continue_if_empty' => true,
            ),
        ),
        'API\\V1\\Rpc\\Login\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'username',
                'field_type' => 'string',
                'error_message' => 'Username was not provided',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'password',
                'error_message' => 'Password was not provided',
            ),
        ),
    ),
);
