var App;
if (!window.console) {
  var console = {
    log: function () {},
    warn: function () {},
    error: function () {},
    time: function () {},
    timeEnd: function () {},
  };
}
var log = function () {};

require.config({
  paths: {
    jquery: "../../plugins/jquery/jquery.min",
    jQueryUI: "../../plugins/jquery-ui/jquery-ui.min",
    bootstrap: "../../plugins/bootstrap.bundle.min",
    metismenu: "../../plugins/metismenu.min",
    scrollbar: "../../plugins/scrollbar",
    architect: "../../plugins/architect",
    blockui: "../../plugins/blockui",
    datatables: "../../plugins/datatables/jquery.dataTables.min",
    responsiveDatatable: "../../plugins/datatables/dataTables.responsive.min",
    datatablesBS4: "../../plugins/datatables/dataTables.bootstrap4.min",
    bootstrapMultiSelect: "../../plugins/bootstrap-multiselect",
    responsiveBS4: "../../plugins/datatables/responsive.bootstrap.min",
    jqvalidate: "../../plugins/jquery.validate.min",
    datepicker: "../../plugins/form-components/datepicker.min",
    tinymce: "../../plugins/tinymce/tinymce.min",
    dropzone: "../../plugins/dropzone/dropzone.min",
    tags: "../../plugins/tags/jquery.tagsinput",
    highcharts: "../../plugins/highcharts",
    toastr: "../../plugins/toastr/toastr.min",
    select2: "../../plugins/select2/js/select2.min",
  },
  waitSeconds: 0,
  urlArgs: "bust=" + new Date().getTime(),
  shim: {
    jquery: {
      exports: "jquery",
      init: function () {
        console.log("jquery inited..");
      },
    },
    jQueryUI: {
      exports: "jQueryUI",
      init: function () {
        console.log("jQueryUI inited..");
      },
    },
    bootstrap: {
      exports: "bootstrap",
      init: function () {
        console.log("bootstrap inited..");
      },
    },
    metismenu: {
      exports: "metismenu",
      init: function () {
        console.log("metismenu inited..");
      },
    },
    scrollbar: {
      exports: "scrollbar",
      init: function () {
        console.log("scrollbar inited..");
      },
    },
    architect: {
      exports: "architect",
      deps: ["jquery"],
      init: function () {
        console.log("architect inited..");
      },
    },
    blockui: {
      exports: "blockui",
      deps: ["jquery"],
      init: function () {
        console.log("blockui inited..");
      },
    },
    datatables: {
      exports: "datatables",
      deps: ["jquery"],
      init: function () {
        console.log("datatables inited..");
      },
    },
    responsiveDatatable: {
      exports: "responsiveDatatable",
      deps: ["jquery"],
      init: function () {
        console.log("responsiveDatatable inited..");
      },
    },
    datatablesBS4: {
      exports: "datatablesBS4",
      deps: ["jquery"],
      init: function () {
        console.log("datatablesBS4 inited..");
      },
    },
    responsiveBS4: {
      exports: "responsiveBS4",
      deps: ["jquery"],
      init: function () {
        console.log("responsiveBS4 inited..");
      },
    },
    jqvalidate: {
      exports: "jqvalidate",
      deps: ["jquery"],
      init: function () {
        console.log("jqvalidate inited..");
      },
    },
    bootstrapMultiSelect: {
      deps: ["jquery"],
      exports: "bootstrapMultiSelect",
      init: function () {
        console.log("bootstrapMultiSelect inited..");
      },
    },
    datepicker: {
      exports: "datepicker",
      deps: ["jquery"],
      init: function () {
        console.log("datepicker inited..");
      },
    },
    tinymce: {
      exports: "tinymce",
      deps: ["jquery"],
      init: function () {
        console.log("tinymce inited..");
      },
    },
    dropzone: {
      exports: "dropzone",
      deps: ["jquery"],
      init: function () {
        console.log("dropzone inited..");
      },
    },
    tags: {
      exports: "tags",
      deps: ["jquery"],
      init: function () {
        console.log("tags inited..");
      },
    },
    toastr: {
      deps: ["jquery"],
      exports: "toastr",
      init: function () {
        console.log("toastr inited..");
      },
    },
    select2: {
      deps: ["jquery"],
      exports: "select2",
      init: function () {
        console.log("select2 inited..");
      },
    },
  },
  map: {
    "*": {
      "datatables.net": "datatables",
      "datatables.net-responsive": "responsiveDatatable",
      "datatables.net-bs": "responsiveBS4",
    },
  },
  packages: [
    {
      name: "highcharts",
      main: "highcharts",
    },
  ],
});
