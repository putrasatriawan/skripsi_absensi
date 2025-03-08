define(["datatablesBS4", "jqvalidate", "toastr"], function (datatablesBS4, jqvalidate, toastr) {
  return {
    table: null,
    table_absensi: null,
    init: function () {
      App.initFunc();
      App.initEvent();
      $(".loadingpage").hide();

      // Fetch and display all data when the page is opened
      this.fetchAndDisplayData();
    },
    initEvent: function () {
      var self = this;

      $(document).ready(function () {
        // Filter button click event
        $('#btn-apply-filter').click(function (e) {
          e.preventDefault();
          self.fetchAndDisplayData();
        });

        // Reset button click event
        $('#btn-reset-filter').click(function () {
          $('#siswa_id_filter').val('');
          $('#kode_kelas_id_filter').val('');
          $('#tanggal_filter_start').val('');
          $('#tanggal_filter_end').val('');
          $('#mapel_id_filter').val('');
          $('#status_id_filter').val('');

          $('#attendance_data').empty();
        });

        $(document).on('change', '.edit-status', function () {
          var Id = $(this).data('id');
          var DateClickStatus = $(this).data('date');
          var Id_siswa = $(this).data('id_siswa');
          var Id_mapel = $(this).data('id_mapel');
          var Kode_kelas = $(this).data('kode_kelas');
          var Id_pengampu = $(this).data('id_pengampu');
          var newStatus = $(this).val();

          $.ajax({
            url: App.baseUrl + "absensi_admin/updateStatus",
            type: "POST",
            data: {
              id:Id,
              status: newStatus,
              id_siswa: Id_siswa,
              id_mapel: Id_mapel,
              kode_kelas: Kode_kelas,
              id_pengampu: Id_pengampu,
              date_click_status: DateClickStatus,

            },
            success: function (response) {
              if(response.status === 'success'){
                toastr.success(response.message);
              } else {
                toastr.error(response.message);
              }
            },
            error: function () {
              toastr.error("An error occurred while updating status.");
            }
          });
        });
      });
    },

    fetchAndDisplayData: function () {
      var pengampu = $('#siswa_id_filter').val();
      var kode_kelas = $('#kode_kelas_id_filter').val();
      var tanggal_start = $('#tanggal_filter_start').val();
      var tanggal_end = $('#tanggal_filter_end').val();
      var mapel = $('#mapel_id_filter').val();
      var status = $('#status_id_filter').val();

      $.ajax({
        url: App.baseUrl + "absensi_admin/getFilteredData",
        type: 'POST',
        dataType: 'json',
        data: {
          pengampu: pengampu,
          kode_kelas: kode_kelas,
          tanggal_start: tanggal_start,
          tanggal_end: tanggal_end,
          mapel: mapel,
          status: status
        },
        success: function (response) {
          $('#attendance_data').empty();
          
          $.each(response.grouped_data, function (pengampu_name, absensi_records) {
            var sectionTitle = '<h3>Pengampu: ' + pengampu_name + '</h3>';
            
            var table = '<table class="table table-bordered">';
            table += '<thead><tr>';
            table += '<th>No</th>';
            table += '<th>Nama Siswa</th>';
            table += '<th>Kode Kelas</th>';
            table += '<th>Mapel</th>';
            table += '<th>Status</th>';
            table += '<th>Date</th>';
            table += '</tr></thead><tbody>';
            
            $.each(absensi_records, function (index, record) {
              var status = record.status || 'Not Marked'; // Default if null
              var bgColor;
              switch (status) {
                case 'hadir':
                    bgColor = '#5cb85c';
                    break;
                case 'alpa':
                    bgColor = '#da4f4a';
                    break;
                case 'izin':
                    bgColor = '#f0ad4e';
                    break;
                case 'sakit':
                    bgColor = '#5bc0de';
                    break;
                default:
                    bgColor = '#ffffff';
                    break;
              }
              table += '<tr>';
              table += '<td>' + (index + 1) + '</td>';
              table += '<td>' + record.nama_siswa + '</td>';
              table += '<td>' + record.kode_kelas_kode + '</td>';
              table += '<td>' + record.mapel_name + '</td>';
              
              table += '<td>';
              table += '<select class="edit-status" style="background-color: ' + bgColor + ';" data-id="' + record.id + '" data-id_siswa="' + record.id_siswa + '" data-id_mapel="' + record.id_mapel + '" data-kode_kelas="' + record.kode_kelas + '" data-id_pengampu="' + record.id_pengampu + '" data-date="' + record.date_click_status + '">';
              table += '<option value=""' + (status === 'Pilih' ? ' selected' : '') + '>Pilih</option>';
              table += '<option value="hadir"' + (status === 'hadir' ? ' selected' : '') + '>Hadir</option>';
              table += '<option value="alpa"' + (status === 'alpa' ? ' selected' : '') + '>Alpa</option>';
              table += '<option value="izin"' + (status === 'izin' ? ' selected' : '') + '>Izin</option>';
              table += '<option value="sakit"' + (status === 'sakit' ? ' selected' : '') + '>Sakit</option>';
              table += '</select>';
              table += '</td>';
              table += '<td>' + record.date_click_status + '</td>';
              table += '</tr>';
            });
            $(document).on('change', '.edit-status', function() {
              var selectedValue = $(this).val();
              var newBgColor;
              switch (selectedValue) {
                  case 'hadir':
                      newBgColor = '#5cb85c';
                      break;
                  case 'alpa':
                      newBgColor = '#da4f4a';
                      break;
                  case 'izin':
                      newBgColor = '#f0ad4e';
                      break;
                  case 'sakit':
                      newBgColor = '#5bc0de';
                      break;
                  default:
                      newBgColor = '#ffffff';
                      break;
              }
          
              $(this).css('background-color', newBgColor);
          });
            
            table += '</tbody></table>';
            
            $('#attendance_data').append(sectionTitle + table);
          });
        },
        error: function () {
          alert('Failed to retrieve data.');
        }
      });
    }
  };
});
