<?php
use Zend\Session;
use Zend\Session\Storage\SessionArrayStorage;

/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c] 2014-2016 Zend Technologies USA Inc. (http://www.zend.com]
 */

return [
    'applicationTitle' => 'Eye Chart',
    'zf-content-negotiation' => [
        'selectors' => [],
    ],
    'db' => [],
    'emailModel' => [
        'options' => [
            'name' => '',
            'host' => '',
            'port' => '25'
        ],
        'emails' => [
            'noReply' => '',
            'from'    => ''
        ],
    ],
    'environment' => [
        'emailOverride' => [
            'enabled'   => false,
            'recipient' => []
        ],
        'timeoutWarningThreshold' => 5, // Minutes
        'activeSessionCheck'      => true,
    ],
    'noTokenRequired' => [
        'api.rpc.login' => 'Login API Controller',
        'zf-apigility/api/module/rpc-service' => 'Apigility RPC update',
    ],
    // Session configuration
    'sessionConfig' => [
        'cookieLifetime' => 60*60*1,
        'gcMaxlifetime'  => 60*60*12,
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'session' => [
        'config' => [
            'class' => Session\Config\SessionConfig::class,
            'options' => [
                'name' => 'EyeChart',
            ],
        ],
        'storage' => Session\Storage\SessionArrayStorage::class,
        'validators' => [
            Session\Validator\RemoteAddr::class,
            Session\Validator\HttpUserAgent::class,
        ],
    ],
];
