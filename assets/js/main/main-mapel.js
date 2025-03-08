require(["../common"], function (common) {
  require(["main-function", "../app/app-mapel"], function (func, application) {
    App = $.extend(application, func);
    App.init();
  });
});
