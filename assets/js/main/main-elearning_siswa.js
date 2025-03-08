require(["../common"], function (common) {
  require(["main-function", "../app/app-elearning_siswa"], function (
    func,
    application
  ) {
    App = $.extend(application, func);
    App.init();
  });
});
