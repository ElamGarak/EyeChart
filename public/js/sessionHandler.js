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
                    $.logOut("You have been logged out due to inactivity");
                }
            },
            error   : function (jqXHR, textStatus, errorThrown) {
                $.logOut(errorThrown);
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
        if ($("div#sessionModalContainer").length > 0) {
            return;
        }

        $.dialogModalContent({
            cloneTarget: "defaultModalContainer",
            targetId:    "sessionModal",
            containerId: "sessionModalContainer",
            title:       "Ready to Leave?",
            message:     "Your session is about to expire in " + SESSION_CHECK_WARNING_THRESHOLD + " minutes.  Do you wish to stay?",
            buttons:     {
                stay: {
                    className: "btn btn-secondary",
                    callback: function () {
                        refreshSession();
                    }
                },
                logout: {
                    className: "btn btn-primary",
                    callback: function () {
                        clearTimeout(sessionCheckHandle);

                        $.openLoaderModal(true);
                        $.logOut("You have been logged out");
                    }
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
}

$(function() {
    "use strict";

    var sessionHandler = new SessionHandler();
    sessionHandler.initialize();
});
