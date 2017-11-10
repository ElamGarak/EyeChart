<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

return [
    'zf-rest' => [
        // @codingStandardsIgnoreStart
        // 'Name of virtual controller' => [
        //     'collection_http_methods'    => [
        //         /* array of HTTP methods that are allowed on collections */
        //         'get'
        //     ],
        //     'collection_name'            => 'Name of property denoting collection in response',
        //     'collection_query_whitelist' => [
        //         /* array of query string parameters to whitelist and return
        //          * when generating links to the collection. E.g., "sort",
        //          * "filter", etc.
        //          */
        //     ],
        //     'controller_class'           => 'Name of ZF\Rest\RestController derivative, if not using that class',
        //     'route_identifier_name'      => 'Name of parameter in route that acts as an entity identifier',
        //     'listener'                   => 'Name of service/class that acts as a listener on the composed Resource',
        //     'page_size'                  => 'Integer specifying the number of results to return per page, if collections are paginated',
        //     'page_size_param'            => 'Name of query string parameter that specifies the number of results to return per page',
        //     'entity_http_methods'      => [
        //         /* array of HTTP methods that are allowed on individual entities */
        //         'get', 'post', 'delete'
        //     ],
        //     'route_name'                 => 'Name of the route that will map to this controller',
        // ],
        // repeat for each controller you want to define
        // @codingStandardsIgnoreEnd
    ],

    'service_manager' => [
        'invokables' => [
            'ZF\Rest\RestParametersListener' => 'ZF\Rest\Listener\RestParametersListener',
        ],
        'factories' => [
            'ZF\Rest\OptionsListener' => 'ZF\Rest\Factory\OptionsListenerFactory',
        ],
    ],

    'controllers' => [
        'abstract_factories' => [
            'ZF\Rest\Factory\RestControllerFactory',
        ],
    ],

    'view_manager' => [
        // Enable this in your application configuration in order to get full
        // exception stack traces in your API-Problem responses.
        'display_exceptions' => false,
    ],
];
