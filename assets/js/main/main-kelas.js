require(["../common"], function (common) {
  require(["main-function", "../app/app-kelas"], function (func, application) {
    App = $.extend(application, func);
    App.init();
  });
});
