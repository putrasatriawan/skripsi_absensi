require(["../common"], function (common) {
  require(["main-function", "../app/app-bidang"], function (func, application) {
    App = $.extend(application, func);
    App.init();
  });
});
