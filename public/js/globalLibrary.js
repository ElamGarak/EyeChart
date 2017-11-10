/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

$(document).ready(function() {
    "use strict";

    var loaderModal = $("#loaderModal");
    loaderModal.removeClass("hidden");
    loaderModal.easyModal(EASY_MODAL_CONFIG);
});

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

$.openLoaderModal = function() {
    "use strict";

    $("#loaderModal").trigger("openModal");
};

$.closeLoaderModal = function() {
    "use strict";

    $("#loaderModal").trigger("closeModal");
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

$.logOut = function() {
    "use strict";

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
            $.redirect(LOGIN_SEGMENT, {}, "POST");
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

/**
 *
 * @param {jQuery} self
 * @param {jQuery} html
 * @throws ReferenceError
 */
$.dialogModalContent = function(self, html) {
    if (self === undefined || html === undefined) {
        throw new ReferenceError("Invalid parameters were passed");
    }

    $("button.ui-dialog-titlebar-close").hide();
    $(this).html(html);

    $(this).parent()
           .find('.ui-dialog-titlebar span')
           .prepend($.faIcon("fa-exclamation-triangle text-danger"));

    $(".ui-widget-overlay").css(DIALOG_OVERLAY);
};

