define(["datatablesBS4", "jqvalidate", "toastr"], function (datatablesBS4, jqvalidate, toastr) {
  return {
    table: null,
    table_absensi: null,
    init: function () {
      App.initFunc();
      App.initEvent();
      App.initConfirm();
      App.initDataAbsensi();
      $(".loadingpage").hide();
    },
    initEvent: function () {
      if (document.getElementById('tanggal')) {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal').value = today;
      }
      
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
          url: App.baseUrl + "absensi/dataList",
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "guru" },
          { data: "mapel" },
          { data: "kode_kelas" },
          { data: "hari" },
          { data: "jam" },
          { data: "ruang" },
          { data: "action", orderable: false },
        ],
      });

      if ($("#form-absensi").length > 0) {
        $("#save-btn").removeAttr("disabled");
        $("#form-absensi").validate({
          rules: {
            code: {
              required: true,
            },
            name: {
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
            // console.log(form);
            form.submit();
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
            App.table.ajax.reload(null, true);
          });
        });
      });
    },
    initSend: function () {
      $.ajax({
        type: "POST",
        url: App.baseUrl + "unit_kerja/delete_data",
      });
    },
    // initGetParameter: function () {
    //   var urlParams = new URLSearchParams(window.location.search);
    //   var id_pengampu = urlParams.get('id_pengampu');
    //   var kode_kelas_id = urlParams.get('kode_kelas_id');
    //   var id_mapel = urlParams.get('id_mapel');
      
    //   return {
    //       id_pengampu: id_pengampu,
    //       kode_kelas_id: kode_kelas_id,
    //       id_mapel: id_mapel
    //   };
    // },
    initDataAbsensi: function () {
      var pathArray = window.location.pathname.split('/');
      var id_pengampu = pathArray[4];
      var kode_kelas_id = pathArray[5];
      var id_mapel = pathArray[6];
      App.table_absensi = $("#absensi_table").DataTable({
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
          url: App.baseUrl + "absensi/dataListAbsen?id_pengampu=" + id_pengampu + "&kode_kelas_id=" + kode_kelas_id + "&id_mapel=" + id_mapel,
          dataType: "json",
          type: "POST",
        },
        columns: [
          { "data": "id" },
          { "data": "nis" },
          { "data": "nama_siswa" },
          { "data": "kode_kelas" },
          { "data": "hadir" },
          { "data": "alpa" },
          { "data": "sakit" },
          { "data": "izin" },
          { "data": "keterangan" },
        ],
    });

    $(document).on('click', 'input[name^="status"]', function() {
      var pathArray = window.location.pathname.split('/');
      var id_pengampu = pathArray[3];
      var kode_kelas_id = pathArray[4];
      var id_mapel = pathArray[5];

      var id_siswa = $(this).data('id_siswa');
      var status = $(this).val();
      var keterangan = $('input[name="keterangan[' + id_siswa + ']"]').val();
      var dateClickStatus = new Date().toISOString().split('T')[0];

      $.ajax({
        url: App.baseUrl + "absensi/input/" + id_pengampu + "/" + kode_kelas_id + "/" + id_mapel,
        type: "POST",
        data: {
          id_siswa: id_siswa,
          id_mapel: id_mapel,
          kode_kelas: kode_kelas_id,
          id_pengampu: id_pengampu,
          status: status,
          keterangan: keterangan,
          date_click_status: dateClickStatus
        },
        success: function(response) {
          toastr.success("Attendance status saved successfully!");
        },
        error: function(xhr, status, error) {
          toastr.error("Failed to save attendance status!");
        }
      });
    });
    if ($("#form-absen").length > 0) {
      $("#save-btn").on("click", function (e) {
        e.preventDefault();
        App.updateDateClickOut();
      });
    }
    },
    updateDateClickOut: function () {
      var pathArray = window.location.pathname.split('/');
      var id_pengampu = pathArray[3];
      var kode_kelas_id = pathArray[4];
      var id_mapel = pathArray[5];
    
      $.ajax({
        url: App.baseUrl + "absensi/simpan",
        type: "POST",
        data: {
          id_mapel: id_mapel,
          kode_kelas: kode_kelas_id,
          id_pengampu: id_pengampu,
          date_click_status: new Date().toISOString().split('T')[0]
        },
        success: function (response) {
          toastr.success("All records updated successfully!");
          setTimeout(function() {
            window.location.href = App.baseUrl + "absensi/";
        }, 1000);
        },
        error: function (xhr, status, error) {
          toastr.error("Failed to update records!");
        }
      });
    },
    
  };
});
