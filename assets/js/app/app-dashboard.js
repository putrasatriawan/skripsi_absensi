define(["highcharts", "datepicker"], function (Highcharts, datepicker) {
  return {
    table: null,
    selectedDate: null,
    init: function () {
      App.initFunc();
      App.initEvent();
      // // App.unitAndYear();
      // App.resetSearch();
      // App.yearPicker();
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

      App.graphUser();
      App.graphAttendance();
      // App.graphProsedur();
      // App.graphInstruksi();
      // App.graphFormulir();
      // App.graphDataRekaman();
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
        url: App.baseUrl + "dashboard/get_absen",
        type: "GET",
        dataType: "json",
        success: function (response) {
          console.log(response);
    
          const countByDate = {};
          response.forEach(item => {
            const tanggal = item.tanggal_absen;
            if (!countByDate[tanggal]) {
              countByDate[tanggal] = 0;
            }
            countByDate[tanggal]++;
          });
    
          const categories = Object.keys(countByDate).sort(); // urutkan tanggal
          const data = categories.map(date => countByDate[date]);
    
          Highcharts.chart('barChartAbsensi', {
            chart: { type: 'column' },
            title: { text: 'Jumlah Kehadiran 7 Hari Terakhir' },
            xAxis: { categories, title: { text: 'Tanggal' } },
            yAxis: {
              min: 0,
              title: { text: 'Jumlah Kehadiran' }
            },
            series: [{
              name: 'Check-In',
              data
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
