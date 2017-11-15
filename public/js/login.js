/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

function Login() {
    "use strict";

    var loaderModal,
        loginErrorContainer;

    this.initialize = function() {
        loginErrorContainer = $("#loginError");

        enableBindings();
        bindModal();
        displayIncomingMessages();
    };

    function enableBindings() {
        $("#username").focus();

        registerPasswordKeypress();
        loginClick();
    }

    function bindModal() {
        loaderModal = $("#loaderModal");
        loaderModal.removeClass("hidden");
        loaderModal.easyModal(EASY_MODAL_CONFIG);
    }

    function registerPasswordKeypress() {
        $('#password').keypress(function (e) {
            if (e.which === 13) {
                $('#login').click();

                return false;
            }
        });
    }

    function loginClick() {
        var loginForm = $("#login-form");

        $("#login").on("click", function() {
            loginForm.parsley();
            if (loginForm.parsley().validate() && loginForm.parsley().isValid()) {
                ajaxLogin();
            }
        });
    }

    function ajaxLogin() {
        var params = {
            username: $("#username").val(),
            password: $("#password").val()
        };

        loaderModal.trigger("openModal");

        $.ajax({
            headers:  JSON_HEADER,
            url:      API_LOGIN_SEGMENT,
            type:     "POST",
            dataType: "json",
            data:     JSON.stringify(params),
            success:  function(data) {
                loginErrorContainer.html('');

                if (data.success === true) {
                    $.redirect(INDEX_SEGMENT, {"token" : data.token }, "POST");

                    return true;
                }

                setMessageContainer(data.messages);
            },
            complete: function() {
                loaderModal.trigger('closeModal');
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
     * @param messages
     */
    function setMessageContainer(messages) {
        if (typeof messages === "object") {
            var container = $('<div></div>');

            $.each(messages, function (index, value) {
                var p = $('<p>').text(value);
                container.append(p);
            });

            loginErrorContainer.html(container.html());
        }
    }
}

$(function() {
    "use strict";

    var login = new Login();
    login.initialize();
});
