require(["../common"], function (common) {
  require(["main-function", "../app/app-penggajian"], function (func, application) {
    App = $.extend(application, func);
    App.init();
  });
});
