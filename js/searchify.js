(function ($, undefiend) {

    $.fn.searchify = function (config) {

        var configs = {
            'searchIcon': '.customSearchBar i.prefix',
            'placeContent': '#mainContainer .container',
            'waitBeforeSend': 300, // ms
            'url': null,
            'searchKey': 'search',
            'ajaxDone': function (response, textStatus, jqXHR) {
                $contentContainer.hide().html(response).fadeIn();
            },
            'ajaxFail': function (jqXHR, textStatus, errorThrown) {
                console.error(jqXHR);
            },
        };

        // overriding the defaults
        for (var i in config) {
            if (typeof configs[i] !== typeof undefiend) {
                configs[i] = config[i];
            }
        }

        var $search = $(this);
        var $icon = $(configs.searchIcon);
        var $contentContainer = $(configs.placeContent);

        var waitOrDieActive = 0;
        var waitOrDie = function (callback, timeout) {
            waitOrDieActive++;
            setTimeout(function () {
                waitOrDieActive--;
                if (waitOrDieActive == 0) {
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            }, timeout);
        };

        var searchFocus = function () {
            $search.focus();
        };

        $(window).on('keyup', function (e) {
            if (83 == e.keyCode) { // char s
                searchFocus();
            }
        });

        $icon.click(function () {
            searchFocus();
        });

        var ajaxCall = function () {

            var data = {};
            data[configs.searchKey] = $search.val().trim();

            var request = $.ajax({
                url: configs.url,
                type: "post",
                data: data
            });

            request.done(configs.ajaxDone);

            request.fail(configs.ajaxFail);
        };

        var getSearchValue = function () {
            return $search.val().trim();
        };

        var valuesAreTheSame = false;
        var valueOnKeyDown = '';
        var valueOnKeyUp = '';

        var hadSearchString = false;
        var hasSearchString = false;

        var isLastStringDeleted = false;
        var isFirstStringAdded = false;
        var isModifyingString = false;

        var enableKeyDown = true;

        $search
            .on('keydown', function () {
                if (enableKeyDown) {
                    // remember only once the keydown state

                    valueOnKeyDown = getSearchValue();

                    hadSearchString = valueOnKeyDown !== '';
                    enableKeyDown = false;
                }
            })
            .on('keyup', function () {

                enableKeyDown = true;

                valueOnKeyUp = getSearchValue();

                hasSearchString = valueOnKeyUp !== '';
                valuesAreTheSame = valueOnKeyUp === valueOnKeyDown;
                isLastStringDeleted = hadSearchString && !hasSearchString;
                isFirstStringAdded = !hadSearchString && hasSearchString;
                isModifyingString = hadSearchString && hasSearchString;


                if (!valuesAreTheSame && (isFirstStringAdded || isModifyingString || isLastStringDeleted)) {
                    waitOrDie(ajaxCall, configs.waitBeforeSend)
                }
            });
    };

}(jQuery));