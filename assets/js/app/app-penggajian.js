const getIdFromUrl = () => {
  const parts = window.location.pathname.split('/');
  return parts[parts.length - 1];
};
const id_config_master = getIdFromUrl();

define(["datatablesBS4", "jqvalidate", "toastr", "datepicker", "select2"], function (datatablesBS4, jqvalidate, toastr, select2) {
  return {
    table: null,
    currentId: null,

    init: function () {
      App.initEvent();
      App.initEvenDetail();
      $(".loadingpage").hide();
    },

    initEvent: function () {
      $('.select2').select2({
        width: '100%',
        placeholder: function(){
          return $(this).data('placeholder');
        },
        allowClear: true,
      });    

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
          url: App.baseUrl + "penggajian/dataList",
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "kode" },
          { data: "bulan_tahun" },
          { data: "keterangan" },
          { data: "action", orderable: false },
        ],
      });

    },
    initEvenDetail: function () {
      $('.select2').select2({
        width: '100%',
        placeholder: function(){
          return $(this).data('placeholder');
        },
        allowClear: true,
      });    

      App.table = $("#table_penggajian").DataTable({
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
          url: App.baseUrl + "penggajian/dataListDetail/"+id_config_master,
          dataType: "json",
          type: "POST",
        },
        columns: [
          { data: "id" },
          { data: "" },
          { data: "name_user" },
          { data: "" },
          { data: "action", orderable: false },
        ],
      });

    },

    
   
  };
});

