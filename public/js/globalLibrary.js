/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

$.notify = function(messageText, messageType) {
    "use strict";

    var layout = {
        type: messageType,
        text: messageText
    };

    noty($.merge(NOTY_LAYOUT_CONFIG, layout));
};

$.ajaxSetup({
    statusCode: {
        401: function(results) {
            "use strict";

            if (results.responseJSON === undefined) {
                throw new EvalError("No response json was returned from 401");
            }

            // API returned a 401 unauthorized and would have removed their token from the session by this point
            if (results.responseJSON.logout === true) {
                $.redirect(LOGIN_SEGMENT, { "messages" : results.responseJSON.messages }, "POST", "");
            }
        }
    }
});

/**
 *
 * @param {boolean} state
 */
$.openLoaderModal = function(state) {
    "use strict";

    var loaderModal = $("#loaderModal");

    if (state === true) {
        loaderModal.removeClass("hidden");

        return;
    }

    loaderModal.addClass("hidden");

};

/**
 *
 * @param {object} params
 * @returns {{limit, order: Array, search, offset}}
 */
$.prepareSearchPayload = function(params) {
    "use strict";

    var order = [];

    $(params.order).each(function (index, value) {
        order[index] = {
            column: params.columns[value.column].data,
            sort:   value.dir
        };
    });

    return {
        limit : (params.length !== undefined) ? params.length : null,
        order : order,
        search: (params.search !== undefined) ? params.search.value : null,
        offset: (params.start !== undefined) ? params.start : null
    };
};

/**
 *
 * @param {String|Array} message
 */
$.logOut = function(message) {
    "use strict";

    if (typeof message === 'string') {
        var messages = [ message ]
    }

    var params = {
        "token": TOKEN
    };

    $.ajax({
        headers: JSON_HEADER_WITH_AUTH,
        url:     API_LOGOUT_SEGMENT,
        type: "POST",
        dataType: "json",
        data: JSON.stringify(params),
        complete: function () {
            $.redirect(LOGIN_SEGMENT, {"messages" : messages}, "POST");
        }
    });
};

/**
 *
 * @param {string} className
 * @returns {*|jQuery|HTMLElement}
 * @throws ReferenceError|TypeError
 */
$.faIcon = function(className) {
    if (className === undefined) {
        throw new ReferenceError("Class name was not defined");
    }

    if (typeof className !== "string") {
        throw new TypeError("Invalid class name passed");
    }

    var icon = $("<i></i>");
    icon.addClass("fa").addClass(className);
    icon.attr("aria-hidden", true);

    return icon;
};

