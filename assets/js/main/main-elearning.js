require(["../common"], function (common) {
  require(["main-function", "../app/app-elearning"], function (
    func,
    application
  ) {
    App = $.extend(application, func);
    App.init();
  });
});
