require(["../common"], function (common) {
  require(["main-function", "../app/app-data_absen"], function (func, application) {
    App = $.extend(application, func);
    App.init();
  });
});
