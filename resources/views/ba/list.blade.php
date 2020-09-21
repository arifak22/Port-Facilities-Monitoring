{!!Pel::headerTitle($title)!!}
<div class="m-content">
    <div id="page-add">
        <div class="row">
            <div class="col-lg-12">
                {!!Pel::portletStart('Tambah', false,'info', array('min' => true, 'full'=> true, 'close'=> false), 
                "<li class=\"m-portlet__nav-item\">
                    <a href=\"#\" id=\"back\" class=\"m-portlet__nav-link m-portlet__nav-link--icon\">
                        <i class=\"la la-close\"></i>
                    </a>
                </li>")!!}
                <form>
                    
                    <div class="row">
                        <div class="col-md-6">
                            {!!Pel::formInput('Judul','text','judul')!!}
                        </div>
                        <div class="col-md-6">
                            {!!Pel::formInput('Tanggal', 'text', 'tanggal', null, 'readonly')!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!!Pel::formSelect('Regional', $regional, 'kd_regional')!!}
                        </div>
                        <div class="col-md-6">
                            {!!Pel::formSelect('Cabang', null, 'kd_cabang')!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!!Pel::formSelect('Cluster', null, 'cluster')!!}
                        </div>
                        <div class="col-md-6">
                            {!!Pel::formSelect('Sub Cluster', null, 'sub_cluster')!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!!Pel::formSelect('Tipe',$tipe_ba,'tipe')!!}
                        </div>
                        <div class="col-md-6">
                            {!!Pel::formSelect2('BA dasar', null, 'ba_dasar', null, true)!!}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            Isi
                        </label>
                        <div class="summernote"></div>
                    </div>
                    {!!Pel::formSubmit('Simpan', 'submit')!!}
                </form>
                {!!Pel::portletEnd()!!}
            </div>
        </div>
    </div>
    <div id="page-ubah">
        <div class="row">
            <div class="col-lg-12">
                {!!Pel::portletStart('Ubah', false,'warning', array('min' => true, 'full'=> true, 'close'=> false), 
                "<li class=\"m-portlet__nav-item\">
                    <a href=\"#\" id=\"uBack\" class=\"m-portlet__nav-link m-portlet__nav-link--icon\">
                        <i class=\"la la-close\"></i>
                    </a>
                </li>")!!}
                <form>
                    <input type="hidden" name="uid" id="uid">
                    <div class="row">
                        <div class="col-md-6">
                            {!!Pel::formInput('Judul','text','uJudul')!!}
                        </div>
                        <div class="col-md-6">
                            {!!Pel::formInput('Tanggal', 'text', 'uTanggal', null, 'readonly')!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!!Pel::formSelect('Regional', $regional, 'uKd_regional', null, 'disabled')!!}
                        </div>
                        <div class="col-md-6">
                            {!!Pel::formSelect('Cabang', null, 'uKd_cabang', null, 'disabled')!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!!Pel::formSelect('Cluster', null, 'uCluster', null, 'disabled')!!}
                        </div>
                        <div class="col-md-6">
                            {!!Pel::formSelect('Sub Cluster', null, 'uSub_cluster', null, 'disabled')!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!!Pel::formSelect('Tipe',$all_tipe,'uTipe', null, 'disabled')!!}
                        </div>
                        <div class="col-md-6" id="vDasar">
                            {!!Pel::formSelect2('BA dasar', null, 'uBa_dasar', null, true)!!}
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label>
                            Isi
                        </label>
                        <div class="uSummernote"></div>
                    </div>
                    {!!Pel::formSubmit('Ubah', 'uSubmit')!!}
                </form>
                {!!Pel::portletEnd()!!}
            </div>
        </div>
    </div>
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
    var pk = null
    $('#waiting_tipe').selectpicker();
    $('#finish_tipe').selectpicker();
    $('#cancel_tipe').selectpicker();
    function refreshTable(table){
        if(table == 'waiting'){
            waiting.setDataSourceParam('tipe', $("#waiting_tipe").val());
            waiting.setDataSourceParam('nomor_ba', $("#waiting_search").val());
            waiting.reload();
            setInterval(() => {
                $("#waiting_go").removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            }, 2000);
        }else if(table == 'finish'){
            finish.setDataSourceParam('tipe', $("#finish_tipe").val());
            finish.setDataSourceParam('nomor_ba', $("#finish_search").val());
            finish.reload();
            setInterval(() => {
                $("#finish_go").removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            }, 2000);
        }else if(table == 'cancel'){
            cancel.setDataSourceParam('tipe', $("#cancel_tipe").val());
            cancel.setDataSourceParam('nomor_ba', $("#cancel_search").val());
            cancel.reload();
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

    var page_add = false;
    $(document).ready(function(){
        $("#page-add").hide();
        $("#page-ubah").hide();
    });
    function showAdd(val){
        if(val){
            if(!page_add)
            getCabang();

            $("#page-add").show();
            $("#page-ubah").hide();
            page_add = true;
        }
    }
    
    $("#back").click(function(){
        $("#page-add").hide();
    });
    
    $("#uBack").click(function(){
        $("#page-ubah").hide();
    });
    var today = new Date();
    $('#tanggal, #uTanggal').datepicker({
        todayHighlight: true,
        autoclose: true,
        format: 'yyyy-mm-dd',
        orientation: "bottom left",
        endDate: today,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    });
    $('#ba_dasar, #uBa_dasar').select2({
        placeholder: "Select a Dasar",
    });
    $('.summernote, .uSummernote').summernote({
        height: 250
    });

    $("#kd_regional").change(function(){
        getCabang();
    });
    $("#kd_cabang").change(function(){
        getCluster();
    });
    $("#cluster").change(function(){
        getSubCluster();
    });
    $("#sub_cluster").change(function(){
        getDasar();
    });

    function getCabang(){
        $("#kd_cabang").html("<option>Loading....</option>");
        $.ajax({
            type : 'get',
            url  : "{{ url('api/master/cabang') }}",
            data : {kd_regional:$("#kd_regional").val()},
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            dataType : 'json',
            success : function(res) {
                if(res.api_status == 1){
                    var optionCabang ='';
                    res.data.forEach(element => {
                        optionCabang += "<option value=\"" + element.value + "\" >" + element.name + "</option>";
                    });
                    $("#kd_cabang").html(optionCabang);
                    getCluster();
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
    function getCluster(){
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
                    getSubCluster();
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
    function getSubCluster(){
        $("#sub_cluster").html("<option>Loading....</option>");
        $.ajax({
            type : 'get',
            url  : "{{ url('api/master/sub-cluster') }}",
            data : {cluster:$("#cluster").val()},
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            dataType : 'json',
            success : function(res) {
                if(res.api_status == 1){
                    var optionSubCluster ='';
                    res.data.forEach(element => {
                        optionSubCluster += "<option value=\"" + element.value + "\" >" + element.name + "</option>";
                    });
                    $("#sub_cluster").html(optionSubCluster);
                    getDasar();
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
    function getDasar(){
        $("#ba_dasar").html("<option>Loading....</option>");
        $.ajax({
            type : 'get',
            url  : "{{ url('api/berita-acara/ba-dasar') }}",
            data : {sub_cluster:$("#sub_cluster").val()},
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            dataType : 'json',
            success : function(res) {
                if(res.api_status == 1){
                    var optionSubCluster ='';
                    res.data.forEach(element => {
                        optionSubCluster += "<option value=\"" + element.value + "\" >" + element.name + "</option>";
                    });
                    $("#ba_dasar").html(optionSubCluster);
                }else{
                    resendToken(res);
                    alertError(res.api_message);
                    $("#ba_dasar").html("");
                }
            },
            error : function(err){
                $("#ba_dasar").html("");
                alertError(err.statusText);
            }
        });
    }

    function getUCabang(kd_cabang = null, cluster = null, subcluster = null, ba_dasar = null ){
        $("#uKd_cabang").html("<option>Loading....</option>");
        $.ajax({
            type : 'get',
            url  : "{{ url('api/master/cabang') }}",
            data : {kd_regional:$("#uKd_regional").val()},
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            dataType : 'json',
            success : function(res) {
                if(res.api_status == 1){
                    var optionCabang ='';
                    res.data.forEach(element => {
                        optionCabang += "<option value=\"" + element.value + "\" >" + element.name + "</option>";
                    });
                    $("#uKd_cabang").html(optionCabang);
                    if(kd_cabang)
                    $("#uKd_cabang").val(kd_cabang);

                    getUCluster(cluster, subcluster, ba_dasar);
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

    function getUCluster(cluster = null, subcluster = null, ba_dasar = null){
        $("#uCluster").html("<option>Loading....</option>");
        $.ajax({
            type : 'get',
            url  : "{{ url('api/master/cluster') }}",
            data : {kd_cabang:$("#uKd_cabang").val()},
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            dataType : 'json',
            success : function(res) {
                if(res.api_status == 1){
                    var optionCluster ='';
                    res.data.forEach(element => {
                        optionCluster += "<option value=\"" + element.value + "\" >" + element.name + "</option>";
                    });
                    $("#uCluster").html(optionCluster);
                    if(cluster)
                    $("#uCluster").val(cluster);
                    getUSubCluster(subcluster, ba_dasar);
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

    function getUSubCluster(subcluster = null, ba_dasar = null){
        $("#uSub_cluster").html("<option>Loading....</option>");
        $.ajax({
            type : 'get',
            url  : "{{ url('api/master/sub-cluster') }}",
            data : {cluster:$("#uCluster").val()},
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            dataType : 'json',
            success : function(res) {
                if(res.api_status == 1){
                    var optionSubCluster ='';
                    res.data.forEach(element => {
                        optionSubCluster += "<option value=\"" + element.value + "\" >" + element.name + "</option>";
                    });
                    $("#uSub_cluster").html(optionSubCluster);
                    if(subcluster)
                    $("#uSub_cluster").val(subcluster);
                    getUDasar(ba_dasar);
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

    function getUDasar(ba_dasar = null){
        $("#uBa_dasar").html("<option>Loading....</option>");
        $.ajax({
            type : 'get',
            url  : "{{ url('api/berita-acara/ba-dasar') }}",
            data : {sub_cluster:$("#uSub_cluster").val(), id: pk},
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            dataType : 'json',
            success : function(res) {
                if(res.api_status == 1){
                    var optionSubCluster ='';
                    res.data.forEach(element => {
                        optionSubCluster += "<option value=\"" + element.value + "\" >" + element.name + "</option>";
                    });
                    $("#uBa_dasar").html(optionSubCluster);
                    if(ba_dasar)
                    $("#uBa_dasar").val(ba_dasar);
                }else{
                    resendToken(res);
                    // alertError(res.api_message);
                    $("#ba_dasar").html("");
                }
            },
            error : function(err){
                $("#ba_dasar").html("");
                alertError(err.statusText);
            }
        });
    }

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
                url: "{{url('api/berita-acara/list?status=0')}}",
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
                    var edit    =  `<button onclick="edit(${row.ba_id}, ${row.tipe_ba})" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill ba" title="Ubah">\
                                            <i class="la la-pencil-square"></i>
                                        </button>`;
                    var cancel      =  `<button onclick="doCancel(${row.ba_id}, ${row.tipe_ba})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill ba" title="Cancel">\
                                        <i class="la la-times-circle"></i>
                                    </button>`;
                    return ba + ' ' + edit + ' ' + cancel;
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
                url: "{{url('api/berita-acara/list?status=1')}}",
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
                    var edit    =  `<button onclick="edit(${row.ba_id}, ${row.tipe_ba})" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill ba" title="Ubah">\
                                        <i class="la la-pencil-square"></i>
                                    </button>`;
                    var cancel      =  `<button onclick="doCancel(${row.ba_id}, ${row.tipe_ba})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill ba" title="Cancel">\
                                        <i class="la la-times-circle"></i>
                                    </button>`;
                    return ba + ' ' + edit + ' ' + cancel;
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
                url: "{{url('api/berita-acara/list?status=9')}}",
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
                    var edit    =  `<button onclick="edit(${row.ba_id}, ${row.tipe_ba})" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill ba" title="Ubah">\
                                            <i class="la la-pencil-square"></i>
                                        </button>`;
                    var cancel      =  `<button onclick="doCancel(${row.ba_id}, ${row.tipe_ba})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill ba" title="Cancel">\
                                        <i class="la la-times-circle"></i>
                                    </button>`;
                    return ba + ' ' + edit;
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
    function lihat(ba_id, tipe){
        window.location.href="{{url('berita-acara/detail')}}?id="+ba_id + "&tipe="+tipe + "&mode=view";
    }

    $('#submit').click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');
        var isi = $('.summernote').summernote('code');
        if(isi == '<p><br></p>' || isi == ''){
            showErrorMsg(form, 'danger', 'Isi masih kosong');
            return;
        }
        form.validate({
            rules: {
                sub_cluster: {
                    required: true
                },
                judul:{
                    required: true,
                    maxlength: 50
                },
                tanggal:{
                    required: true
                },
                tipe:{
                    required: true
                },
                ba_dasar:{
                    required: true
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{url('api/berita-acara/simpan')}}",
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            data : {isi: isi},
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 0){
                    resendToken(response);
                    showErrorMsg(form, 'danger', response.api_message);
                }else{
                    showErrorMsg(form, 'success', 'Berhasil');
                    window.location.reload();
                }
                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            },
            error : function(err){
                showErrorMsg(form, 'danger', err.statusText);
                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            }
        });
    });

    $('#uSubmit').click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');
        var isi = $('.uSummernote').summernote('code');
        if(isi == '<p><br></p>' || isi == ''){
            showErrorMsg(form, 'danger', 'Isi masih kosong');
            return;
        }
        form.validate({
            rules: {
                uid: {
                    required: true
                },
                uSub_cluster: {
                    required: true
                },
                uJudul:{
                    required: true,
                    maxlength: 50
                },
                uTanggal:{
                    required: true
                },
                uTipe:{
                    required: true
                },
                uBa_dasar:{
                    required: false
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{url('api/berita-acara/ubah')}}",
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            data : {isi: isi},
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 0){
                    resendToken(response);
                    showErrorMsg(form, 'danger', response.api_message);
                }else{
                    showErrorMsg(form, 'success', 'Berhasil');
                    window.location.reload();
                }
                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            },
            error : function(err){
                showErrorMsg(form, 'danger', err.statusText);
                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            }
        });
    });
    function doCancel(ba_id, tipe_ba){
        swal({
            title: "Apakah Anda yakin?",
            text: "Mengcancel BA ini?",
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
                    url : "{{url('api/berita-acara/cancel')}}",
                    data: { ba_id: ba_id, tipe_ba: tipe_ba },
                    headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
                    type: 'POST',
                    success: function(res, status, xhr, $form) {
                        if(res.api_status == 1){
                            swal({
                                title: "Success!",
                                text: "Berhasil di cancel.",
                                type: "success"
                            }).then((result) => {
                                if (result.value) {
                                    waiting.reload();
                                    finish.reload();
                                    cancel.reload();
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

    function edit(ba_id, tipe_ba){
        $.ajax({
            url : "{{url('api/berita-acara/ubah')}}",
            data: { ba_id: ba_id, tipe_ba: tipe_ba },
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            type: 'GET',
            success: function(res, status, xhr, $form) {
                if(res.api_status == 1){
                    var val = res.data;
                    $("#uJudul").val(val.judul);
                    $("#uid").val(val.ba_id);
                    $("#uTanggal").val(val.tanggal);
                    $("#uTipe").val(val.tipe_ba);
                    $("#uRegional").val(val.kd_regional);
                    $(".uSummernote").summernote("code", val.isi);
                    pk = val.ba_id;
                    getUCabang(val.kd_cabang, val.id_cluster, val.id_sub_cluster, res.ba_dasar );
                    if(val.tipe_ba == 1)
                    $("#vDasar").hide();

                    $("#page-add").hide();
                    $("#page-ubah").show();
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
</script>