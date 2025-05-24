define([  
    "datatablesBS4",  
    "jqvalidate",
    "toastr"
    ], function (  
    datatablesBS4,   
    jqvalidate,
    toastr
    ) {
    return {
        table:null,
        init: function () {
            App.initFunc();
            App.initEvent();
            App.initConfirm();
            $(".loadingpage").hide();
        },
        initEvent : function(){
            App.table = $('#table').DataTable({
                "language": {
                    "search": "Cari",
                    "lengthMenu": "Tampilkan _MENU_ baris per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang ditampilkan ",
                    "infoFiltered": "(pencarian dari _MAX_ total records)",
                    "paginate": {
                        "first":      "Pertama",
                        "last":       "Terakhir",
                        "next":       "Selanjutnya",
                        "previous":   "Sebelum"
                    },
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax":{
                    "url": App.baseUrl+"role/dataList",
                    "dataType": "json",
                    "type": "POST",
                },
                "columns": [
                    { "data": "id" },
                    { "data": "name" },
                    { "data": "description" },
                    { "data": "action" ,"orderable": false}
                ]
            }); 

            if($("#form").length > 0){
                $("#save-btn").removeAttr("disabled");
                $("#form").validate({
                    rules: {
                        name: {
                            required: true
                        } 
                    },
                    messages: {
                        name: {
                            required: "Nama Role Harus Diisi"
                        } 
                    }, 

                    errorPlacement: function(error, element) { 
                        var name = element.attr('name');
                        var errorSelector = '.form-control-feedback[for="' + name + '"]';
                        var $element = $(errorSelector);
                        if ($element.length) {
                            $(errorSelector).html(error.html());
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    
                    submitHandler : function(form) { 
                          $.ajax({
                            method: "POST",
                            url: form.action,
                            data: $(form).serialize(),
                            dataType: 'json', 
                              success: function (response) {
                                if(response.status === 'error'){
                                    toastr.error(response.message);
                                    return false;
                                } else {
                                    toastr.success(response.message);
                                    setTimeout(function () {
                                        window.location.href = App.baseUrl + "role/";
                                    }, 1000);   
                                }
                            },
                            error: function (xhr, status, error) {
                                toastr.error("Gagal menyimpan data!");
                            },
                        });
                    }
                });
            } 
        },
        
        initConfirm :function(){
            $('#table tbody').on( 'click', '.delete', function () {
                var url = $(this).attr("url");
                App.confirm("Apakah Anda Yakin Untuk Mengubah Ini?",function(){
                    $.ajax({
                        method: "GET",
                        url: url,
                    }).done(function (response) {
                        var res = JSON.parse(response);
                        if (res.status) {
                            toastr.success(res.msg);
                        } else {
                            toastr.error(res.msg || "Terjadi kesalahan!");
                        }
                        App.table.ajax.reload(null, true);
                    }).fail(function () {
                        toastr.error("Gagal menghapus data!");
                    });
                    
                    
                })
            });
        }
	}
});
