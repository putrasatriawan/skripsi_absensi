require(["../common"], function (common) {
  require(["main-function", "../app/app-jurusan"], function (func, application) {
    App = $.extend(application, func);
    App.init();
  });
});
