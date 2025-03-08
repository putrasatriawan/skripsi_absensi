require(["../common"], function (common) {
  require(["main-function", "../app/app-absensi_staff"], function (
    func,
    application
  ) {
    App = $.extend(application, func);
    App.definedLatitude = definedLatitude;
    App.definedLongitude = definedLongitude;
    App.init();
  });
});
