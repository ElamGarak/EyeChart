/**
 *
 * @param {json} params
 */
$.dialogModalContent = function(params) {
    checkParams(params);

    var modal = $("#" + params.cloneTarget).clone();

    modal.find("div.modal").filter(function () {
        $(this).attr("id", params.targetId);
        $(this).addClass("defaultModal").removeClass("hidden");
    });

    modal.find("div.modal-footer").filter(function () {
        var footer = $(this);

        $.each(params.buttons, function (key, value) {
            checkForClass(value);

            var button = $("<button type='button' data-dismiss='" + key + "'>" + key.toUpperCase() + "</button>");
            button.addClass(value.className);

            footer.append(button);
        })
    });

    var container = $('<div id="' + params.containerId + '"></div>').append(modal.html());

    $("body").append(container);

    var sessionModal = $("div#" + params.targetId);

    sessionModal.find("div.modal-footer").on("click", "button[data-dismiss]", function() {
        bindCallBack(params.buttons[$(this).data().dismiss]);

        container.remove();
    });

    sessionModal.find(".modal-title").html(params.title);
    sessionModal.find(".modal-body").html(params.message);

    sessionModal.show();


    function bindCallBack(button) {
        if (button.callback !== undefined) {
            button.callback();
        }
    }

    /**
     *
     * @param {json} params
     * @throws ReferenceError
     */
    function checkParams(params) {
        if (params.cloneTarget === undefined) {
            throw new ReferenceError("No clone target was passed")
        }

        if (params.targetId === undefined) {
            throw new ReferenceError("No target id was passed")
        }

        if (params.containerId === undefined) {
            throw new ReferenceError("No container id was passed")
        }

        if (params.title === undefined) {
            throw new ReferenceError("No dialog title was passed")
        }

        if (params.buttons === undefined) {
            throw new ReferenceError("You must pass a least one button")
        }
    }

    /**
     *
     * @param {json} button
     * @throws ReferenceError
     */
    function checkForClass(button) {
        if (button.className === undefined) {
            throw new ReferenceError("You must pass a class name for the button")
        }
    }
};
