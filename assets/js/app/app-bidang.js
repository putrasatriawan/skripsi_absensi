define(["datatablesBS4", "jqvalidate"], function (datatablesBS4, jqvalidate) {
  return {
    table: null,
    init: function () {
      App.initFunc();
      App.initEvent();
      App.initConfirm();

      $(".loadingpage").hide();
    },
    initEvent: function () {
      App.table = $("#table").DataTable({
        language: {
          search: "Cari",
          lengthMenu: "Tampilkan _MENU_ baris per halaman",
          zeroRecords: "Data tidak ditemukan",
          info: "Menampilkan _PAGE_ dari _PAGES_",
          infoEmpty: "Tidak ada data yang ditampilkan ",
          infoFiltered: "(pencarian dari _MAX_ total records)",
          paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: "Selanjutnya",
            previous: "Sebelum",
          },
        },
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
          url: App.baseUrl + "bidang/dataList",
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "code" },
          { data: "name" },
          { data: "action", orderable: false },
        ],
      });

      if ($("#form-bidang").length > 0) {
        $("#save-btn").removeAttr("disabled");
        $("#form-bidang").validate({
          rules: {
            code: {
              required: true,
            },
            name: {
              required: true,
            },
            th_ajaran: {
              required: true,
            },
          },
          messages: {
            code: {
              required: "Code sdsd Diisi",
            },
            name: {
              required: "Nama Unit Kerja Harus Diisi",
            },
            th_ajaran: {
              required: "Nama Tahun Ajaran Harus Diisi",
            },
          },
          errorPlacement: function (error, element) {
            var name = element.attr("name");
            var errorSelector = '.form-control-feedback[for="' + name + '"]';
            var $element = $(errorSelector);
            if ($element.length) {
              $(errorSelector).html(error.html());
            } else {
              error.insertAfter(element);
            }
          },
          submitHandler: function (form) {
            // console.log(form);
            form.submit();
          },
        });
      }
    },
    initConfirm: function () {
      $("#table tbody").on("click", ".delete", function () {
        var url = $(this).attr("url");
        App.confirm("Apakah Anda Yakin Untuk Mengubah Ini?", function () {
          $.ajax({
            method: "GET",
            url: url,
          }).done(function (msg) {
            App.table.ajax.reload(null, true);
          });
        });
      });
    },
    initSend: function () {
      $.ajax({
        type: "POST",
        url: App.baseUrl + "bidang/delete_data",
      });
    },
  };
});
