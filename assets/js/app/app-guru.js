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
          url: App.baseUrl + "guru/dataList",
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "name" },
          { data: "nip" },
          { data: "jenis_kelamin" }, 
          { data: "action", orderable: false },
      ],
      
      });

      $('#table').on('click', '.edit-button', function(){
        var id = $(this).data('id');
        App.currentId = id;
        App.loadRecord(id);
      });

      $('#editGuruForm').on('submit', function(e){
        e.preventDefault();
        $.ajax({
          url: App.baseUrl + "guru/edit",
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
        url: App.baseUrl + "guru/getGuruById",
        method: "POST",
        data: {id: id},
        dataType: "json",
        success: function(data) {
          $('#editGuruModal #id').val(data.id);
          $('#editGuruModal #nip').val(data.nip);
          $('#editGuruModal #name').val(data.name);
          $('#editGuruModal #jenis_kelamin').val(data.jenis_kelamin);
          $('#editGuruModal #no_hp').val(data.no_hp);
          $('#editGuruModal #agama').val(data.agama);
          $('#editGuruModal #alamat').val(data.alamat);
          $('#editGuruModal #gaji').val(data.gaji);
          $('#editGuruModal #tempat_lahir').val(data.tempat_lahir);
          $('#editGuruModal #tanggal_lahir').val(data.tanggal_lahir);
          $('#editGuruModal').modal('show');

          App.loadAdjacentRecords(id);
        }
      });
    },
    loadAdjacentRecords: function(currentId) {
      $.ajax({
        url: App.baseUrl + "guru/getAdjacentRecords",
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
            toastr.success("Data berhasil dihapus/ubah status!");
            App.table.ajax.reload(null, true);
          }).fail(function () {
            toastr.error("Gagal menghapus/ubah status data!");
          });
        });
      });
    },
    
    initSend: function () {
      $.ajax({
        type: "POST",
        url: App.baseUrl + "guru/delete_data",
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
            url: App.baseUrl + "guru/import_data",
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
            App.baseUrl + "assets/template/template-data-guru.xlsx";
        });
      });
    },
  };
});
