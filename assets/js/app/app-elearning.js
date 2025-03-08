define(["datatablesBS4", "jqvalidate", "toastr", "tinymce"], function (datatablesBS4, jqvalidate, toastr, tinymce) {
    return {
      table: null,
      init: function () {
        App.initFunc();
        App.initEvent();
        App.initConfirm();
        App.initTinymce();
        App.ElearningUploaded();
        App.ElearningUpdate();
        App.getPengampuByAssigned();
        $(".loadingpage").hide();
      },
      initEvent: function () {
        App.table = $("#table").DataTable({
          language: {
            search: "Cari",
            lengthMenu: "Tampilkan _MENU_ baris per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _PAGE_ dari _PAGES_",
            infoEmpty: "Tidak ada data yang ditampilkan",
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
            url: App.baseUrl + "elearning/dataList",
            dataType: "json",
            type: "POST",
          },
          columns: [
            { data: "id" },
            { data: "guru" },
            { data: "mapel" },
            { data: "kode_kelas" },
            { data: "nama_materi" },
            { data: "deskripsi_materi" },
            { data: "tanggal_tayang" },
            { data: "tanggal_akhir_tayang" },
            { data: "action", orderable: false },
          ],
        });
      },
      initConfirm: function () {
        $("#table tbody").on("click", ".delete", function () {
          var url = $(this).attr("url");
          App.confirm("Apakah Anda yakin ingin menghapus data ini?", function () {
            $.ajax({
              method: "GET",
              url: url,
            })
              .done(function (response) {
                var data = JSON.parse(response);
                if (data.status) {
                  toastr.success("Status data berhasil diubah/hapus");
                  App.table.ajax.reload(null, true);
                } else {
                  toastr.error(data.msg);
                }
              })
              .fail(function () {
                toastr.error("Gagal menghapus data!");
              });
          });
        });
      },
  
      ElearningUploaded: function () {
        $('#uploadedMateriForm').on('submit', function (e) {
          e.preventDefault();
  
          var formData = new FormData(this);
          $.ajax({
            url: App.baseUrl + "elearning/upload_materi",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
              console.log('Response:', response);
              toastr.success('Materi berhasil di-upload!');
  
              $('#importModal').modal('hide');
            },
            error: function (xhr, status, error) {
              console.error('Error:', error);
              toastr.error('Gagal meng-upload materi.');
            }
          });
        });
      },

      ElearningUpdate: function(){
          $(document).ready(function() {
            $('#formEditMateri').on('submit', function(e) {
              e.preventDefault();

              tinymce.triggerSave();
              
              let formData = new FormData(this);
              let deskripsiMateri = tinymce.get('deskripsi_materi').getContent();
              formData.set('deskripsi_materi', deskripsiMateri);
              
              let id = $('#id').val();
              
                $.ajax({
                    url: App.baseUrl + "elearning/edit" + "/" + id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let res = JSON.parse(response);
                        if (res.status) {
                            toastr.success(res.message);
                            setTimeout(function() {
                                window.location.href = App.baseUrl + "elearning";
                            }, 1500);
                        } 
                        else {
                            toastr.error(res.message);
                        }
                    },
                    error: function() {
                        toastr.error('Terjadi kesalahan saat menyimpan data.');
                    }
                });
            });
        });      
      },
  
      getPengampuByAssigned: function () {
        $('#id_mapel').on('change', function () {
          var id_mapel = $(this).val();
          if (id_mapel) {
            $.ajax({
              url: App.baseUrl + "elearning/get_pengampu_kelas",
              type: 'POST',
              dataType: 'json',
              data: { id_mapel: id_mapel },
              success: function (data) {
                console.log(data);
  
                $('#id_pengampu').html('<option selected hidden disabled>Pilih Pengampu</option>');
                if (data.pengampu && data.pengampu.length > 0) {
                  $.each(data.pengampu, function (key, value) {
                    $('#id_pengampu').append('<option value="' + value.id + '">' + value.guru + '</option>');
                  });
                } 
                else {
                  $('#id_pengampu').html('<option selected hidden disabled>Tidak ada pengampu</option>');
                }
  
                $('#id_kelas').html('<option selected hidden disabled>Pilih Kelas</option>');
              }
            });
          } 
          else {
            $('#id_pengampu').html('<option selected hidden disabled>Pilih Pengampu</option>');
            $('#id_kelas').html('<option selected hidden disabled>Pilih Kelas</option>');
          }
        });
  
        $('#id_pengampu').on('change', function () {
          var id_mapel = $('#id_mapel').val();
          var id_pengampu = $(this).val();
  
          if (id_mapel && id_pengampu) {
            $.ajax({
              url: App.baseUrl + "elearning/get_pengampu_kelas",
              type: 'POST',
              dataType: 'json',
              data: { id_mapel: id_mapel, id_pengampu: id_pengampu },
              success: function (data) {
                console.log(data);
  
                $('#id_kelas').html('<option selected hidden disabled>Pilih Kelas</option>');
                if (data.kelas && data.kelas.length > 0) {
                  $.each(data.kelas, function (key, value) {
                    $('#id_kelas').append('<option value="' + value.id + '">' + value.code_kelas + '</option>');
                  });
                } 
                else {
                  $('#id_kelas').html('<option selected hidden disabled>Tidak ada kelas</option>');
                }
              }
            });
          } 
          else {
            $('#id_kelas').html('<option selected hidden disabled>Pilih Kelas</option>');
          }
        });
      },
  
      initTinymce: function () {
        tinymce.init({
          selector: "textarea[name='deskripsi_materi']",
          plugins: [
            'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
            'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
            'media', 'table', 'emoticons', 'template', 'help'
          ],
          toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
            'forecolor backcolor emoticons | help',
          menu: {
            favs: { title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons' }
          },
          menubar: 'favs file edit view insert format tools table help',
          tinycomments_mode: 'embedded',
          tinycomments_author: 'Author name'
        });
      },
    };
  });
  
