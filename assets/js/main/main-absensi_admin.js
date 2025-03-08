require(["../common"], function (common) {
  require(["main-function", "../app/app-absensi_admin"], function (
    func,
    application
  ) {
    App = $.extend(application, func);
    App.init();
  });
});
