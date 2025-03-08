define(["datatablesBS4", "jqvalidate", "toastr"], function (datatablesBS4, jqvalidate, toastr) {
    return {
      table: null,
      table_absensi: null,
      init: function () {
        App.initFunc();
        App.initEvent();
        $(".loadingpage").hide();
      },
      initEvent: function () {
        $(document).ready(function () {
          $('#btn-apply-filter').click(function (e) {
              e.preventDefault();
              var pengampu = $('#siswa_id_filter').val();
              var kode_kelas = $('#kode_kelas_id_filter').val();
              var tanggal_start = $('#tanggal_filter_start').val();
              var tanggal_end = $('#tanggal_filter_end').val();
              var mapel = $('#mapel_id_filter').val();
              var status = $('#status_id_filter').val();

              $.ajax({
                  url: App.baseUrl + "report/getFilteredData",
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
      
                      $.each(response.grouped_data, function(pengampu_name, absensi_records) {
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
                        
                        $.each(absensi_records, function(index, record) {
                            table += '<tr>';
                            table += '<td>' + (index + 1) + '</td>';
                            table += '<td>' + record.nama_siswa + '</td>';
                            table += '<td>' + record.kode_kelas_kode + '</td>';
                            table += '<td>' + record.mapel_name + '</td>';
                            table += '<td>' + record.status + '</td>';
                            table += '<td>' + record.date_click_status + '</td>';
                            table += '</tr>';
                        });
                        
                        table += '</tbody></table>';
                        
                        $('#attendance_data').append(sectionTitle + table);
                    });
                  },
                  error: function () {
                      alert('Failed to retrieve data.');
                  }
              });
          });

          $('#btn-reset-filter').click(function () {
              $('#siswa_id_filter').val('');
              $('#kode_kelas_id_filter').val('');
              $('#tanggal_filter_start').val('');
              $('#tanggal_filter_end').val('');
              $('#mapel_id_filter').val('');
              $('#status_id_filter').val('');
      
              $('#absensi_table_body').empty();
          });

          $('#generatePdfBtn').click(function () {
            var pengampu = $('#siswa_id_filter').val();
            var kode_kelas = $('#kode_kelas_id_filter').val();
            var tanggal_start = $('#tanggal_filter_start').val();
            var tanggal_end = $('#tanggal_filter_end').val();
            var mapel = $('#mapel_id_filter').val();
            var status = $('#status_id_filter').val();

            var form = $('<form/>', {
                'method': 'POST',
                'action': App.baseUrl + 'report/generate_pdf'
            }).append($('<input/>', { 'type': 'hidden', 'name': 'pengampu', 'value': pengampu }))
              .append($('<input/>', { 'type': 'hidden', 'name': 'kode_kelas', 'value': kode_kelas }))
              .append($('<input/>', { 'type': 'hidden', 'name': 'tanggal_start', 'value': tanggal_start }))
              .append($('<input/>', { 'type': 'hidden', 'name': 'tanggal_end', 'value': tanggal_end }))
              .append($('<input/>', { 'type': 'hidden', 'name': 'mapel', 'value': mapel }))
              .append($('<input/>', { 'type': 'hidden', 'name': 'status', 'value': status }));

            $('body').append(form);
            form.submit();
        });
      });
      },
    };
  });
  