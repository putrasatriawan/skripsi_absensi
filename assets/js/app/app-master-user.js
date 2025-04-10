define(["datatablesBS4", "jqvalidate", "toastr"], function (datatablesBS4, jqvalidate, toastr) {
  return {
    table: null,
    currentId: null,

    init: function () {
      App.initFunc();
      App.initEvent();
      App.initConfirm();
      // App.mapelData();
      App.editId();
      App.addButtonEdit();
      App.calculateDuration();
      App.initEventKonfigurasiWaktu();
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
          url: App.baseUrl + "master_user/dataList",
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
        var users_id = $(this).data('users-id');
        console.log(users_id)
        App.currentId = id;
        App.users_id = users_id;
        App.loadRecord(id);
      });

      $('#editGuruForm').on('submit', function(e){
        e.preventDefault();
        $.ajax({
          url: App.baseUrl + "master_user/edit/" + App.currentId + '/' + App.users_id,
          method: "POST",
          data: $(this).serialize(),
          dataType: "json",
          success: function(response) {
            if(response.status === 'success'){
              toastr.success(response.message);
              location.reload();
            } else {
              toastr.error(response.message);
              location.reload();
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
    initEventKonfigurasiWaktu: function () {
      App.table = $("#table_waktu").DataTable({
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
          url: App.baseUrl + "master_user/dataListWaktu",
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "kode" },
          { data: "tahun" },
          { data: "bulan" },
          { data: "action", orderable: false },
        ],
      });
    },

    loadRecord: function(id) {
      $.ajax({
        url: App.baseUrl + "master_user/getGuruById",
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
        url: App.baseUrl + "master_user/getAdjacentRecords",
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
    calculateDuration: function () {
        $(document).ready(function () {
    
            function calculateDuration(startTime, endTime) {
                const start = new Date(`1970-01-01T${startTime}:00`);
                const end = new Date(`1970-01-01T${endTime}:00`);
                
                const diffInMs = end - start;
                const diffInMinutes = Math.floor(diffInMs / 60000);
                const hours = Math.floor(diffInMinutes / 60);
                const minutes = diffInMinutes % 60;
    
                if (diffInMs <= 0) return "0 Jam 0 Menit";
                return `${hours} Jam ${minutes} Menit`;
            }
 
    
            $(document).on('change', '.start-time, .end-time', function () {
                const parentRow = $(this).closest('.row');
                const startTime = parentRow.find('.start-time').val();
                const endTime = parentRow.find('.end-time').val();
                const durationField = parentRow.find('.duration');
    
                if (startTime && endTime) {
                    const duration = calculateDuration(startTime, endTime);
                    durationField.val(duration);
                }
            });
        });
    },
    editId: function () {
      var path = window.location.pathname;
      var match = path.match(/master_user\/mapel\/(\d+)/);
      if (match !== null) {
        var id_users = match[1];
        console.log(id_users);
        App.editRow(id_users);
      }
    },
    editRow: function (id_users) {
      $.ajax({
        url: App.baseUrl + "master_user/get_mapel",
        type: "GET",
        dataType: "json",
        success: function (data) {
          for (var i = 0; i < data.length; i++) {
            if (data[i].id_user == id_users) {
              var html = '<div class="row">';
              html += '<div class="col-md-2">';
              html += '<div class="form-group">';
              html +=
                '<input type="hidden" name="id_jadwal[]"  value="' +
                data[i].id +
                '">';
              html +=
                '<input type="hidden" name="id_user[]"  value="' +
                data[i].id_user +
                '">';
              html += '<label class="form-label">Hari</label>';
              html += '<select name="hari[]" class="form-control">';
              const days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
              for (var d = 0; d < days.length; d++) {
                var selected = data[i].hari == days[d] ? 'selected' : '';
                html += `<option value="${days[d]}" ${selected}>${days[d]}</option>`;
              }
              html += '</select>';
              html += "</div>";
              html += "</div>";
              html += '<div class="col-md-2">';
              html += '<div class="form-group">';
              html += '<label class="form-label">Nama Mapel</label>';
              html += '<div class="input-group mb-2">';
              html +=
                '<input type="text" name="nama_mapel[]" class="form-control" placeholder="Masukkan Nama Mapel"value="' +
                data[i].nama_mapel +
                '">';

              html += "</div>";
              html += "</div>";
              html += "</div>";

              html += '<div class="col-md-2">';
              html += '<div class="form-group">';
              html += '<label class="form-label">Jam Mulai</label>';
              html +=
                '<input type="time" name="jam_mulai[]" class="form-control start-time" placeholder="Masukkan Jam Mulai"value="' +
                data[i].jam_mulai +
                '">';
              html += "</div>";
              html += "</div>";
              html += '<div class="col-md-2">';
              html += '<div class="form-group">';
              html += '<label class="form-label">Jam Selesai</label>';
              html +=
                '<input type="time" name="jam_selesai[]" class="form-control end-time" placeholder="Masukkan jam selesai"value="' +
                data[i].jam_selesai +
                '">';
              html += "</div>";
              html += "</div>";
              html += '<div class="col-md-2">';
              html += '<div class="form-group">';
              html += '<label class="form-label">Durasi</label>';
              html +=
                '<input type="text" name="durasi[]" class="form-control duration" placeholder="Durasi" value="' +
                data[i].durasi +
                '" readonly>';
              html += "</div>";
              html += "</div>";
              html += '<div class="col-md-2 align-self-center">';
              html +=
                '<button type="button" class="btn btn-danger btn-remove-sub"><i class="fa fa-trash"></i></button>';
              html += "</div>";
              html += "</div>";

              $("#form-container-edit").append(html);
            }
            console.log(data[i].id);
          }
          $(".btn-remove-sub").on("click", function () {
            var id_delete = $(this)
              .closest("div.row")
              .find("input[name='id_jadwal[]']")
              .val();
            $(this).closest("div.row").remove();
            $.ajax({
              url: App.baseUrl + "master_user/delete_jadwal",
              type: "POST",
              dataType: "json",
              data: { id_delete: id_delete },
              success: function (id_delete) {
                console.log(id_delete);
              },
              error: function (response) {
                console.log("errorr");
              },
            });
          });
        },
      });
    },
    addButtonEdit: function () {
      $("#rowbuttonedit").click(function () {
        var path = window.location.pathname;
        var match = path.match(/master_user\/mapel\/(\d+)/);
        if (match !== null) {
          var id = match[1];
          console.log(id);
          App.addRowEdit(id);
        }
      });
    },
    addRowEdit: function (id) {
      // Menyiapkan elemen HTML
      var html = '<div class="row">';
      html += '<div class="col-md-2">';
      html += '<div class="form-group">';
      html += '<label class="form-label">Hari</label>';
      html += '<select name="hari[]" class="form-control">';
      const days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
      for (var d = 0; d < days.length; d++) {
        html += `<option value="${days[d]}">${days[d]}</option>`;
      }
      html += '</select>';
      
      html += "</div>";
      html += "</div>";
      html += '<div class="col-md-2">';
      html += '<div class="form-group">';
      html += '<label class="form-label">Nama Mapel</label>';
      html += '<div class="input-group mb-2">';
      html +=
        '<input type="text" name="nama_mapel[]" class="form-control" placeholder="Masukkan nama mapel">';
      html +=
        '<input type="hidden" name="id_user[]"  value="' + id_user +
        '">';

      html += "</div>";
      html += "</div>";
      html += "</div>";

      html += '<div class="col-md-2">';
      html += '<div class="form-group">';
      html += '<label class="form-label">Jadwal Mulai</label>';
      html +=
        '<input type="time" name="jam_mulai[]" class="form-control start-time" placeholder="Masukkan jam mulai">';
      html += "</div>";
      html += "</div>";
      html += '<div class="col-md-2">';
      html += '<div class="form-group">';
      html += '<label class="form-label">Jam Selesai</label>';
      html +=
        '<input type="time" name="jam_selesai[]" class="form-control end-time" placeholder="Masukkan sat">';
      html += "</div>";
      html += "</div>";
      html += '<div class="col-md-2">';
      html += '<div class="form-group">';
      html += '<label class="form-label">Durasi</label>';
      html +=
        '<input type="text" name="durasi[]" class="form-control duration" placeholder="Durasi" readonly>';
      html += "</div>";
      html += "</div>";
      html += '<div class="col-md-2 align-self-center">';
      html +=
        '<button type="button" class="btn btn-danger delete-row"><i class="fa fa-trash"></i></button>';
      html += "</div>";
      html += "</div>";

      $("#form-container-edit").append(html);

      // Mengaktifkan tombol delete
      $(".delete-row").on("click", function () {
        $(this).closest(".row").remove();
      });
    },
    
   
    };
  });

