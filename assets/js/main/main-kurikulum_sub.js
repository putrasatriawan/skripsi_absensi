require(["../common"], function (common) {
  require(["main-function", "../app/app-kurikulum_sub"], function (func, application) {
    App = $.extend(application, func);
    App.init();
  });
});
