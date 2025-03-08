require(["../common"], function (common) {
    require(["main-function", "../app/app-home"], function (func, application) {
        App = $.extend(application, func);
        App.init();
    });
});
