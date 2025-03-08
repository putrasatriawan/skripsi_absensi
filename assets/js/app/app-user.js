define(["toastr", "datatablesBS4", "jqvalidate","dropzone"], function (
  toastr,
  datatablesBS4,
  jqvalidate,
  dropzone
) {
  return {
    table: null,
    init: function () {
      App.initFunc();
      App.initEvent();
      App.initConfirm();
      // App.initSave();
      App.searchTable();
      App.resetSearch();
      App.roleIdChangeSiswa();
      App.disableFunction();
      App.FormImport();

      // $(".dataTables_filter").hide();
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
        processing: true,
        serverSide: true,
        ajax: {
          url: App.baseUrl + "user/dataList",
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "role_name" },
          { data: "name" },
          { data: "nik" },
          { data: "action", orderable: false },
        ],
      });

      if ($("#form").length > 0) {
        $("#save-btn").removeAttr("disabled");
    
        // Handle role selection and field visibility
        $("#role_id").on('change', function () {
            var selectedRole = $(this).val();
            if (selectedRole == "4") {
                $("#kelas_siswa, #jk_siswa, #ttl_siswa, #no_hp_siswa").show();
            } else {
                $("#kelas_siswa, #jk_siswa, #ttl_siswa, #no_hp_siswa").hide();
            }
        });
    
        // Form validation
        $("#form").validate({
            rules: {
                name: { required: true },
                email: { required: true },
                nik: { required: true },
                password: {
                    required: $("#user_id").length <= 0,
                    minlength: 8,
                },
                password_confirm: {
                    required: $("#user_id").length <= 0,
                    minlength: 8,
                    equalTo: "#password",
                },
                role_id: { required: true },
                kelas_id: {
                    required: function () { return $("#role_id").val() == "4"; }
                },
                jk: {
                    required: function () { return $("#role_id").val() == "4"; }
                },
                ttl: {
                    required: function () { return $("#role_id").val() == "4"; }
                },
                no_hp: {
                    required: function () { return $("#role_id").val() == "4"; }
                },
            },
            messages: {
                // Custom error messages
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
                var formData = new FormData(form); // FormData for file upload
                $.ajax({
                    method: "POST",
                    url: form.action,
                    data: formData,
                    contentType: false, // Set to false for multipart/form-data
                    processData: false, // Prevent jQuery from processing the data
                    success: function (response) {
                        toastr.success(response.message);
                        // setTimeout(function () {
                        //     window.location.href = App.baseUrl + "user/";
                        // }, 1000);
                    },
                    error: function (xhr, status, error) {
                        toastr.error("Gagal menyimpan data!");
                    },
                });
            },
        });
    }
    
    
      
    },

    roleIdChangeSiswa: function () {
      $("#role_id").change(function () {
        var selectedRoleName = $(this).find(":selected").attr("data-role-name");

        if (selectedRoleName.toLowerCase() === "siswa") {
          $("#kelas_siswa").show();
          $("#angkatan_siswa").show();
          $("#jk_siswa").show();
          $("#ttl_siswa").show();
          $("#no_hp_siswa").show();
        } else {
          $("#kelas_siswa").hide();
          $("#angkatan_siswa").hide();
          $("#jk_siswa").hide();
          $("#ttl_siswa").hide();
          $("#no_hp_siswa").hide();
        }
      });
    },
    searchTable: function () {
      $("#search").on("click", function () {
        console.log("SEARCH");
        var name = $("#name").val();
        var company_field = $("#company").val();
        var email = $("#email").val();

        App.table.column(3).search(name, true, true);
        App.table.column(5).search(email, true, true);

        App.table.draw();
      });
    },
    resetSearch: function () {
      $("#reset").on("click", function () {
        $("#name").val("");
        $("#company").val("");
        $("#email").val("");

        App.table.search("").columns().search("").draw();
      });
    },

    initConfirm: function () {
      $("#table tbody").on("click", ".delete", function () {
        var url = $(this).attr("url");
        App.confirm("Apakah Anda Yakin Untuk Mengubah Ini?", function () {
          $.ajax({
            method: "GET",
            url: url,
          })
            .done(function (msg) {
              toastr.success("Data berhasil dihapus!");
              App.table.ajax.reload(null, true);
            })
            .fail(function () {
              toastr.error("Gagal menghapus data!");
            });
        });
      });

      $("#table tbody").on("click", ".reset_password", function () {
        var url = $(this).attr("url");
        var status = $(this).attr("data-status");
        var pesan = "Apakah Anda Yakin akan Mereset Password ini";
        App.confirm(pesan, function () {
          $.ajax({
            method: "GET",
            url: url,
          }).done(function (msg) {
            var data = JSON.parse(msg);
            if (data.status == false) {
              toastr.error(data.msg);
            } else {
              toastr.success(data.msg);
              App.table.ajax.reload(null, true);
            }
          });
        });
      });
    },
    searchTable: function () {
      $("#btn-apply-filter").on("click", function () {
        // var status = $("#filter-status").val();
        var roles = $("#filter-roles-jb").val();

        if (roles) {
          App.table.column(1).search(roles, true, false);
        }

        App.table.draw();
      });
    },
    resetSearch: function () {
      $("#btn-reset-filter").on("click", function () {
        $("#filter-roles-jb").val("");

        App.table.search("").columns().search("").draw();

        $("#filter-roles option:first")
          .prop("selected", true)
          .prop("hidden", true)
          .prop("disabled", true)[0].textContent = "Pilih Jabatan";
      });
    },

    disableFunction: function() {
      const filterRoles = document.getElementById('filter-roles');
      const downloadButton = document.getElementById('download-template');
      const fileInput = document.getElementById('userfile');
      const uploadButton = document.getElementById('upload-button');
  
      // Check if elements exist
      if (!filterRoles || !downloadButton || !fileInput || !uploadButton) {
          console.error("One or more elements not found.");
          return; // Exit if any element is missing
      }
  
      filterRoles.addEventListener('change', function () {
          const selectedRole = this.value;
  
          document.getElementById('role_id').value = selectedRole;
  
          if (selectedRole) {
              downloadButton.classList.remove("disabled");
              fileInput.disabled = false;
              uploadButton.disabled = false;
          } else {
              downloadButton.classList.add("disabled");
              fileInput.disabled = true;
              uploadButton.disabled = true;
          }
  
          downloadButton.addEventListener('click', function () {
              filterRoles.disabled = true;
  
              $.ajax({
                  url: App.baseUrl + "user/get_template_url",
                  type: 'POST',
                  data: { role: selectedRole },
                  dataType: 'json',
                  success: function (response) {
                      if (response.templateUrl) {
                          window.location.href = response.templateUrl;
                      } else {
                          toastr.error("URL template tidak ditemukan.");
                      }
                  },
                  error: function () {
                      toastr.error("Gagal mendapatkan URL template.");
                  },
                  complete: function() {
                      // Disable elements
                      filterRoles.disabled = true;
                      fileInput.disabled = true;
                      uploadButton.disabled = true;
                      downloadButton.classList.add("disabled");
  
                      // Re-enable the role dropdown after 2 seconds
                      setTimeout(function() {
                          window.location.reload();
                      }, 2000);
                  }
              });
          });
      });
  },


      FormImport: function() {
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
    
            const formData = new FormData(this);
    
            $.ajax({
                url: App.baseUrl + "user/import_data",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false, 
                success: function(response) {
                    console.log("Response:", response);
                    try {
                        const result = JSON.parse(response);
    
                        if (result && result.status === 'success') {
                            toastr.success(result.message);

                            document.getElementById('download-template').classList.add('disabled');
                            document.getElementById('userfile').disabled = true;
                            document.getElementById('upload-button').disabled = true;
                            document.getElementById('filter-roles').disabled = true;
    
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        } else {
                            toastr.error(result.message || "Unexpected error.");
                        }
                    } catch (error) {
                        console.error("JSON Parse Error:", error);
                        toastr.error("Failed to parse server response.");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                    toastr.error("An error occurred while importing data: " + textStatus);
                }
            });
        });
    },
  };
});
