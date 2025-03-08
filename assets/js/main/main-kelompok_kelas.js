require(["../common"], function (common) {
  require(["main-function", "../app/app-kelompok_kelas"], function (func, application) {
    App = $.extend(application, func);
    App.init();
  });
});
