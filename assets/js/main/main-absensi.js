require(["../common"], function (common) {
  require(["main-function", "../app/app-absensi"], function (
    func,
    application
  ) {
    App = $.extend(application, func);
    App.init();
  });
});
