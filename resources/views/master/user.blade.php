{!!Pel::headerTitle($title)!!}
<div class="m-content">
    <center><div id="loading" class="m-loader m-loader--brand" style="width: 30px; display: inline-block; margin-bottom:20px"></div></center>
    {{-- start tambah page --}}
    <div class="m-portlet" id="page-tambah">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                    Tambah {{$title}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a style="cursor:pointer" onclick="pageTambah(false)" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <form id="form-tambah" class="m-form m-form--fit m-form--label-align-right">
                {!!Pel::formSelect('Regional',Pel::makeOption($regional,'kd_regional','nama_regional'), 'kd_regional')!!}
                {!!Pel::formSelect('Cabang', null , 'kd_cabang')!!}
                {!!Pel::formInput('Username','text','username')!!}
                {!!Pel::formInput('Nama','text','nama')!!}
                {!!Pel::formInput('Password','text','password', '123456')!!}
                {!!Pel::formSelect('Privilige',Pel::makeOption($privilege,'id_privilege','nama_privilege'), 'id_privilege')!!}
                {!!Pel::formInput('E-mail Address','text','email')!!}
                {!!Pel::formInput('Keterangan','text','keterangan')!!}
                {!!Pel::formSubmit('Tambah','do-tambah','la la-plus')!!}
            </form>
        </div>
    </div>
    {{-- end tambah page --}}

    {{-- start ubah page --}}
    <div class="m-portlet" id="page-ubah">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                    Ubah {{$title}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a style="cursor:pointer" onclick="pageUbah(false)" class="m-portlet__nav-link m-portlet__nav-link--icon">
                            <i class="la la-close"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <form id="form-ubah" class="m-form m-form--fit m-form--label-align-right">
                {!!Pel::formHidden('id')!!}
                {!!Pel::formSelect('Regional',Pel::makeOption($regional,'kd_regional','nama_regional'), 'u_kd_regional')!!}
                {!!Pel::formSelect('Cabang', null , 'u_kd_cabang')!!}

                {!!Pel::formInput('Username','text','u_username',null, "disabled")!!}
                {!!Pel::formInput('Nama','text','u_nama')!!}
                {!!Pel::formSelect('Privilige',Pel::makeOption($privilege,'id_privilege','nama_privilege'), 'u_id_privilege')!!}
                {!!Pel::formInput('E-mail Address','text','u_email')!!}
                {!!Pel::formInput('Keterangan','text','u_keterangan')!!}
                {!!Pel::formSubmit('Ubah','do-ubah','la la-pencil')!!}
            </form>
        </div>
    </div>
    {{-- end ubah page --}}

    {{-- page list --}}
    <div class="m-portlet" id="page-list">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        {{$title}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="form-group m-form__group row align-items-center">
                            <div class="col-md-4">
                                <div class="m-input-icon m-input-icon--left">
                                    <input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
                                    <span class="m-input-icon__icon m-input-icon__icon--left">
                                        <span>
                                            <i class="la la-search"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                        <button id="button-tambah" onclick="pageTambah(true)" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                            <span>
                                <i class="la la-plus"></i>
                                <span>
                                    Tambah
                                </span>
                            </span>
                        </button>
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>
            <table class="m-datatable" id="html_table" width="100%">
                <thead>
                    <tr>
                        <th title="Field #1">
                            #
                        </th>
                        <th title="Field #2">
                            Username
                        </th>
                        <th title="Field #3">
                            Nama
                        </th>
                        <th title="Field #4">
                            Privilege
                        </th>
                        <th title="Field #5">
                            E-mail Address
                        </th>
                        <th title="Field #6">
                            Keterangan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1 ?>
                    @foreach ($data as $d)
                        <tr>
                            <td>
                                <div class="btn-group mr-2" role="group" aria-label="...">
                                    <button onclick="pageUbah(true, {{$d->id}})" type="button" class="m-btn btn btn-secondary">
                                        <i class="la la-pencil"></i>
                                    </button>
                                    <button onclick="doDelete({{$d->id}})" class="m-btn btn btn-secondary">
                                        <i class="la la-trash"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                {{$d->username}}
                            </td>
                            <td>
                                {{$d->nama}}
                            </td>
                            <td>
                                {{$d->nama_privilege}}
                            </td>
                            <td>
                                {{$d->email_address}}
                            </td>
                            <td>
                                {{$d->keterangan}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#page-tambah").hide();
        $("#page-ubah").hide();
        $("#loading").hide();
        getCabang();
        $('.m-datatable').mDatatable({
            data: {
                saveState: {cookie: false},
            },
            search: {
                input: $('#generalSearch'),
            },
            columns: [
                {
                    field: '#',
                    width: 100,
                },
            ]
        });
    });
    function getCabang(){
        $("#kd_cabang").html("<option>Loading....</option>");
        $.ajax({
            type : 'get',
            url  : "{{ url('api/master/cabang') }}",
            data : {kd_regional:$("#kd_regional").val(), all: true},
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            dataType : 'json',
            success : function(res) {
                if(res.api_status == 1){
                    var optionCabang ='';
                    res.data.forEach(element => {
                        value = element.value ? element.value : '';
                        optionCabang += "<option value=\"" + value + "\" >" + element.name + "</option>";
                    });
                    $("#kd_cabang").html(optionCabang);
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
    function getUCabang(val = null){
        $("#u_kd_cabang").html("<option>Loading....</option>");
        $.ajax({
            type : 'get',
            url  : "{{ url('api/master/cabang') }}",
            data : {kd_regional:$("#u_kd_regional").val(), all: true},
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            dataType : 'json',
            success : function(res) {
                if(res.api_status == 1){
                    var optionCabang ='';
                    res.data.forEach(element => {
                        value = element.value ? element.value : '';
                        optionCabang += "<option value=\"" + value + "\" >" + element.name + "</option>";
                    });
                    $("#u_kd_cabang").html(optionCabang);
                    if(val)
                    $("#u_kd_cabang").val(val);
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
    $("#kd_regional").change(function(){
        getCabang();
    })
    $("#u_kd_regional").change(function(){
        getUCabang();
    });
    function pageTambah(show){
        window.scrollTo(0, 0);
        if(show){
            $("#page-ubah").hide();
            $("#loading").show();
            setTimeout(function() {
                $("#page-tambah").show();
                $("#button-tambah").prop('disabled', true);
                $("#loading").hide();
            }, 500);
        }else{
            $("#page-tambah").hide();
            $("#button-tambah").prop('disabled', false);
        }
    }
    function pageUbah(show, id=null){
        window.scrollTo(0, 0);
        if(show){
            $("#page-ubah").hide();
            $("#page-tambah").hide();
            $("#loading").show();
            setTimeout(function() {
                $.ajax({
                    type : 'get',
                    url  : "{{url('master/user-by')}}",
                    data : {id:id, _token: "{{ csrf_token() }}"},
                    dataType : 'json',
                    success : function(res) {
                        $("#loading").hide();
                        if(res.api_status==1){
                            var data = res.data;
                            $("#id").val(data.id);
                            $("#u_username").val(data.username);
                            $("#u_nama").val(data.nama);
                            $("#u_id_privilege").val(data.id_privilege);
                            $("#u_email").val(data.email_address);
                            $("#u_keterangan").val(data.keterangan);
                            $("#u_kd_regional").val(data.kd_regional);
                            var kd_cabang = '';
                            if(data.kd_regional != 0 && (data.kd_cabang == ''|| data.kd_cabang == null)){
                                kd_cabang = '-all-';
                            }else{
                                kd_cabang = data.kd_cabang;
                            }
                            getUCabang(kd_cabang);
                            $("#page-ubah").show();
                            $("#button-tambah").prop('disabled', false);
                        }else if(res.api_status==0){
                            showErrorMsg($('.m-datatable'), 'danger', res.api_message);
                        }
                    },
                    error : function(err){
                        $("#loading").hide();
                        showErrorMsg($('.m-datatable'), 'danger', 'Error: ' + err.statusText);
                    }
                });
            }, 500);
        }else{
            $("#page-ubah").hide();
        }
    }

    $('#do-ubah').click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');

        form.validate({
            rules: {
                id: {
                    required: true,
                },
                u_nama: {
                    required: true,
                    maxlength: 100
                },
                u_id_privilege: {
                    required: true,
                },
                u_email: {
                    required: true,
                    email: true
                },
                u_keterangan: {
                    required: false,
                    maxlength: 200
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{url('master/update-user')}}",
            data: { _token: "{{ csrf_token() }}" },
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 1){
                    showErrorMsg(form, 'success', response.api_message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }else{
                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                    showErrorMsg(form, 'danger', response.api_message);
                }
                
            },
            error: function(err){
                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                showErrorMsg(form, 'danger', 'Error: ' + err.statusText);
            },
        });
    });
    function doDelete(id){
        swal({
            title: "Apakah Anda yakin?",
            text: "Menghapus data ini?",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#39BF4D",
            confirmButtonText: "Ya, hapus!"
        })
        .then((result) => {
            if (result.value) {
                swal({
                    title: 'Wait',
                    html: '<div class="m-loader m-loader--lg m-loader--brand" style="width: 30px; display: inline-block;"></div>',
                    showConfirmButton: false,
                    customClass: 'sweetalert-xs',
                    allowOutsideClick: false,
                });
                $.ajax({
                    type : 'get',
                    url  : "{{url('master/delete-user')}}",
                    data : {id:id, _token: "{{ csrf_token() }}"},
                    dataType : 'json',
                    success : function(res) {
                        swal.close();
                        if(res.api_status==1){
                            showErrorMsg($('.m-datatable'), 'success', res.api_message);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }else if(res.api_status==0){
                            showErrorMsg($('.m-datatable'), 'danger', res.api_message);
                        }
                    },
                    error : function(err){
                        swal.close();
                        showErrorMsg($('.m-datatable'), 'danger', 'Error: ' + err.statusText);
                    }
                });
            }
        });
    }

    $('#do-tambah').click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');

        form.validate({
            rules: {
                username: {
                    required: true,
                    maxlength: 25
                },
                nama: {
                    required: true,
                    maxlength: 100
                },
                password: {
                    required: true,
                    maxlength: 50
                },
                id_privilege: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                keterangan: {
                    required: false,
                    maxlength: 200
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{url('master/user')}}",
            data: { _token: "{{ csrf_token() }}" },
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 1){
                    showErrorMsg(form, 'success', response.api_message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }else{
                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                    showErrorMsg(form, 'danger', response.api_message);
                }
                
            },
            error: function(err){
                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                showErrorMsg(form, 'danger', 'Error: ' + err.statusText);
            },
        });
    });
</script>