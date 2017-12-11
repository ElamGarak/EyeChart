/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

// Global Variables
var API_LOGIN_SEGMENT              = BASE_PATH + "/api/login";
var API_LOGOUT_SEGMENT             = BASE_PATH + "/api/logout";
var API_GET_SESSION_STATUS_SEGMENT = BASE_PATH + "/api/checkSessionStatus";
var API_REFRESH_SESSION_SEGMENT    = BASE_PATH + "/api/refreshSession";

var LOGIN_SEGMENT = BASE_PATH + "/login";
var INDEX_SEGMENT = BASE_PATH + "/";

var SESSION_CHECK_WARNING_THRESHOLD = 5; // Default of 5 minutes
var SESSION_CHECK_TIMEOUT_FREQUENCY = 30; // Default 30 seconds,


var JSON_HEADER = {
    "Accept":       "application/json",
    "Content-Type": "application/json"
};

var JSON_HEADER_WITH_AUTH = $.extend({}, JSON_HEADER, { "X-Authentication": TOKEN });

var NOTY_LAYOUT_CONFIG = {
    layout:  "bottomLeft",
    timeout: 5000
};
