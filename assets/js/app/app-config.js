define(["datatablesBS4", "jqvalidate", "toastr"], function (datatablesBS4, jqvalidate, toastr) {
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
            url: App.baseUrl + "config/dataList",
            dataType: "json",
            type: "POST",
          },
          columns: [
            { data: "id" },
            { data: "name" },
            { data: "check_in" },
            { data: "check_out" },
            { data: "action", orderable: false },
          ],
        });

        $('#table').on('click', '.edit-button', function(){
            var id = $(this).data('id');
            App.loadRecord(id);
          });
    
  
        if ($("#form-config").length > 0) {
          $("#save-btn").removeAttr("disabled");
          $("#form-config").validate({
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
                    window.location.href = App.baseUrl + "config/";
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
        $('#configForm').on('submit', function(event) {
            event.preventDefault(); 
            var longitude = $('#longitude').val();
            var latitude = $('#latitude').val();
            $.ajax({
                url: App.baseUrl + 'config/save', 
                method: 'POST',
                data: {
                    longitude: longitude,
                    latitude: latitude
                },
                dataType: 'json',
                success: function(response) {
                    var alertClass = (response.status === 'success') ? 'alert-primary' : 'alert-danger';
                    $('#alert-container').html('<div class="alert ' + alertClass + '">' + response.message + '</div>');

                    if (response.status === 'success') {
                        // toastr.success(response.message);
                    } else {
                        // toastr.error(response.message);
                    }
                    setTimeout(function() {
                        $('#alert-container .alert').fadeOut();
                    }, 3000);
                }
            });
        });
      },
      loadRecord: function(id) {
        $.ajax({
          url: App.baseUrl + "config/getRoleById",
          method: "POST",
          data: {id: id},
          dataType: "json",
          success: function(data) {
            $('#editDataModal #roles_id').val(data.roles_id);
            $('#editDataModal #id_check').val(data.id_check);
            $('#editDataModal #check_in').val(data.check_in);
            $('#editDataModal #check_out').val(data.check_out);
            $('#editDataModal').modal('show');
  
          }
        });
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
          url: App.baseUrl + "config/delete_data",
        });
      },
  };
  
  });
  