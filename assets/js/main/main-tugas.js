require(["../common"], function (common) {
  require(["main-function", "../app/app-tugas"], function (func, application) {
    App = $.extend(application, func);
    App.init();
  });
});
