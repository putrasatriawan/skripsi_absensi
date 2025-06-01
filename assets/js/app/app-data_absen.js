define(["datatablesBS4", "jqvalidate", "toastr"], function (datatablesBS4, jqvalidate, toastr) {
  return {
    table: null,
    init: function () {
      App.initFunc();
      App.initEvent();
      App.initConfirm();
      App.editAbsen();
      App.createAbsen();
      App.detailAbsen();
      App.dataAbsenMapel();
      App.initAbsenMapel();
      App.changeModal();
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
          url: App.baseUrl + "data_absen/dataList",
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "tanggal_absen" },
          { data: "users_name" },
          { data: "check_in" },
          { data: "check_out" },
          { data: "status_kerja" },
          { data: "status" },
          { data: "foto" },
          { data: "action", orderable: false },
        ],
      });

      if ($("#form-data_absen").length > 0) {
        $("#save-btn").removeAttr("disabled");
        $("#form-data_absen").validate({
          rules: {
            kelas_id: {
              required: true,
            },
            nomor_kelas: {
              required: true,
            },
            jurusan_id: {
              required: true,
            },
            tahun_angkatan: {
              required: true,
            },
          },
          messages: {
            kelas_id: {
              required: "Kelas Harus Diisi",
            },
            nomor_kelas: {
              required: "Nomor Kelas Harus Diisi",
            },
            jurusan_id: {
              required: "Jurusan Harus Diisi",
            },
            tahun_angkatan: {
              required: "Tahun Angkatan Harus Diisi",
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
                setTimeout(function() {
                  window.location.href = App.baseUrl + "data_absen/";
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
          }).done(function (msg) {
            toastr.success("Data berhasil dihapus!"); // Success notification
            App.table.ajax.reload(null, true);
          }).fail(function () {
            toastr.error("Gagal menghapus data!"); // Error notification
          });
        });
      });
    },
    initSend: function () {
      $.ajax({
        type: "POST",
        url: App.baseUrl + "data_absen/delete_data",
      });
    },
    editAbsen: function () {
      $(document).on('click', '.edit-button', function () {
        const id = $(this).data('id');
        const nama_user = $(this).data('nama_user');
        const is_check_in = $(this).data('is_check_in');
        const init_time = $(this).data('init_time');
        const status_work = $(this).data('status_work');
        const status = $(this).data('status');
        const photo = $(this).data('photo');

        $('#edit-id').val(id);
        $('#edit-nama_user').val(nama_user);
        $('#edit-status_work').val(status_work);
        $('#edit-status').val(status);
        $('#edit-is_check_in').val(is_check_in);
        if (photo) {
          $('#edit-photo').attr('src', 'data:image/jpeg;base64,' + photo);
        } else {
          $('#edit-photo').attr('src', App.baseUrl + 'assets/images/default.png');
        }

        if (init_time) {
          const timeParts = init_time.split(":");
          if (timeParts.length >= 2) {
              const formattedTime = timeParts[0] + ":" + timeParts[1];
              $('#edit-init_time').val(formattedTime);
          }

        }

        // console.log(is_check_in);
        if (is_check_in === 'check_out') {
          $('#label-init-time').text('Check Out');
        } else {
            $('#label-init-time').text('Check In');
        }
        

        // Tampilkan modal
        $('#editModal').modal('show');
      });
    },
    createAbsen: function () {
      $(document).on('click', '.create-button', function () {
        $('#createModal').modal('show');
      });
    },
    detailAbsen: function () {
      $(document).on('click', '.detail-button', function () {
        const nama_user = $(this).data('nama_user');
        const init_time = $(this).data('init_time');
        const status_work = $(this).data('status_work');
        const is_check_in = $(this).data('is_check_in');
        const status = $(this).data('status');
        const photo = $(this).data('photo');

        $('#detail-nama_user').text(nama_user);
        $('#detail-init_time').text(init_time);
        $('#detail-status_work').text(status_work);
        $('#detail-is_check_in').text(is_check_in);
        $('#detail-status').text(status);
        if (photo) {
          $('#detail-photo').attr('src', 'data:image/jpeg;base64,' + photo);
        } else {
          $('#detail-photo').attr('src', App.baseUrl + 'assets/images/default.png');
        }

        $('#detailModal').modal('show');
      });
    },
    dataAbsenMapel: function () {
      $(document).on('click', '.absen-mapel-button', function () {
        const mapelData = JSON.parse($(this).attr('data-mapel'));
        const id_absen = $(this).data('id-absen');
        let htmlRows = '';
    
        mapelData.forEach(function (mapel) {
          htmlRows += `
            <tr>
              <td>${mapel.nama_mapel}</td>
              <td>${mapel.jam_mulai}</td>
              <td>${mapel.jam_selesai}</td>
              <td>${mapel.hari}</td>
              <td>${mapel.status}</td>
              <td>
                <button class="btn btn-success btn-sm btn-hadir" data-id="${mapel.id}" data-id-absen="${id_absen}"><i class="fas fa-check"></i> Hadir</button>
                <button class="btn btn-danger btn-sm btn-tidak-hadir" data-id="${mapel.id}" data-id-absen="${id_absen}"><i class="fas fa-times"></i> Tidak Hadir</button>
              </td>
            </tr>
          `;
        });
    
        $('#detailMapelModal #mapel_list').html(htmlRows);
        $('#detailMapelModal').modal('show');
      });
    },
    initAbsenMapel: function () {
      $(document).on('click', '.btn-hadir, .btn-tidak-hadir', function () {
        const id_mapel = $(this).data('id');
        const id_absen = $(this).data('id-absen');
        const status = $(this).hasClass('btn-hadir') ? 'hadir' : 'tidak_hadir';
      
        $.ajax({
          url: App.baseUrl + 'data_absen/update_status_mapel',
          method: 'POST',
          data: {
            id: id_mapel,
            status: status,
            id_absen: id_absen
          },
          success: function (res) {
            toastr.success("Status Berhasil Diperbaharui")
            $('#detailMapelModal').modal('hide');
                  setTimeout(function () {
                 window.location.href = App.baseUrl + "data_absen/";
                }, 1000);   

          },
          error: function () {
            toastr.success("Status Gagal Diperbaharui")
                  setTimeout(function () {
                 window.location.href = App.baseUrl + "data_absen/";
                }, 1000);   
          }
        });
      });
      
    },    
    
    changeModal: function () {
      $(document).on('change', '#create-status, #create-is_check_in', function () {
        console.log("Status:", $('#create-status').val());
        const status = $('#create-status').val();
        const isCheckIn = $('#create-is_check_in').val();
    
        // Sembunyikan is_check_in jika Izin atau Sakit
        if (status === 'Izin' || status === 'Sakit') {
          $('#group-is-check-in').addClass('d-none');
        } else {
          $('#group-is-check-in').removeClass('d-none');
        }
    
        console.log("isCheckIn:", isCheckIn);
        // Ganti label input waktu
        $('.label-init-time').text(isCheckIn === 'check_out' ? 'Check Out' : 'Check In');
      });
    }
  };
});
