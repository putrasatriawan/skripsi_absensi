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
      App.importData();
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
          url: App.baseUrl + "mapel/dataList",
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "code" },
          { data: "name" },
          { data: "th_ajaran" },
          { data: "action", orderable: false },
        ],
      });

      if ($("#form-mapel").length > 0) {
        $("#save-btn").removeAttr("disabled");
        $("#form-mapel").validate({
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
            $.ajax({
              method: "POST",
              url: form.action,
              data: $(form).serialize(),
              success: function (response) {
                toastr.success(response.message);
                setTimeout(function () {
                  window.location.href = App.baseUrl + "mapel/";
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
              toastr.success("Data berhasil dihapus!");
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
        url: App.baseUrl + "mapel/delete_data",
      });
    },
    importData: function () {
      $(document).ready(function () {
        $("#importForm").on("submit", function (event) {
          event.preventDefault();

          var fileInput = document.getElementById("userfile");
          if (!fileInput.files.length) {
            alert("Silakan pilih file untuk diupload.");
            return;
          }

          var formData = new FormData(this);

          $.ajax({
            url: App.baseUrl + "mapel/import_data",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
              if (response.status === "success") {
                toastr.success(response.message);
                setTimeout(function () {
                  window.location.reload(); // Reload halaman setelah 2 detik
                }, 2000); // 2000 ms = 2 detik
              } else if (response.status === "error") {
                toastr.error(response.message);
              }
            },
            error: function (xhr, status, error) {
              toastr.error("Terjadi kesalahan saat mengimport data.");
            },
          });
        });

        $('[data-toggle="modal"]').on("click", function () {
          $("#importModal").modal("show");
        });

        $(".modal .close").on("click", function () {
          $("#importModal").modal("hide");
        });

        $(".btn-info").on("click", function () {
          window.location.href =
            App.baseUrl + "assets/template/template-data-mapel.xlsx";
        });
      });
    },
  };
});
