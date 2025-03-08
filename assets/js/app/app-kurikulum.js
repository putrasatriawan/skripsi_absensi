define(["datatablesBS4", "jqvalidate", "toastr"], function (
  datatablesBS4,
  jqvalidate,
  toastr
) {
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
          info: "Menampilkan _START_ - _END_ dari _TOTAL_",
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
          url: App.baseUrl + "kurikulum/dataList",
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "name" },
          { data: "action", orderable: false },
        ],
      });

      if ($("#form-kurikulum").length > 0) {
        $("#save-btn").removeAttr("disabled");
        $("#form-kurikulum").validate({
          rules: {
            name: {
              required: true,
            },
          },
          messages: {
            name: {
              required: "Nama Kurikulum Harus Diisi",
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
            $.ajax({
              method: "POST",
              url: form.action,
              data: $(form).serialize(),
              success: function (response) {
                toastr.success(response.message);
                setTimeout(function () {
                  window.location.href = App.baseUrl + "kurikulum/";
                }, 1000);
              },
              error: function (xhr, status, error) {
                console.log("Error Status:", status);
                console.log("XHR Object:", xhr);
                console.log("Error Thrown:", error);
                toastr.error("Gagal menyimpan data!");
              },
            });
          },
        });
      }
    },
    initDataMapel: function () {
      var pathArray = window.location.pathname.split('/');
      var id_kurikulum = pathArray[4];
      App.table = $("#table").DataTable({
        language: {
          search: "Cari",
          lengthMenu: "Tampilkan _MENU_ baris per halaman",
          zeroRecords: "Data tidak ditemukan",
          info: "Menampilkan _START_ - _END_ dari _TOTAL_",
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
          url: App.baseUrl + "kurikulum_sub/dataListMapel?id_kurikulum=" + id_kurikulum,
          dataType: "json",
          type: "POST",
          data: function(d) {
            d.id_kurikulum = id_kurikulum;
          },
        },
        columns: [
          { data: "id" },
          { data: "name" },
          { data: "kurikulum_name" },
          { data: "kelas" },
          { data: "total_bab" },
          { data: "action", orderable: false },
        ],
      });

      if ($("#form-kurikulum").length > 0) {
        $("#save-btn").removeAttr("disabled");
        $("#form-kurikulum").validate({
          rules: {
            name: {
              required: true,
            },
          },
          messages: {
            name: {
              required: "Nama Kurikulum Harus Diisi",
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
            $.ajax({
              method: "POST",
              url: form.action,
              data: $(form).serialize(),
              success: function (response) {
                toastr.success(response.message);
                setTimeout(function () {
                  window.location.href = App.baseUrl + "kurikulum_sub/";
                }, 1000);
              },
              error: function (xhr, status, error) {
                console.log("Error Status:", status);
                console.log("XHR Object:", xhr);
                console.log("Error Thrown:", error);
                toastr.error("Gagal menyimpan data!");
              },
            });
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
          })
            .done(function (msg) {
              toastr.success("Status data berhasil diubah!");
              App.table.ajax.reload(null, true);
            })
            .fail(function () {
              toastr.error("Gagal menghapus data!");
            });
        });
      });
    },
    initSend: function () {
      $.ajax({
        type: "POST",
        url: App.baseUrl + "kurikulum/delete_data",
      });
    },
  };
});
