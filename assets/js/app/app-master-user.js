define(["datatablesBS4", "jqvalidate", "toastr"], function (datatablesBS4, jqvalidate, toastr) {
  return {
    table: null,
    currentId: null,

    init: function () {
      App.initFunc();
      App.initEvent();
      App.initConfirm();
      App.mapelData();
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
    mapelData: function () {
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
  
          $('.add-mapel').click(function () {
              const day = $(this).data('day');
              const mapelContainer = $('#mapel-container-' + day);
  
              const index = mapelContainer.children().length;
              const newRow = `
                  <div class="row mt-2" id="${day}-mapel-${index}">
                      <div class="col-md-3">
                          <input type="text" name="nama_mapel[${day}][]" class="form-control" placeholder="Nama Mapel" required>
                      </div>
                      <div class="col-md-2">
                          <input type="time" name="jam_mulai[${day}][]" class="form-control start-time" required>
                      </div>
                      <div class="col-md-2">
                          <input type="time" name="jam_selesai[${day}][]" class="form-control end-time" required>
                      </div>
                      <div class="col-md-3">
                          <input type="text" name="durasi[${day}][]" class="form-control duration" placeholder="Durasi" readonly>
                      </div>
                      <div class="col-md-2">
                          <button type="button" class="btn btn-danger remove-mapel" data-target="#${day}-mapel-${index}">Hapus</button>
                      </div>
                  </div>
              `;
              mapelContainer.append(newRow);
          });
  
          $(document).on('click', '.remove-mapel', function () {
              const target = $(this).data('target');
              $(target).remove();
          });
  
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
  
          $('#mapelForm').submit(function (e) {
              e.preventDefault();
  
              const formData = $(this).serialize();
              console.log(formData);
  
              $.ajax({
                  type: "POST",
                  url: App.baseUrl + "master_user/save_jadwal/" + id,
                  data: formData,
                  dataType: "json",
                  success: function (response) {
                      if (response.success) {
                          alert('Jadwal berhasil disimpan!');
                          location.reload();
                      } else {
                          alert('Gagal menyimpan jadwal.');
                      }
                  },
                  error: function () {
                      alert('Terjadi kesalahan. Coba lagi.');
                  }
              });
          });
      });
  }
  
    };
  });

