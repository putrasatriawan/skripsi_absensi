require(["../common"], function (common) {
  require(["main-function", "../app/app-absensi_siswa"], function (
    func,
    application
  ) {
    App = $.extend(application, func);
    App.init();
  });
});
