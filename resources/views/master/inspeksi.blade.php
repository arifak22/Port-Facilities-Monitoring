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
                            {!!Pel::formSelect('Cluster', null, 'cluster')!!}
                        </div>
                        <div class="col-md-4" style="float: none;display: table-cell;vertical-align: bottom;">
                            <div class="form-group m-form__group">
                                <label>
                                    {{' '}}
                                </label>
                                <br>
                                <button type="button" id="tButton" onclick="tambahCluster()" class="btn m-btn--pill    btn-info">
                                    Tambah
                                </button>
                                <button type="button" id="uButton" onclick="ubahCluster()" class="btn m-btn--pill    btn-warning">
                                    Ubah
                                </button>
                                <button type="button" id="hButton" onclick="deleteCluster()" class="btn m-btn--pill    btn-danger">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            {!!Pel::portletStart('Sub Cluster', false, 'success', array('min' => true, 'full'=> true, 'close'=> false), 
            "<li class=\"m-portlet__nav-item\">
                <button onclick=\"tambahSubCluster(true)\" class=\"btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--pill m-btn--air\">
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
<div class="modal fade" id="tambah_cluster" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tambah Cluster</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                {!!Pel::formInput('Cluster','text','tCluster')!!}
                <br/>
                {!!Pel::formSubmit('Tambah','tambah-cluster')!!}
            </form>
        </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tambah_subcluster" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tambah Sub Cluster</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                {!!Pel::formInput('Sub Cluster','text','tSubCluster')!!}
                {!!Pel::formSelect('Suhu', array(array('value'=>'1', 'name'=>'Ya'), array('value'=>'0', 'name'=>'Tidak')), 'tSuhu')!!}
                {!!Pel::formSelect('Getaran', array(array('value'=>'1', 'name'=>'Ya'), array('value'=>'0', 'name'=>'Tidak')), 'tGetaran')!!}
                {!!Pel::formSelect('Noise', array(array('value'=>'1', 'name'=>'Ya'), array('value'=>'0', 'name'=>'Tidak')), 'tNoise')!!}
                <br/>
                {!!Pel::formSubmit('Tambah','tambah-subcluster')!!}
            </form>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ubah_cluster" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ubah Cluster</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                <input type="hidden" name="uidCluster" id="uidCluster">
                {!!Pel::formInput('Cluster','text','uCluster')!!}
                <br/>
                {!!Pel::formSubmit('Ubah','ubah-cluster')!!}
            </form>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ubah_subcluster" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ubah Sub Cluster</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                <input type="hidden" name="uidSubCluster" id="uidSubCluster">
                {!!Pel::formInput('Sub Cluster','text','uSubCluster')!!}
                {!!Pel::formSelect('Suhu', array(array('value'=>'1', 'name'=>'Ya'), array('value'=>'0', 'name'=>'Tidak')), 'uSuhu')!!}
                {!!Pel::formSelect('Getaran', array(array('value'=>'1', 'name'=>'Ya'), array('value'=>'0', 'name'=>'Tidak')), 'uGetaran')!!}
                {!!Pel::formSelect('Noise', array(array('value'=>'1', 'name'=>'Ya'), array('value'=>'0', 'name'=>'Tidak')), 'uNoise')!!}
                <br/>
                {!!Pel::formSubmit('Ubah','ubah-subcluster')!!}
            </form>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-fasilitas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Fasilitas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div id="view-fasilitas">

            </div>
        </div>
    </div>
</div>
</div>
<script>
    var clusterReady = false;
    $(document).ready(function(){
        getCluster();
    });
    $("#kd_cabang").change(function(){
        getCluster();
    });
    function getCluster(){
        buttonReady(false)
        $("#cluster").html("<option>Loading....</option>");
        $.ajax({
            type : 'get',
            url  : "{{ url('api/master/cluster') }}",
            data : {kd_cabang:$("#kd_cabang").val()},
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            dataType : 'json',
            success : function(res) {
                if(res.api_status == 1){
                    var optionCluster ='';
                    res.data.forEach(element => {
                        optionCluster += "<option value=\"" + element.value + "\" >" + element.name + "</option>";
                    });
                    $("#cluster").html(optionCluster);
                    refreshTable();
                    buttonReady(true)
                }else{
                    resendToken(res);
                    alertError(res.api_message);
                }
            },
            error : function(err){
                alertError(err.statusText);
            }
        });
    }

    $("#cluster").change(function(){
        refreshTable();
    })

    

    function buttonReady(val){
        if(val){
            $("#tButton").attr('disabled', false);
            $("#uButton").attr('disabled', false);
            $("#hButton").attr('disabled', false);
        }else{
            $("#tButton").attr('disabled', true);
            $("#uButton").attr('disabled', true);
            $("#hButton").attr('disabled', true);
        }
    }

    function tambahCluster(){
        $("#tambah_cluster").modal('show');
    }
    function tambahSubCluster(){
        $("#tambah_subcluster").modal('show');
    }
    function ubahCluster(){
        var id   = $("#cluster").val();
        var text = $("#cluster option:selected").text();
        $("#ubah_cluster").modal('show');
        $("#uidCluster").val(id);
        $("#uCluster").val(text);
    }

    function ubahSubCluster(id){
        swal({
            title: 'Wait',
            html: '<div class="m-loader m-loader--lg m-loader--brand" style="width: 30px; display: inline-block;"></div>',
            showConfirmButton: false,
            customClass: 'sweetalert-xs',
            allowOutsideClick: false,
        })
        $.ajax({
            url : "{{url('master/ubah-subcluster')}}",
            data: { id: id, _token: "{{ csrf_token() }}" },
            type: 'GET',
            success: function(res, status, xhr, $form) {
                if(res.api_status == 1){
                    swal.close();
                    $("#ubah_subcluster").modal('show');
                    $("#uidSubCluster").val(id);
                    $("#uSubCluster").val(res.data.nama_sub_cluster);
                    $("#uSuhu").val(res.data.suhu);
                    $("#uGetaran").val(res.data.getaran);
                    $("#uNoise").val(res.data.noise);
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
    
    $("#tambah-cluster").click(function(e){
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');

        form.validate({
            rules: {
                tCluster: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{Pel::baseUrl('master/tambah-cluster')}}",
            data: { _token: "{{ csrf_token() }}", kd_cabang: $("#kd_cabang").val() },
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 1){
                    setTimeout(function() {
                        btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                        showErrorMsg(form, 'success', response.api_message);
                        setTimeout(function() {
                            getCluster();
                            $("#tambah_cluster").modal('hide');
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

    $("#tambah-subcluster").click(function(e){
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');
        form.validate({
            rules: {
                tSubCluster: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{Pel::baseUrl('master/tambah-subcluster')}}",
            data: { _token: "{{ csrf_token() }}", cluster: $("#cluster").val() },
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 1){
                    setTimeout(function() {
                        btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                        showErrorMsg(form, 'success', response.api_message);
                        setTimeout(function() {
                            refreshTable();
                            $("#tambah_subcluster").modal('hide');
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

    $("#ubah-cluster").click(function(e){
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');

        form.validate({
            rules: {
                uCluster: {
                    required: true
                },
                uidCluster: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{Pel::baseUrl('master/ubah-cluster')}}",
            data: { _token: "{{ csrf_token() }}" },
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 1){
                    setTimeout(function() {
                        btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                        showErrorMsg(form, 'success', response.api_message);
                        setTimeout(function() {
                            getCluster();
                            $("#ubah_cluster").modal('hide');
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

    $("#ubah-subcluster").click(function(e){
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');

        form.validate({
            rules: {
                uSubCluster: {
                    required: true
                },
                uidSubCluster: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{Pel::baseUrl('master/ubah-subcluster')}}",
            data: { _token: "{{ csrf_token() }}" },
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 1){
                    setTimeout(function() {
                        btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                        showErrorMsg(form, 'success', response.api_message);
                        setTimeout(function() {
                            refreshTable();
                            $("#ubah_subcluster").modal('hide');
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

    function deleteCluster(){
        swal({
            title: "Apakah Anda yakin?",
            text: "Menghapus Cluster ini?",
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
                    url : "{{url('master/hapus-cluster')}}",
                    data: { id: $("#cluster").val(), _token: "{{ csrf_token() }}" },
                    type: 'POST',
                    success: function(res, status, xhr, $form) {
                        if(res.api_status == 1){
                            swal({
                                title: "Success!",
                                text: "Berhasil di hapus.",
                                type: "success"
                            }).then((result) => {
                                if (result.value) {
                                    getCluster();
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

    function deleteSubCluster(id){
        swal({
            title: "Apakah Anda yakin?",
            text: "Menghapus Sub Cluster ini?",
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
                    url : "{{url('master/hapus-subcluster')}}",
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
                url: "{{url('api/master/list-subcluster')}}",
                map: function(raw) {
                    if(raw.api_status == 1){
                        $("#submit").removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);

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
            pageSize: 100,
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
                    var detail = `<button onclick="detailSubCluster(${row.id_sub_cluster})" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill ba" title="Detail Objek Fasilitas">\
                                        <i class="la la-eye"></i>
                                    </button>`;
                    var ubah = `<button onclick="ubahSubCluster(${row.id_sub_cluster})" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill ba" title="Ubah">\
                                        <i class="la la-pencil-square"></i>
                                    </button>`;
                    var hapus = `<button onclick="deleteSubCluster(${row.id_sub_cluster})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill ba" title="Hapus">\
                                        <i class="la la-times"></i>
                                    </button>`;
                    return detail + ' ' + ubah + ' ' + hapus;
                }
            },
            {
                field: 'nama_sub_cluster',
                title: 'Nama Sub Cluster',
            },
            {
                field: 'suhu',
                title: 'Suhu',
                template: function (row, index, datatable){
                    if(row.suhu == '1'){
                        return 'Ya';
                    }else{
                        return 'Tidak';
                    }
                }
            },
            {
                field: 'getaran',
                title: 'Getaran',
                template: function (row, index, datatable){
                    if(row.getaran == '1'){
                        return 'Ya';
                    }else{
                        return 'Tidak';
                    }
                }
            },
            {
                field: 'noise',
                title: 'Noise',
                template: function (row, index, datatable){
                    if(row.noise == '1'){
                        return 'Ya';
                    }else{
                        return 'Tidak';
                    }
                }
            },
        ],
    });

    function refreshTable(){
        datatable.setDataSourceParam('cluster', $("#cluster").val());
        datatable.reload();
    }

    function detailSubCluster(id){
        swal({
            title: 'Wait',
            html: '<div class="m-loader m-loader--lg m-loader--brand" style="width: 30px; display: inline-block;"></div>',
            showConfirmButton: false,
            customClass: 'sweetalert-xs',
            allowOutsideClick: false,
        })
        $.ajax({
            url : "{{url('master/fasilitas')}}",
            data: { id: id, _token: "{{ csrf_token() }}" },
            type: 'GET',
            success: function(res, status, xhr, $form) {
                if(res.api_status == 1){
                    swal.close();
                    $("#modal-fasilitas").modal('show');
                    $("#view-fasilitas").html(res.html);
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
</script>