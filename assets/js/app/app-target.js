define([
    "jQuery",
    "bootstrap4"
], function (
    $,
    bootstrap4
    ) {
        return {
            init: function () {
                App.initFunc();
                App.initEvent();
                console.log("loaded");
                $(".loadingpage").hide();
            },

            initEvent: function () {
                 
            }
        }
    });
