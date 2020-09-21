{!!Pel::headerTitle($title)!!}
<div class="m-content">
    <div class="row">
        <div class="col-lg-12">
            {!!Pel::portletStart('Berita Acara', false, 'success', array('min' => true, 'full'=> true, 'close'=> false), 
            "<li class=\"m-portlet__nav-item\">
                <button onclick=\"showAdd(true)\" class=\"btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--pill m-btn--air\">
                    <span>
                        <i class=\"la la-plus\"></i>
                        <span>
                            Tambah
                        </span>
                    </span>
                </button>
            </li>")!!}
            <ul class="nav nav-tabs  m-tabs-line m-tabs-line--2x m-tabs-line--success" role="tablist">
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link active" data-toggle="tab" href="#waiting" role="tab">
                        Waiting List
                    </a>
                </li>
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#finish" role="tab">
                        Finish List
                    </a>
                </li>
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#cancel" role="tab">
                        Cancel List
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="waiting" role="tabpanel">
                    <div class="m-form m-form--label-align-right m--margin-bottom-10">
                        <div class="row align-items-center">
                            <div class="col-xl-12 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-6">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                    <label>
                                        Tipe BA:
                                    </label>
                                    </div>
                                    <div class="m-form__control">
                                    <select class="form-control m-bootstrap-select" name="waiting_tipe" id="waiting_tipe">
                                        @foreach($all_tipe as $at)
                                            <option value="{{$at['value']}}">{{$at['name']}}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                                <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
										<input type="text" class="form-control" name="waiting_search" id="waiting_search" placeholder="Nomor BA...">
										<div class="input-group-append">
											<button class="btn btn-primary" id="waiting_go" type="button">
												Go Filter
											</button>
										</div>
									</div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="data-waiting"></div>
                </div>
                <div class="tab-pane" id="finish" role="tabpanel">
                    <div class="m-form m-form--label-align-right m--margin-bottom-10">
                        <div class="row align-items-center">
                            <div class="col-xl-12 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-6">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                    <label>
                                        Tipe BA:
                                    </label>
                                    </div>
                                    <div class="m-form__control">
                                    <select class="form-control m-bootstrap-select" name="finish_tipe" id="finish_tipe">
                                        @foreach($all_tipe as $at)
                                            <option value="{{$at['value']}}">{{$at['name']}}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                                <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
										<input type="text" class="form-control" name="finish_search" id="finish_search" placeholder="Nomor BA...">
										<div class="input-group-append">
											<button class="btn btn-primary" id="finish_go" type="button">
												Go Filter
											</button>
										</div>
									</div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="data-finish"></div>
                </div>
                <div class="tab-pane" id="cancel" role="tabpanel">
                    <div class="m-form m-form--label-align-right m--margin-bottom-10">
                        <div class="row align-items-center">
                            <div class="col-xl-12 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-6">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                    <label>
                                        Tipe BA:
                                    </label>
                                    </div>
                                    <div class="m-form__control">
                                    <select class="form-control m-bootstrap-select" name="cancel_tipe" id="cancel_tipe">
                                        @foreach($all_tipe as $at)
                                            <option value="{{$at['value']}}">{{$at['name']}}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                                <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
										<input type="text" class="form-control" name="cancel_search" id="cancel_search" placeholder="Nomor BA...">
										<div class="input-group-append">
											<button class="btn btn-primary" id="cancel_go" type="button">
												Go Filter
											</button>
										</div>
									</div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="data-cancel"></div>
                </div>
            </div>
            {!!Pel::portletEnd()!!}
        </div>
    </div>
</div>
<script>
    $('#waiting_tipe').selectpicker();
    $('#finish_tipe').selectpicker();
    $('#cancel_tipe').selectpicker();
    function refreshTable(table){
        if(table == 'waiting'){
            waiting.setDataSourceParam('tipe', $("#waiting_tipe").val());
            waiting.setDataSourceParam('nomor_ba', $("#waiting_search").val());
            waiting.load();
            setInterval(() => {
                $("#waiting_go").removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            }, 2000);
        }else if(table == 'finish'){
            finish.setDataSourceParam('tipe', $("#finish_tipe").val());
            finish.setDataSourceParam('nomor_ba', $("#finish_search").val());
            finish.load();
            setInterval(() => {
                $("#finish_go").removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            }, 2000);
        }else if(table == 'cancel'){
            cancel.setDataSourceParam('tipe', $("#cancel_tipe").val());
            cancel.setDataSourceParam('nomor_ba', $("#cancel_search").val());
            cancel.load();
            setInterval(() => {
                $("#cancel_go").removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            }, 2000);
        }
    }

    $("#waiting_go").click(function(e){
        e.preventDefault();
        $("#waiting_go").addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        refreshTable('waiting');
    });
    $("#finish_go").click(function(e){
        e.preventDefault();
        $("#finish_go").addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        refreshTable('finish');
    });
    $("#cancel_go").click(function(e){
        e.preventDefault();
        $("#cancel_go").addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        refreshTable('cancel');
    });

    var waiting = $('.data-waiting').mDatatable({
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
                url: "{{url('api/berita-acara/approval?status=0')}}",
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
                    var ba      = `<button onclick="lihat(${row.ba_id}, ${row.tipe_ba})" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill ba" title="Detail">\
                                        <i class="la la-eye"></i>
                                    </button>`;
                    var approve      =  `<button onclick="approvalBa(${row.ba_id}, 1)" class="m-portlet__nav-link btn m-btn m-btn--hover-info m-btn--icon m-btn--icon-only m-btn--pill ba" title="Approve">\
                                        <i class="la la-check"></i>
                                    </button>`;
                    var cancel      =  `<button onclick="approvalBa(${row.ba_id}, 9)" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill ba" title="Cancel">\
                                        <i class="la la-times"></i>
                                    </button>`;
                    return ba + ' ' + approve + ' ' + cancel;
                }
            },
            {
                field: 'nomor_ba',
                title: 'Nomor',
            },
            {
                field: 'tipe',
                title: 'Tipe',
            },
            {
                field: 'judul',
                title: 'Judul',
            },
            {
                field: 'tanggal',
                title: 'Tanggal',
            },
            {
                field: 'created_at',
                title: 'Created At',
            },
        ],
    });
    var finish = $('.data-finish').mDatatable({
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
                url: "{{url('api/berita-acara/approval?status=1')}}",
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
                width: 80,
                title: "Actions",
                sortable: false,
                overflow: 'visible',
                template: function (row, index, datatable) {
                    var ba      = `<button onclick="lihat(${row.ba_id}, ${row.tipe_ba})" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill ba" title="Detail">\
                                        <i class="la la-eye"></i>
                                    </button>`;
                    return ba;
                }
            },
            {
                field: 'nomor_ba',
                title: 'Nomor',
            },
            {
                field: 'tipe',
                title: 'Tipe',
            },
            {
                field: 'judul',
                title: 'Judul',
            },
            {
                field: 'tanggal',
                title: 'Tanggal',
            },
            {
                field: 'created_at',
                title: 'Created At',
            },
        ],
    });
    var cancel = $('.data-cancel').mDatatable({
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
                url: "{{url('api/berita-acara/approval?status=9')}}",
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
                width: 80,
                title: "Actions",
                sortable: false,
                overflow: 'visible',
                template: function (row, index, datatable) {
                    var ba      = `<button onclick="lihat(${row.ba_id}, ${row.tipe_ba})" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill ba" title="Detail">\
                                        <i class="la la-eye"></i>
                                    </button>`;
                    return ba;
                }
            },
            {
                field: 'nomor_ba',
                title: 'Nomor',
            },
            {
                field: 'tipe',
                title: 'Tipe',
            },
            {
                field: 'judul',
                title: 'Judul',
            },
            {
                field: 'tanggal',
                title: 'Tanggal',
            },
            {
                field: 'created_at',
                title: 'Created At',
            },
        ],
    });

    function approvalBa(ba_id, status){
        var text = 'Approve';
        if(status == 9) 
        text = 'Tolak';
        swal({
            title:  "Anda akan <b>"+text+"</b> BA ini?",
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: text,
            showLoaderOnConfirm: true,
            preConfirm: (note) => {
                return note;
            }
        }).then((result) =>{
            if(!result.dismiss){
                swal({
                    title: 'Wait',
                    html: '<div class="m-loader m-loader--lg m-loader--brand" style="width: 30px; display: inline-block;"></div>',
                    showConfirmButton: false,
                    customClass: 'sweetalert-xs',
                    allowOutsideClick: false,
                })
                $.ajax({
                    type: "POST",
                    url: "{{url('api/berita-acara/approve')}}",
                    data: {id:ba_id, note: result.value, status: status},
                    headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
                    dataType: 'json',
                    success: function (res) {
                        if(res.api_status == 1){
                            swal({
                                title: "Success!",
                                text: res.api_message,
                                type: "success",
                                closeOnConfirm: true
                            }).then((result) => {
                                if (result.value) {
                                    refreshTable('waiting');
                                    refreshTable('finish');
                                    refreshTable('cancel');
                                }
                            });
                        }else{
                            swal({
                                title: "Error!",
                                text: res.api_message,
                                type: "error",
                                closeOnConfirm: true
                            });
                            resendToken(res);
                        }
                    },
                    error : function(err){
                        swal.close();
                        alertError(err.statusText);
                    }
                });
            }
        })
        return false;
    }
    
    function lihat(ba_id, tipe){
        window.location.href="{{url('berita-acara/detail')}}?id="+ba_id + "&tipe="+tipe + "&mode=view";
    }
</script>