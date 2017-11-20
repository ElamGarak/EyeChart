/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c)
 */

function Logout() {
    "use strict";

    this.initialize = function() {
        bindLogoutElements();
    };

    function bindLogoutElements() {
        $(".logout").on('click', function () {
            callLogoutApi();
        })
    }

    function callLogoutApi() {
        var params = {
            token: TOKEN
        };

        $.openLoaderModal(true);

        $.ajax({
            headers:  JSON_HEADER_WITH_AUTH,
            url:      API_LOGOUT_SEGMENT,
            type:     "POST",
            dataType: "json",
            data:     JSON.stringify(params),
            success: function (results) {
                $.redirect(LOGIN_SEGMENT, { "messages" : results.messages }, "POST");
            },
            error:  function (jqXHR, textStatus, errorThrown) {
                $.redirect(LOGIN_SEGMENT, { "message" : [ errorThrown ] }, "POST");
            }
        });
    }
}

$(function() {
    "use strict";

    var logout =  new Logout();
    logout.initialize();
});


