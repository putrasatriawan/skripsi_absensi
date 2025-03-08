define(["highcharts", "datepicker"], function (Highcharts, datepicker) {
  return {
    table: null,
    selectedDate: null,
    init: function () {
      App.initFunc();
      App.initEvent();
      App.unitAndYear();
      App.resetSearch();
      App.yearPicker();
      $(".loadingpage").hide();
    },
    initEvent: function () {
      if (!App.selectedDate) {
        App.selectedDate = new Date().toISOString().slice(0, 10);
      }

      $('#attendanceDateFilter').val(App.selectedDate);

      $('#attendanceDateFilter').on('change', function () {
        App.selectedDate = $(this).val();
        App.graphAttendance();
      });

      App.graphAll();
      App.graphUser();
      App.graphAttendance();
      // App.graphProsedur();
      // App.graphInstruksi();
      // App.graphFormulir();
      // App.graphDataRekaman();
    },
    graphAll: function () {
      $.ajax({
        url: App.baseUrl + "dashboard/get_all",
        type: "GET",
        dataType: "json",
        success: function (response) {
          var categories = [];
          var seriesData = [];

          for (var table in response) {
            var tableData = [];
            var tableTotal = response[table].total;
            var tableAlias = response[table].alias; // Mengambil alias kustom dari data yang diterima

            response[table].data.forEach(function (item) {
              categories.push(item.name);
              tableData.push({
                y: parseInt(item.count),
                name: tableAlias, // Menggunakan alias kustom sebagai nama table
              });
            });

            seriesData.push({
              name: tableAlias,
              data: tableData,
              total: tableTotal,
            });
          }

          Highcharts.chart("graph-all", {
            chart: {
              type: "bar",
            },
            title: {
              text: "Total Semua Per Unit",
              align: "center",
            },
            xAxis: {
              categories: categories,
              title: {
                text: null,
              },
            },
            yAxis: {
              min: 0,
              title: {
                text: "Total Data",
                align: "high",
              },
              labels: {
                overflow: "justify",
                format: "{value}",
              },
              tickInterval: 1,
              gridLineWidth: 0,
            },
            legend: {
              align: "right",
              verticalAlign: "top",
              layout: "vertical",
            },
            tooltip: {
              formatter: function () {
                return this.point.name + ": " + this.point.y + " Unit";
              },
            },
            credits: {
              enabled: false,
            },
            series: seriesData,
          });
        },
        error: function (xhr, status, error) {
          console.error("Error fetching data:", error);
        },
      });
    },

    graphUser: function () {
      $.ajax({
        url: App.baseUrl + "dashboard/get_user",
        type: "GET",
        dataType: "json",
        success: function (response) {
          var data = [];

          response.forEach(function (item) {
            data.push({ name: item.name, y: parseInt(item.count) });
          });

          Highcharts.chart("userRolesPieChart", {
            chart: {
              type: "pie",
            },
            title: {
              text: "Total User",
              align: "center",
            },
            series: [
              {
                name: "Users",
                colorByPoint: true,
                data: data,
              },
            ],
          });
        },
        error: function (xhr, status, error) {
          console.error("Error fetching data:", error);
        },
      });
    },

    graphAttendance: function () {
      $.ajax({
        url: App.baseUrl + "dashboard/get_attendance",
        type: "GET",
        data: { date: App.selectedDate },
        dataType: "json",
        success: function (response) {
          var categories = [];
          var hadirData = [];
          var alpaData = [];
          var sakitData = [];

          response.forEach(function (item) {
            categories.push(item.kode_kelas);
            hadirData.push(parseInt(item.Hadir));
            alpaData.push(parseInt(item.Alpa));
            sakitData.push(parseInt(item.Sakit));
          });
    
          Highcharts.chart("attendanceBarChart", {
            chart: {
              type: "column"
            },
            title: {
              text: "Laporan Kehadiran"
            },
            xAxis: {
              categories: categories,
              crosshair: true
            },
            yAxis: {
              min: 0,
              title: {
                text: "Kehadiran"
              }
            },
            tooltip: {
              headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
              pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
              footerFormat: '</table>',
              shared: true,
              useHTML: true
            },
            plotOptions: {
              column: {
                pointPadding: 0.2,
                borderWidth: 0
              }
            },
            series: [{
              name: "Hadir",
              data: hadirData
            }, {
              name: "Alpa",
              data: alpaData
            }, {
              name: "Sakit",
              data: sakitData
            }]
          });
        },
        error: function (xhr, status, error) {
          console.error("Error fetching attendance data:", error);
        },
      });
    },
    

    graphProsedur: function () {
      $.ajax({
        url: App.baseUrl + "dashboard/get_prosedur",
        type: "GET",
        dataType: "json",
        success: function (response) {
          var categories = [];
          var dataCounts = [];
          var dataCountsTerbit = [];
          var dataCountsNull = [];

          response.forEach(function (item) {
            categories.push(item.name);
            dataCountsTerbit.push(parseInt(item.terbit_count));
            dataCountsNull.push(parseInt(item.null_count));
            dataCounts.push(parseInt(item.count));
          });

          // console.log("prosedur count", dataCountsNull);

          Highcharts.chart("graph-prosedur", {
            chart: {
              type: "bar",
              // height: 400,
            },
            title: {
              text: "Total Prosedur Per Unit",
              align: "center",
            },
            xAxis: {
              categories: categories,
              title: {
                text: null,
              },
            },
            yAxis: {
              min: 0,
              title: {
                text: "Total Data",
                align: "high",
              },
              labels: {
                overflow: "justify",
                format: "{value}",
              },
              tickInterval: 1,
              gridLineWidth: 0,
            },
            legend: { enabled: false },
            tooltip: {
              // formatter: function () {
              //   return (
              //     this.x +
              //     "<br><b>Total Unit: " +
              //     this.y +
              //     "</b><br>" +
              //     "Total Terbit: " +
              //     dataCountsTerbit[this.point.index] + // Menggunakan nilai langsung dari dataCountsTerbit
              //     "<br>" +
              //     "Total Waiting Approved: " +
              //     dataCountsNull[this.point.index] // Menggunakan nilai langsung dari dataCountsNull
              //   );
              // },
              formatter: function () {
                return this.x + "<br><b>Total Unit: " + this.y + "</b>";
              },
            },

            credits: {
              enabled: false,
            },
            series: [
              {
                data: dataCounts,
              },
            ],
          });
        },
        error: function (xhr, status, error) {
          console.error("Error fetching data:", error);
        },
      });
    },
    graphInstruksi: function () {
      $.ajax({
        url: App.baseUrl + "dashboard/get_instruksi",
        type: "GET",
        dataType: "json",
        success: function (response) {
          var categories = [];
          var dataCounts = [];

          //   console.log(dataCounts);
          response.forEach(function (item) {
            categories.push(item.name);
            dataCounts.push(parseInt(item.count));
          });

          Highcharts.chart("graph-instruksi", {
            chart: {
              type: "bar",
            },
            title: {
              text: "Total Instruksi Per Unit",
              align: "center",
            },
            xAxis: {
              categories: categories,
              title: {
                text: null,
              },
            },
            yAxis: {
              min: 0,
              title: {
                text: "Total Data",
                align: "high",
              },
              labels: {
                overflow: "justify",
                format: "{value}",
              },
              tickInterval: 1,
              gridLineWidth: 0,
            },
            legend: { enabled: false },
            tooltip: {
              formatter: function () {
                return this.x + "<br><b>Total Unit: " + this.y + "</b>";
              },
            },
            credits: {
              enabled: false,
            },
            series: [
              {
                data: dataCounts,
              },
            ],
          });
        },
        error: function (xhr, status, error) {
          console.error("Error fetching data:", error);
        },
      });
    },
    graphFormulir: function () {
      $.ajax({
        url: App.baseUrl + "dashboard/get_formulir",
        type: "GET",
        dataType: "json",
        success: function (response) {
          var categories = [];
          var dataCounts = [];

          //   console.log(dataCounts);
          response.forEach(function (item) {
            categories.push(item.name);
            dataCounts.push(parseInt(item.count));
          });

          Highcharts.chart("graph-formulir", {
            chart: {
              type: "bar",
            },
            title: {
              text: "Total Formulir Per Unit",
              align: "center",
            },
            xAxis: {
              categories: categories,
              title: {
                text: null,
              },
            },
            yAxis: {
              min: 0,
              title: {
                text: "Total Data",
                align: "high",
              },
              labels: {
                overflow: "justify",
                format: "{value}",
              },
              tickInterval: 1,
              gridLineWidth: 0,
            },
            legend: { enabled: false },
            tooltip: {
              formatter: function () {
                return this.x + "<br><b>Total Unit: " + this.y + "</b>";
              },
            },
            credits: {
              enabled: false,
            },
            series: [
              {
                data: dataCounts,
              },
            ],
          });
        },
        error: function (xhr, status, error) {
          console.error("Error fetching data:", error);
        },
      });
    },
  };
});
