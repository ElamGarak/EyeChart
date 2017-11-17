/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

function Login() {
    "use strict";

    var messageDisplay = $("#message");

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
            messageDisplay.addClass('hidden');
            loginForm.parsley().validate();

            if (!loginForm.parsley().isValid()) {
                messageDisplay.removeClass('hidden');

                return
            }

            ajaxLogin();
        });
    }

    function ajaxLogin() {
        var params = {
            username: $("#username").val(),
            password: $("#password").val()
        };

        $.ajax({
            headers:  JSON_HEADER,
            url:      API_LOGIN_SEGMENT,
            type:     "POST",
            dataType: "json",
            data:     JSON.stringify(params),
            success:  function(data) {
                if (data.success === true) {
                    $.redirect(INDEX_SEGMENT, {"token" : data.token }, "POST");

                    return true;
                }

                setMessageContainer(data.messages);
            },
            error: function(jqXHR, textStatus, errorThrown) {
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
     * @param {json} messages
     */
    function setMessageContainer(messages) {
        if (typeof messages === "object") {
            var container = $('<div></div>');

            $.each(messages, function (index, value) {
                var p = $('<p>').text(value);
                container.append(p);
            });

            messageDisplay.html(container.html());
            messageDisplay.removeClass("hidden");
        }
    }
}

$(function() {
    "use strict";

    var login = new Login();
    login.initialize();
});
