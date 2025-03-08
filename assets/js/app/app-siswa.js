define(["datatablesBS4", "jqvalidate", "toastr"], function (datatablesBS4, jqvalidate, toastr) {
  return {
    table: null,
    currentId: null,

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
          url: App.baseUrl + "siswa/dataList",
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "nama" },
          { data: "nis" },
          { data: "jk" },
          // { data: "kode_kelas_id" }, // Pastikan ini ada dalam data yang dikembalikan
          { data: "action", orderable: false },
        ],
      
      });
      
      $('#table').on('click', '.edit-button', function(){
        var id = $(this).data('id');
        App.currentId = id;
        App.loadRecord(id);
      });

      $('#editSiswaForm').on('submit', function(e){
        e.preventDefault();
        $.ajax({
          url: App.baseUrl + "siswa/edit",
          method: "POST",
          data: $(this).serialize(),
          dataType: "json",
          success: function(response) {
            if(response.status === 'success'){
              toastr.success(response.message);
            } else {
              toastr.error(response.message);
            }
          },
          error: function(xhr, status, error) {
            toastr.error("Error updating data.");
          }
        });
      });

      $('#prevBtn').on('click', function() {
        if (App.prevId !== null) {
          App.loadRecord(App.prevId);
        }
      });

      $('#nextBtn').on('click', function() {
        if (App.nextId !== null) {
          App.loadRecord(App.nextId);
        }
      });
    },

    loadRecord: function(id) {
      $.ajax({
        url: App.baseUrl + "siswa/getSiswaById",
        method: "POST",
        data: {id: id},
        dataType: "json",
        success: function(data) {
          $('#editSiswaModal #id').val(data.id);
          $('#editSiswaModal #nis').val(data.nis);
          $('#editSiswaModal #nama').val(data.nama);
          $('#editSiswaModal #jk').val(data.jk);
          $('#editSiswaModal #ttl').val(data.ttl);
          $('#editSiswaModal #alamat').val(data.alamat);
          $('#editSiswaModal #no_hp').val(data.no_hp);

          var $kodeKelasDropdown = $('#editSiswaModal #kode_kelas_id');
          $kodeKelasDropdown.empty();
          $.each(data.kode_kelas_options, function(key, value) {
            $kodeKelasDropdown.append($('<option>', {
              value: key,
              text: value
            }));
          });
          $kodeKelasDropdown.val(data.kode_kelas_id);

          $('#editSiswaModal').modal('show');
          App.loadAdjacentRecords(id);
        }
      });
    },
    loadAdjacentRecords: function(currentId) {
      $.ajax({
        url: App.baseUrl + "siswa/getAdjacentRecords",
        method: "POST",
        data: {id: currentId},
        dataType: "json",
        success: function(data) {

          if (data.prevId) {
            App.prevId = data.prevId;
            $('#prevBtn').show();
          } else {
            App.prevId = null;
            $('#prevBtn').hide();
          }

          if (data.nextId) {
            App.nextId = data.nextId;
            $('#nextBtn').show();
          } else {
            App.nextId = null;
            $('#nextBtn').hide();
          }
        },
        error: function(xhr, status, error) {
          toastr.error("Error loading adjacent records.");
        }
      });
    },

    initConfirm: function () {
      $("#table tbody").on("click", ".delete", function () {
        var url = $(this).attr("url");
        App.confirm("Apakah Anda yakin ingin menghapus data ini?", function () {
          $.ajax({
            method: "GET",
            url: url,
          }).done(function (msg) {
            toastr.success("Data berhasil dihapus!");
            App.table.ajax.reload(null, true);
          }).fail(function () {
            toastr.error("Gagal menghapus data!");
          });
        });
      });
    },
    

    initSend: function () {
      $.ajax({
        type: "POST",
        url: App.baseUrl + "siswa/delete_data",
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
            url: App.baseUrl + "siswa/import_data",
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
            App.baseUrl + "assets/template/template-data-siswa.xlsx";
        });
      });
    },
  };
});
