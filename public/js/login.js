/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

function Login() {
    "use strict";

    var messages = $("#messages");

    this.initialize = function() {
        enableBindings();
        displayIncomingMessages();
    };

    function enableBindings() {
        $("#username").focus();

        bindPasswordKeyPress();
        bindSubmit();
    }

    function bindPasswordKeyPress() {
        $('#password').keypress(function (e) {
            if (e.which === 13) {
                $('#login').click();

                return false;
            }
        });
    }

    function bindSubmit() {
        var loginForm = $("form#login");
        loginForm.parsley();

        $("#submit").on("click", function() {
            messages.addClass("hidden");
            messages.html('');

            loginForm.parsley().validate();

            if (!loginForm.parsley().isValid()) {
                $.openLoaderModal();
                messages.removeClass("hidden");

                return
            }

            messages.addClass("hidden");

            ajaxLogin();
        });
    }

    function ajaxLogin() {
        var params = {
            username: $("#username").val(),
            password: $("#password").val()
        };

        $.openLoaderModal(true);

        $.ajax({
            headers:  JSON_HEADER,
            url:      API_LOGIN_SEGMENT,
            type:     "POST",
            dataType: "json",
            data:     JSON.stringify(params),
            success:  function(data) {
                if (data.token) {
                    $.redirect(INDEX_SEGMENT, {"token" : data.token }, "POST");

                    return true;
                }

                $.openLoaderModal();
                setMessageContainer(data.messages);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $.openLoaderModal();
                setMessageContainer({
                    error: [ errorThrown ]
                });
            }
        });
    }

    function displayIncomingMessages() {
        if (MESSAGES === undefined) {
            return;
        }

        if (MESSAGES.length > 0) {
            setMessageContainer($.parseJSON(MESSAGES));
        }
    }

    /**
     *
     * @param {json} incomingMessages
     */
    function setMessageContainer(incomingMessages) {
        if (incomingMessages && typeof incomingMessages === "object") {
            var container = $('<div></div>');

            $.each(incomingMessages, function (index, value) {
                var p = $('<p>').text(value);
                container.append(p);
            });

            messages.html(container.html());
            messages.removeClass("hidden");
        }
    }
}

$(function() {
    "use strict";

    var login = new Login();
    login.initialize();
});
