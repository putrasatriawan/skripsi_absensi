define(["datatablesBS4", "jqvalidate", "toastr"], function (datatablesBS4, jqvalidate, toastr) {
  return {
    table: null,
    init: function () {
      App.initFunc();
      App.initEvent();
      App.initConfirm();
      App.addButton();
      App.editId();
      App.addButtonEdit();
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
          url: App.baseUrl + "pengampu/dataList",
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

      if ($("#form-pengampu").length > 0) {
        $("#save-btn").removeAttr("disabled");
        $("#form-pengampu").validate({
          rules: {
            id_user: {
              required: true,
            },
            id_kelas: {
              required: true,
            },
            id_guru: {
              required: true,
            },
            hari: {
              required: true,
            },
            jam: {
              required: true,
            },
          },
          messages: {
            id_user: {
              required: "User Harus Diisi",
            },
            id_kelas: {
              required: "Kelas Harus Diisi",
            },
            id_guru: {
              required: "Guru Harus Diisi",
            },
            hari: {
              required: "Hari Harus Diisi",
            },
            jam: {
              required: "Jam Harus Diisi",
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
                  window.location.href = App.baseUrl + "pengampu/";
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
            toastr.success("Data berhasil dihapus!");
            App.table.ajax.reload(null, true);
          }).fail(function () {
            toastr.error("Gagal menghapus data!");
          });
        });
      });
    },
    addButton: function () {
      $("#rowbutton").click(function () {
        console.log("asladjao");
        App.addRow();
      });
    },

    addRow: function () {
      $.ajax({
        url: App.baseUrl + "pengampu/get_mapel",
        type: "GET",
        dataType: "json",
        success: function (datamapel) {
          $.ajax({
            url: App.baseUrl + "pengampu/get_kelompok_kelas",
            type: "GET",
            dataType: "json",
            success: function (datakelompok_kelas) {
              $.ajax({
                url: App.baseUrl + "pengampu/get_ruang",
                type: "GET",
                dataType: "json",
                success: function (dataruang) {
                  const dropdownMapel = datamapel.map((item) => {
                    return `<option value="${item.id}">${item.name} </option>`;
                  });
                  const dropdownKelas = datakelompok_kelas.map((item) => {
                    return `<option value="${item.id}">${item.kode_kelas} </option>`;
                  });
                  const dropdownRuang = dataruang.map((item) => {
                    return `<option value="${item.id}">${item.name} </option>`;
                  });
                  var count = $("#form-container .row").length;

                  var html = '<div class="row">';
                  html += '<div class="col-md-3">';
                  html += '<div class="form-group">';
                  html += '<label class="form-label">Mapel</label>';
                  html += `<select name="id_mapel[]" class="form-control" required>
                    ${dropdownMapel.join("")}
                  </select>`;
                  html += "</div>";
                  html += "</div>";
                  html += '<div class="col-md-3">';
                  html += '<div class="form-group">';
                  html += '<label class="form-label">Kelas</label>';
                  html += '<div class="input-group mb-2">';
                  html += `<select name="id_kelas[]" class="form-control" required>
                  ${dropdownKelas.join("")}
                  </select>`;
                  html += "</div>";
                  html += "</div>";
                  html += "</div>";
                  html += '<div class="col-md-3">';
                  html += '<div class="form-group">';
                  html += '<label class="form-label">Ruang</label>';
                  html += '<div class="input-group mb-2">';
                  html += `<select name="ruang_id[]" class="form-control" required>
                  ${dropdownRuang.join("")}
                  </select>`;
                  html += "</div>";
                  html += "</div>";
                  html += "</div>";
                  html += '<div class="col-md-2">';
                  html += '<div class="form-group">';
                  html += '<label class="form-label">Hari</label>';
                  html += '<div class="input-group mb-2">';
                  html += '<select name="hari" class="form-control" required>';
                  html += '<option selected hidden disabled>Hari</option>';
                  html += '<option name= "hari" value="senin" class="form-control">Senin</option>'
                  html += '<option name= "hari" value="selasa" class="form-control">Selasa</option>'
                  html += '<option name= "hari" value="rabu" class="form-control">Rabu</option>'
                  html += '<option name= "hari" value="kamis" class="form-control">Kamis</option>'
                  html += '<option name= "hari" value="jumat" class="form-control">Jumat</option>'
                  html += '<option name= "hari" value="sabtu" class="form-control">Sabtu</option>'
                  html += "</select>";
                  html += "</div>";
                  html += "</div>";
                  html += "</div>";
                  html += '<div class="col-md-4">';
                  html += '<div class="form-group">';
                  html += '<div class=row>'
                  html += '<div class="col-md-6">';
                  html += '<label for="jamf">Dari Jam:</label>';
                  html += '<div class="input-group mb-2">';
                  html += '<input type="time" id="jamf" name="jamf" required>';
                  html += "</div>";
                  html += "</div>";
                  html += '<div class="col-md-6>';
                  html += '<label for="jamt">Sampai Jam:</label>';
                  html += '<div class="input-group mb-2">';
                  html += '<input type="time" id="jamt" name="jamt" required>';
                  html += "</div>";
                  html += "</div>";
                  html += "</div>";
                  html += "</div>";
                  html += "</div>";
                  html += '<div class="col-md-1 align-self-center">';
                  html +=
                    '<button type="button" class="btn btn-danger delete-row"><i class="fa fa-trash"></i></button>';
                  html += "</div>";
                  html += "</div>";

                  $("#form-container").append(html);

                  $(".delete-row").on("click", function () {
                    $(this).closest(".row").remove();
                  });
                },
              });
            },
          });
        },
      });
    },

    editId: function () {
      var path = window.location.pathname;
      var match = path.match(/pengampu\/edit\/(\d+)/);
      if (match !== null) {
        var id_pengampu = match[1];
        // console.log(id_pengampu);
        App.editRow(id_pengampu);
      }
    },
    deleteRowEdit: function (rowId) {
      $("#" + rowId).remove();
    },
    editRow: function (id_pengampu) {
      $.ajax({
        url: App.baseUrl + "pengampu/get_sub",
        type: "GET",
        dataType: "json",
        success: function (data) {
          $.ajax({
            url: App.baseUrl + "pengampu/get_mapel",
            type: "GET",
            dataType: "json",
            success: function (datamapel) {
              $.ajax({
                url: App.baseUrl + "pengampu/get_kelompok_kelas",
                type: "GET",
                dataType: "json",
                success: function (datakelompok_kelas) {
                  $.ajax({
                    url: App.baseUrl + "pengampu/get_ruang",
                    type: "GET",
                    dataType: "json",
                    success: function (dataruang) {
                      const dropdownMapel = datamapel.map((item) => {
                        return `<option value="${item.id}">${item.name} </option>`;
                      });
                      const dropdownKelas = datakelompok_kelas.map((item) => {
                        return `<option value="${item.id}">${item.kode_kelas} </option>`;
                      });
                      const dropdownRuang = dataruang.map((item) => {
                        return `<option value="${item.id}">${item.name} </option>`;
                      });
                      var count = $("#form-container-edit .row").length;
                      for (var i = 0; i < data.length; i++) {
                        if (data[i].id_pengampu == id_pengampu) {
                          var html = '<div class="row">';
                          html += '<div class="col-md-3">';
                          html += '<div class="form-group">';
                          html +=
                            '<input type="hidden" name="id_sub[]" value="' +
                            data[i].id +
                            '">';
                          html += '<label class="form-label">Mapel</label>';
                          html += `<select name="id_mapel[]" class="form-control" required>`;
                          dropdownMapel.forEach((item) => {
                            const selected =
                              data[i].id_mapel == item.match(/value="(\d+)"/)[1]
                                ? "selected"
                                : "";
                            html += `${item.replace(
                              "<option",
                              `<option ${selected}`
                            )}`;
                          });
                          html += `</select>`;
                          html += "</div>";
                          html += "</div>";
                          
                          html += '<div class="col-md-3">';
                          html += '<div class="form-group">';
                          html += '<label class="form-label">Kelas</label>';
                          html += '<div class="input-group mb-2">';
                          html += `<select name="id_kelas[]" class="form-control" required>`;
                          dropdownKelas.forEach((item) => {
                            const selected =
                              data[i].id_kelas == item.match(/value="(\d+)"/)[1]
                                ? "selected"
                                : "";
                            html += `${item.replace(
                              "<option",
                              `<option ${selected}`
                            )}`;
                          });
                          html += `</select>`;
                          html += "</div>";
                          html += "</div>";
                          html += "</div>";

                          html += '<div class="col-md-3">';
                          html += '<div class="form-group">';
                          html += '<label class="form-label">Ruang</label>';
                          html += '<div class="input-group mb-2">';
                          html += `<select name="ruang_id[]" class="form-control" required>`;
                          dropdownRuang.forEach((item) => {
                            const selected =
                              data[i].ruang_id == item.match(/value="(\d+)"/)[1]
                                ? "selected"
                                : "";
                            html += `${item.replace(
                              "<option",
                              `<option ${selected}`
                            )}`;
                          });
                          html += `</select>`;
                          html += "</div>";
                          html += "</div>";
                          html += "</div>";

                          html += '<div class="col-md-2">';
                          html += '<div class="form-group">';
                          html += '<label class="form-label">Hari</label>';
                          html += '<div class="input-group mb-2">';
                          html += '<select name="hari" class="form-control" required>';
                          html += '<option selected hidden disabled>Hari</option>';
                          html += '<option name="hari" value="senin" class="form-control" ' +
                              (data[i].hari === 'senin' ? 'selected' : '') + '>Senin</option>';
                          html += '<option name="hari" value="selasa" class="form-control" ' +
                              (data[i].hari === 'selasa' ? 'selected' : '') + '>Selasa</option>';
                          html += '<option name="hari" value="rabu" class="form-control" ' +
                              (data[i].hari === 'rabu' ? 'selected' : '') + '>Rabu</option>';
                          html += '<option name="hari" value="kamis" class="form-control" ' +
                              (data[i].hari === 'kamis' ? 'selected' : '') + '>Kamis</option>';
                          html += '<option name="hari" value="jumat" class="form-control" ' +
                              (data[i].hari === 'jumat' ? 'selected' : '') + '>Jumat</option>';
                          html += '<option name="hari" value="sabtu" class="form-control" ' +
                              (data[i].hari === 'sabtu' ? 'selected' : '') + '>Sabtu</option>';
                          html += "</select>";
                          html += "</div>";
                          html += "</div>";
                          html += "</div>";
                          const [jamf, jamt] = data[i].jam.split('-');
                          html += '<div class="col-md-4">';
                          html += '<div class="form-group">';
                          html += '<div class=row>'
                          html += '<div class="col-md-6">';
                          html += '<label for="jamf">Dari Jam:</label>';
                          html += '<div class="input-group mb-2">';
                          html += '<input type="time" id="jamf" name="jamf" value="' + jamf + '" required>';
                          html += "</div>";
                          html += "</div>";
                          html += '<div class="col-md-6>';
                          html += '<label for="jamt">Sampai Jam:</label>';
                          html += '<div class="input-group mb-2">';
                          html += '<input type="time" id="jamt" name="jamt" value="' + jamt + '" required>';
                          html += "</div>";
                          html += "</div>";
                          html += "</div>";
                          html += "</div>";
                          html += "</div>";

                          html += '<div class="col-md-1 align-self-center">';
                          html +=
                            '<button type="button" class="btn btn-danger btn-remove-sub"><i class="fa fa-trash"></i></button>';
                          html += "</div>";
                          html += "</div>";

                          $("#form-container-edit").append(html);

                          // Ambil data kelas dan mapel dengan Ajax untuk baris terkait

                          count++;
                        }
                        console.log(data[i].id);
                      }

                      $(".btn-remove-sub").on("click", function () {
                        var id_delete = $(this)
                          .closest("div.row")
                          .find("input[name='id_sub[]']")
                          .val();
                        $(this).closest("div.row").remove();
                        $.ajax({
                          url: App.baseUrl + "pengampu/delete_sub",
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
              });
            },
          });
        },
      });
    },

    addButtonEdit: function () {
      $("#rowbuttonedit").click(function () {
        var path = window.location.pathname;
        var match = path.match(/pengampu\/edit\/(\d+)/);
        if (match !== null) {
          var id = match[1];
          console.log(id);
          App.addRowEdit(id);
        }
      });
    },
    addRowEdit: function (id) {
      var count = $("#form-container-edit .row").length;

      var count = $("#form-container .row").length;

      var html = '<div class="row">';
      html += '<div class="col-md-3">';
      html += '<div class="form-group">';
      html += '<label class="form-label">Mapel</label>';
      html += `<select name="id_mapel[]" class="form-control" required>
                <option hidden disabled>Pilih </option>
              </select>`;
      html += "</div>";
      html += "</div>";
      html += '<div class="col-md-3">';
      html += '<div class="form-group">';
      html += '<label class="form-label">Kelas</label>';
      html += '<div class="input-group mb-2">';
      html += `<select name="id_kelas[]" class="form-control" required>
                <option hidden disabled>Pilih </option>
              </select>`;
      html += "</div>";
      html += "</div>";
      html += "</div>";
      html += '<div class="col-md-3">';
      html += '<div class="form-group">';
      html += '<label class="form-label">Ruang</label>';
      html += '<div class="input-group mb-2">';
      html += `<select name="ruang_id[]" class="form-control" required>
                <option hidden disabled>Pilih </option>
              </select>`;
      html += "</div>";
      html += "</div>";
      html += "</div>";
      html += '<div class="col-md-2">';
      html += '<div class="form-group">';
      html += '<label class="form-label">Hari</label>';
      html += '<div class="input-group mb-2">';
      html += '<select name="hari" class="form-control" required>';
      html += '<option selected hidden disabled>Hari</option>';
      html += '<option name= "hari" value="senin" class="form-control">Senin</option>'
      html += '<option name= "hari" value="selasa" class="form-control">Selasa</option>'
      html += '<option name= "hari" value="rabu" class="form-control">Rabu</option>'
      html += '<option name= "hari" value="kamis" class="form-control">Kamis</option>'
      html += '<option name= "hari" value="jumat" class="form-control">Jumat</option>'
      html += '<option name= "hari" value="sabtu" class="form-control">Sabtu</option>'
      html += "</select>";
      html += "</div>";
      html += "</div>";
      html += "</div>";
      html += '<div class="col-md-4">';
      html += '<div class="form-group">';
      html += '<div class=row>'
      html += '<div class="col-md-6">';
      html += '<label for="jamf">Dari Jam:</label>';
      html += '<div class="input-group mb-2">';
      html += '<input type="time" id="jamf" name="jamf" required>';
      html += "</div>";
      html += "</div>";
      html += '<div class="col-md-6>';
      html += '<label for="jamt">Sampai Jam:</label>';
      html += '<div class="input-group mb-2">';
      html += '<input type="time" id="jamt" name="jamt" required>';
      html += "</div>";
      html += "</div>";
      html += "</div>";
      html += "</div>";
      html += "</div>";

      html += '<div class="col-md-1 align-self-center">';
      html +=
        '<button type="button" class="btn btn-danger delete-row"><i class="fa fa-trash"></i></button>';
      html += "</div>";
      html += "</div>";

      $("#form-container").append(html);
      $.ajax({
        url: App.baseUrl + "pengampu/get_kelompok_kelas",
        dataType: "json",
        success: function (data) {
          var selectKelas = $("select[name='id_kelas[]']:last");
          selectKelas.empty();
          selectKelas.append("<option hidden disabled>Pilih </option>");
          $.each(data, function (key, value) {
            selectKelas.append(
              '<option value="' + value.id + '">' + value.kode_kelas + "</option>"
            );
          });
        },
      });
      $.ajax({
        url: App.baseUrl + "pengampu/get_mapel",
        dataType: "json",
        success: function (data) {
          var selectMapel = $("select[name='id_mapel[]']:last");
          selectMapel.empty();
          selectMapel.append("<option hidden disabled>Pilih </option>");
          $.each(data, function (key, value) {
            selectMapel.append(
              '<option value="' + value.id + '">' + value.name + "</option>"
            );
          });
        },
      });
      $(".delete-row").on("click", function () {
        $(this).closest(".row").remove();
      });

      $("#form-container-edit").append(html);
    },
  };
});
