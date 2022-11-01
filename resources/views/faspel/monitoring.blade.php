{!!Pel::headerTitle($title)!!}
<div class="m-content">
    <div id="main_page">
        <div class="row">
            <div class="col-lg-6">
                {!!Pel::portletStart('Filter', false)!!}
                <form id="form-inspeksi">
                    {!!Pel::formSelect('Regional', $regional, 'kd_regional')!!}
                    {!!Pel::formSelect('Cabang', null, 'kd_cabang')!!}
                    {!!Pel::formSelect('Cluster', null, 'cluster')!!}
                    {!!Pel::formSelect('Sub Cluster', null, 'sub_cluster')!!}
                    <div class="row">
                        <div class="col-md-6">
                        {!!Pel::formInput('Tanggal Start', 'text', 'tgl_start', null, 'readonly')!!}
                        </div>
                        <div class="col-md-6">
                        {!!Pel::formInput('Tanggal Finish', 'text', 'tgl_finish', null, 'readonly')!!}
                        </div>
                    </div>
                    {!!Pel::formSubmit('Cek', 'submit')!!}
                </form>
                {!!Pel::portletEnd()!!}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                {!!Pel::portletStart('Monitoring', false)!!}
                <div class="data-table"></div>
                {!!Pel::portletEnd()!!}
            </div>
        </div>
    </div>
    <div id="tanggapan">
        <div class="row">
            <div class="col-lg-12">
                {!!Pel::portletStart('Tanggapan', false,'success', array('min' => true, 'full'=> true, 'close'=> false), 
                "<li class=\"m-portlet__nav-item\">
                    <a href=\"#\" id=\"back\" class=\"m-portlet__nav-link m-portlet__nav-link--icon\">
                        <i class=\"la la-angle-double-left\"></i>
                    </a>
                </li>"
                )!!}
                <div id="view_checklist">
                </div>
                {!!Pel::portletEnd()!!}
            </div>
        </div>
    </div>
</div>
<script>
    var today = new Date();
    var first = true;
    var ready = false;
    $('#tgl_start, #tgl_finish').datepicker({
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
    function main_page(val){
        if(val){
            $('#tanggapan').fadeOut("slow",function(){
                $('#main_page').fadeIn(1000);
            });
        }else{
            $('#main_page').fadeOut("slow",function(){
                $('#tanggapan').fadeIn(1000);
            });
        }
    }
    $(document).ready(function(){
        main_page(true);
        buttonsubmit(true);
        getCabang();
    });
    $("#kd_regional").change(function(){
        buttonsubmit(true);
        getCabang();
    });
    $("#kd_cabang").change(function(){
        buttonsubmit(true);
        getCluster();
    });
    $("#cluster").change(function(){
        buttonsubmit(true);
        getSubCluster();
    });

    function buttonsubmit(status){
        $("#submit").attr('disabled', status);
    }

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
            data : {kd_cabang:$("#kd_cabang").val(), all: true},
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
            data : {cluster:$("#cluster").val(),kd_cabang:$("#kd_cabang").val(), all: true},
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            dataType : 'json',
            success : function(res) {
                if(res.api_status == 1){
                    var optionSubCluster ='';
                    res.data.forEach(element => {
                        optionSubCluster += "<option value=\"" + element.value + "\" >" + element.name + "</option>";
                    });
                    $("#sub_cluster").html(optionSubCluster);
                    buttonsubmit(false);
                    ready = true;
                    if(first) refreshTable();

                    first =false;
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
                url: "{{url('api/inspeksi/monitoring')}}",
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
                    var cek_close = row.jumlah == row.total ? true : false;
                    var ba = `<button onclick="doBa(${row.ba_id}, 1)" class="m-portlet__nav-link btn m-btn m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill ba" title="Berita Acara Pemeriksaan">\
                                        <i class="la la-newspaper-o"></i>
                                    </button>`;
                    var tanggapan = `<button onclick="doTanggapan(${row.kode_periksa})" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill tanggapan" title="Tanggapan">\
                                        <i class="la la-comment-o"></i>
                                    </button>`;
                    var close = `<button onclick="doClose(${row.kode_periksa}, ${cek_close})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill close-inspeksi" title="Close Inspeksi">\
                                        <i class="la la-times-circle"></i>
                                    </button>`;
                    return ba + ' ' + tanggapan + ' ' + close;
                }
            },
            {
                field: 'jumlah',
                title: 'Status',
                template: function(row){
                    let nilai = row.total - row.jumlah;
                    if(row.jumlah == row.total){
                        return `<span class="m-badge m-badge--warning m-badge--wide">
                        Selesai ditanggapi ${nilai + '/' + row.total}
                        </span>`; 
                    }
                    return `<span class="m-badge m-badge--danger m-badge--wide">
                      Belum ditanggapi ${nilai + '/' + row.total}
                    </span>`;
                }
            }, {
                field: 'nama_cluster',
                title: 'Cluster',
                template: function(row){
                    return row.nama_cluster + ' <br>(' + row.nama_sub_cluster + ')';
                }
            }, {
                field: 'tanggal_periksa',
                title: 'Waktu Periksa'
            }
        ],
    });
    $("#submit").click(function(e){
        e.preventDefault();
        $("#submit").addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        refreshTable();
    });
    function refreshTable(){
        datatable.setDataSourceParam('kd_cabang', $("#kd_cabang").val());
        datatable.setDataSourceParam('cluster', $("#cluster").val());
        datatable.setDataSourceParam('sub_cluster', $("#sub_cluster").val());
        datatable.setDataSourceParam('tgl_start', $("#tgl_start").val());
        datatable.setDataSourceParam('tgl_finish', $("#tgl_finish").val());
        datatable.reload();
    }

    function doBa(ba_id, tipe){
        window.location.href="{{url('berita-acara/detail')}}?id="+ba_id + "&tipe="+tipe + "&mode=view";
    }

    function doTanggapan(kode_periksa){
        $.ajax({
            url : "{{url('api/inspeksi/tanggapan')}}",
            data: { _token: "{{ csrf_token() }}", kode_periksa: kode_periksa },
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            type: 'GET',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 0){
                    resendToken(response);
                    alert('Checklist tidak ditemukan');
                }else{
                    $("#view_checklist").html(response.html);
                    main_page(false);
                }
            },
            error : function(err){
                alert(err.statusText);
            }
        });
    }
    $("#back").click(function(e){
        e.preventDefault();
        main_page(true);
    });
    function doClose(kode_periksa, status){
        if(status){
            swal({
                title: "Apakah Anda yakin?",
                text: "Mengclose Inspeksi?",
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
                        url : "{{url('api/inspeksi/close')}}",
                        data: { kode_periksa: kode_periksa },
                        headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
                        type: 'POST',
                        success: function(res, status, xhr, $form) {
                            if(res.api_status == 1){
                                swal({
                                    title: "Success!",
                                    text: "Berhasil di close.",
                                    type: "success"
                                }).then((result) => {
                                    if (result.value) {
                                        datatable.reload();
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
        }else{
            swal({
                title: "Failed!",
                text: "Maaf, belum selesai ditanggapi",
                type: "error"
            });
        }
    }
</script>