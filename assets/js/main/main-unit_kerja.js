require(["../common"], function (common) {
  require(["main-function", "../app/app-unit_kerja"], function (
    func,
    application
  ) {
    App = $.extend(application, func);
    App.init();
  });
});
