{!!Pel::headerTitle($title)!!}
<div class="m-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="m-portlet m-portlet--head-sm" data-portlet="true">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-8">
                            {!!Pel::formSelect('Cabang', $cabang, 'kd_cabang')!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            {!!Pel::formSelect('Tipe BA', $all_tipe, 'tipe_ba')!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            {!!Pel::portletStart('Master Approval', false, 'success', array('min' => true, 'full'=> true, 'close'=> false), 
            "<li class=\"m-portlet__nav-item\">
                <button onclick=\"tambahApproval(true)\" class=\"btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--pill m-btn--air\">
                    <span>
                        <i class=\"la la-plus\"></i>
                        <span>
                            Tambah
                        </span>
                    </span>
                </button>
            </li>")!!}
                <div class="data-table"></div>
            {!!Pel::portletEnd()!!}
        </div>
    </div>
</div>
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tambah</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                {!!Pel::formSelect('User', $user, 'id_user')!!}
                {!!Pel::formInput('Jabatan','text','jabatan')!!}
                <br/>
                {!!Pel::formSubmit('Tambah','submitTambah')!!}
            </form>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUbah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ubah</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                {!!Pel::formHidden('mid')!!}
                {!!Pel::formInput('User', 'text', 'uid', null, 'disabled')!!}
                {!!Pel::formInput('Jabatan','text','uJabatan')!!}
                <br/>
                {!!Pel::formSubmit('Ubah','submitUbah')!!}
            </form>
        </div>
        </div>
    </div>
</div>

<script>
    function tambahApproval(){
        $("#modalTambah").modal('show');
    }
    function ubahApproval(id){
        swal({
            title: 'Wait',
            html: '<div class="m-loader m-loader--lg m-loader--brand" style="width: 30px; display: inline-block;"></div>',
            showConfirmButton: false,
            customClass: 'sweetalert-xs',
            allowOutsideClick: false,
        })
        $.ajax({
            url : "{{url('master/ubah-approval')}}",
            data: { id: id, _token: "{{ csrf_token() }}" },
            type: 'GET',
            success: function(res, status, xhr, $form) {
                if(res.api_status == 1){
                    swal.close();
                    $("#modalUbah").modal('show');
                    $("#mid").val(id);
                    $("#uid").val(res.data.nama);
                    $("#uJabatan").val(res.data.jabatan);
                }else{
                    swal({
                        title: "Failed!",
                        text: res.api_message,
                        type: "error"
                    });
                }
            },
            error: function(err){
                swal({
                    title: "Failed!",
                    text: err.statusText,
                    type: "error"
                });
            }
        });

    }
    function deleteApproval(id){
        swal({
            title: "Apakah Anda yakin?",
            text: "Menghapus Approval ini?",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#39BF4D",
            confirmButtonText: "Ya!"
        })
        .then((result) => {
            if (result.value) {
                swal({
                    title: 'Wait',
                    html: '<div class="m-loader m-loader--lg m-loader--brand" style="width: 30px; display: inline-block;"></div>',
                    showConfirmButton: false,
                    customClass: 'sweetalert-xs',
                    allowOutsideClick: false,
                })
                $.ajax({
                    url : "{{url('master/hapus-approval')}}",
                    data: { id: id, _token: "{{ csrf_token() }}" },
                    type: 'POST',
                    success: function(res, status, xhr, $form) {
                        if(res.api_status == 1){
                            swal({
                                title: "Success!",
                                text: "Berhasil di hapus.",
                                type: "success"
                            }).then((result) => {
                                if (result.value) {
                                    refreshTable();
                                }
                            });
                        }else{
                            swal({
                                title: "Failed!",
                                text: res.api_message,
                                type: "error"
                            }).then((result) => {
                                if (result.value) {
                                    resendToken(res);
                                }
                            });
                        }
                    },
                    error: function(err){
                        swal({
                            title: "Failed!",
                            text: err.statusText,
                            type: "error"
                        });
                    }
                })
            }
        })
    }
    $("#submitTambah").click(function(e){
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');
        form.validate({
            rules: {
                id_user: {
                    required: true
                },
                jabatan: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{Pel::baseUrl('master/tambah-approval')}}",
            data: { _token: "{{ csrf_token() }}", kd_cabang: $("#kd_cabang").val() , tipe_ba: $("#tipe_ba").val() },
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 1){
                    setTimeout(function() {
                        btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                        showErrorMsg(form, 'success', response.api_message);
                        setTimeout(function() {
                            refreshTable();
                            $("#modalTambah").modal('hide');
                        }, 1000);
                    }, 2000);
                }else{
                    setTimeout(function() {
                        btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                        showErrorMsg(form, 'danger', response.api_message);
                    }, 2000);
                }
                
            }
        });
    });
    $("#submitUbah").click(function(e){
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');
        form.validate({
            rules: {
                mid: {
                    required: true
                },
                uJabatan: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{Pel::baseUrl('master/ubah-approval')}}",
            data: { _token: "{{ csrf_token() }}", kd_cabang: $("#kd_cabang").val() , tipe_ba: $("#tipe_ba").val() },
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 1){
                    setTimeout(function() {
                        btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                        showErrorMsg(form, 'success', response.api_message);
                        setTimeout(function() {
                            refreshTable();
                            $("#modalUbah").modal('hide');
                        }, 1000);
                    }, 2000);
                }else{
                    setTimeout(function() {
                        btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                        showErrorMsg(form, 'danger', response.api_message);
                    }, 2000);
                }
                
            }
        });
    })
    function refreshTable(){
        datatable.setDataSourceParam('kd_cabang', $("#kd_cabang").val());
        datatable.setDataSourceParam('tipe_ba', $("#tipe_ba").val());
        datatable.reload();
    }
    $("#kd_cabang").change(function(){
        refreshTable();
    });
    $("#tipe_ba").change(function(){
        refreshTable();
    });
    $(document).ready(function(){
        refreshTable();
    })
    var datatable = $('.data-table').mDatatable({
        // datasource definition
        data: {
            saveState: {cookie: false},
            type: 'remote',
            source: {
            read: {
                // sample GET method
                method: 'GET',
                headers: {
                'Authorization': "Bearer " + localStorage.getItem('jwt_token')
                },
                url: "{{url('api/master/list-approval')}}",
                map: function(raw) {
                    if(raw.api_status == 1){

                        var dataSet = raw.table;
                        if (typeof raw.table.data !== 'undefined') {
                            dataSet = raw.table.data;
                        }
                        return raw.table.data;
                    }else{
                        resendToken(raw);
                        alertError(raw.api_message);
                    }
                },

            },
            },
            pageSize: 10,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true,
        },
        // layout definition
        layout: {
            scroll: false,
            footer: false,
        },

        // column sorting
        sortable: true,
        pagination: true,
        columns: [
            {
                field: "actions",
                width: 110,
                title: "Actions",
                sortable: false,
                overflow: 'visible',
                template: function (row, index, datatable) {
                    var ubah = `<button onclick="ubahApproval(${row.mid})" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill ba" title="Ubah">\
                                        <i class="la la-pencil-square"></i>
                                    </button>`;
                    var hapus = `<button onclick="deleteApproval(${row.mid})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill ba" title="Hapus">\
                                        <i class="la la-times"></i>
                                    </button>`;
                    return ubah + ' ' + hapus;
                }
            },
            {
                field: 'nama',
                title: 'Nama',
            },
            {
                field: 'jabatan',
                title: 'Jabatan',
            },
            {
                field: 'nama_cabang',
                title: 'Cabang',
            },
            {
                field: 'nama_tipe',
                title: 'Tipe BA',
            },
        ],
    });
</script>