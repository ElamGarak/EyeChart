/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c)
 */

function SessionHandler() {
    "use strict";

    var tokenParam       = {token: TOKEN},
        warningThreshold = SESSION_CHECK_WARNING_THRESHOLD,
        timeoutFrequency = SESSION_CHECK_TIMEOUT_FREQUENCY,
        sessionCheckHandle;

    this.initialize = function() {
        checkSessionStatus();
    };

    function checkSessionStatus() {
        $.ajax({
            headers : JSON_HEADER_WITH_AUTH,
            type    : "POST",
            dataType: "json",
            url     : API_GET_SESSION_STATUS_SEGMENT,
            data    : JSON.stringify(tokenParam),
            success : function (data) {
                warningThreshold = data.threshold * 60; // Convert to seconds

                if (data.expired === true) {
                    $.logOut();
                }

            },
            error   : function () {
                $.logOut();
            },
            complete: function (jqXHR) {
                clearTimeout(sessionCheckHandle);
                checkSessionTime(jqXHR.responseJSON);
            }
        });
    }

    /**
     *
     * @param {json} sessionData
     */
    function checkSessionTime(sessionData) {
        if (sessionData.activeCheck === false) {
            return;
        }

        if (sessionData.remaining < warningThreshold) {
            confirmModal();
        }

        sessionCheckHandle = setTimeout(checkSessionStatus, (timeoutFrequency * 1000));
    }

    function confirmModal() {
        if ($("div#sessionEndingModal").length > 0) {
            return;
        }

        $('<div id="sessionEndingModal"></div>').dialog({
            modal:         true,
            title:         "Confirm",
            closeOnEscape: false,
            open:          function () {
                $.dialogModalContent($(this), prepareModal())
            },
            buttons      : {
                'Continue': function () {
                    refreshSession();
                    $(this).dialog("destroy");
                },
                'Logout'  : function () {
                    clearTimeout(sessionCheckHandle);

                    $.logOut();
                    $(this).dialog("destroy");
                }
            }
        });
    }

    function refreshSession() {
        $.ajax({
            headers : JSON_HEADER_WITH_AUTH,
            type    : "POST",
            dataType: "json",
            url     : API_REFRESH_SESSION_SEGMENT,
            data    : JSON.stringify(tokenParam),
            success : function () {
                checkSessionStatus();
            },
            error   : function (jqXHR, textStatus, errorThrown) {
                $.notify(errorThrown, textStatus);
            }
        });
    }

    /**
     *
     * @returns {jQuery}
     */
    function prepareModal() {
        return $("<span></span>").text("Your session is about to end.  Do you wish to continue?").html();
    }
}

$(function() {
    "use strict";

    var sessionHandler = new SessionHandler();
    sessionHandler.initialize();
});
