define(["datatablesBS4", "jqvalidate", "toastr", "tinymce"], function (datatablesBS4, jqvalidate, toastr, tinymce) {
  return {
    table: null,
    init: function () {
      App.initFunc();
      App.initEvent();
      App.initTinyMCE();
      App.TugasUpload();
      App.toggleBatasSubmit();
      App.initDeleteTugas();
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
          url: App.baseUrl + "elearning_siswa/dataList",
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "mapel" },
          { data: "guru" },
          { data: "action", orderable: false },
        ],
      });
    },

    initTinyMCE: function () {
      tinymce.init({
        selector: "#deskripsi_materi",
        menubar: false,
        plugins: [
          "advlist autolink lists link image charmap print preview anchor",
          "searchreplace visualblocks code fullscreen",
          "insertdatetime media table paste code help wordcount",
        ],
        toolbar:
          "",
        readonly: true,
      });
    },

    TugasUpload: function(){
      $(document).ready(function () {
        $('#uploadedTugasForm').on('submit', function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            $.ajax({
                url: App.baseUrl + "elearning_siswa/upload_tugas",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log('Response:', response);
                    toastr.success('Tugas berhasil di-upload!');
                    setTimeout(function() {
                      window.location.reload(null, true);
                      }, 1000);

                    $('#importModal').modal('hide');
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    toastr.error('Gagal meng-upload tugas.');
                }
            });
        });
    });
    },
    toggleBatasSubmit: function () {
      $(document).ready(function () {
        const isTugasValue = $("#is_tugas").val();
        if (isTugasValue === "1") {
          $("#batasTugasContainer").show();
          $("#tugasUploaded").show();
        } else {
          $("#batasTugasContainer").hide();
          $("#tugasUploaded").hide();
        }
      });
    },

    initDeleteTugas: function () {
      $("#tugasUploaded ul").on("click", ".delete", function (e) {
        e.preventDefault();
        var url = $(this).attr("data-url");
        
        App.confirm("Apakah Anda Yakin Untuk Menghapus Tugas Ini?", function () {
          $.ajax({
            method: "POST",
            url: url,
          })
          .done(function (response) {
            var data = JSON.parse(response);
            if (data.success) {
              toastr.success("Tugas berhasil dihapus!");
              location.reload();
            } else {
              toastr.error("Gagal menghapus tugas!");
            }
          })
          .fail(function () {
            toastr.error("Terjadi kesalahan saat menghapus tugas!");
          });
        });
      });
    }

  };
});