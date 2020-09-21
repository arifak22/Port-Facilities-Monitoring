<style>
    .remove-image:hover{
        cursor: pointer;
    }
</style>
{!!Pel::headerTitle($title)!!}
<div class="m-content">
    <div id="filter">
        <div class="row">
            <div class="col-lg-3">
            </div>
            <div class="col-lg-6">
                {!!Pel::portletStart('Filter Cluster', false)!!}
                <form id="form-inspeksi">
                    {!!Pel::formSelect('Regional', $regional, 'kd_regional')!!}
                    {!!Pel::formSelect('Cabang', null, 'kd_cabang')!!}
                    {!!Pel::formSelect('Cluster', null, 'cluster')!!}
                    {!!Pel::formSelect('Sub Cluster', null, 'sub_cluster')!!}
                    {!!Pel::formSubmit('Cek Fasilitas', 'submit')!!}
                </form>
                {!!Pel::portletEnd()!!}
            </div>
            <div class="col-lg-3">
            </div>
        </div>
    </div>
    <div id="checklist">
        <div class="row">
            <div class="col-lg-12">
                {!!Pel::portletStart('Inspeksi Fasilitas Pelabuhan', false,'success', array('min' => true, 'full'=> true, 'close'=> false), 
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
<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            Upload Foto
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">
              &times;
            </span>
          </button>
        </div>
        <div class="modal-body">
            <table class="table">
                <tbody id="forFoto">
                    
                </tbody>
            </table>
            <div class="custom-file" id="ready">
                <input type="file" class="custom-file-input" id="customFile">
                <label class="custom-file-label" for="customFile">
                    Choose file
                </label>
            </div>
            <center id="loading"><div class="m-loader m-loader--success" style="width: 30px; display: inline-block;"></div></center>
        </div>
      </div>
    </div>
</div>
<script>
    var id_inspeksi = null;
    var foto_file   = {};
    var filename    = {};
    function is_loading(param){
        if(param){
            $("#ready").hide();
            $("#loading").show();
        }else{
            $("#ready").show();
            $("#loading").hide();
        }
    }
    function showModal(id){
        is_loading(false);
        id_inspeksi = id;
        appendFoto(id_inspeksi);
        $('#m_modal_1').modal('show');
    }
    function appendFoto(id){
        var html = '';
        $("#forFoto").html('');
        if(typeof foto_file[id] === 'undefined'){

        }else{
            foto_file[id].forEach(function(v,i) {
                $("#forFoto").append(
                    `<tr>
                        <td width="50%" valign="middle">
                            <div style="position: relative;">
                                <img width="100%" id="here${i}"/>
                                <img onclick="removeImage(${i})" class="remove-image" width="25px" src="{{Pel::customUrl('img/remove.svg')}}" style="position: absolute; top: 4px; right: 5px"/>
                            </div>
                        </td>
                    </tr>`)
                    .promise().done(function() {
                        var fr = new FileReader();
                        fr.onload = function(ev2) {
                            $('#here'+i).attr('src', ev2.target.result);
                        }
                        fr.readAsDataURL(v);
                    });
            })
        }
    }
    function updateCount(id){
        var count = foto_file[id].length;
        $("#count_"+id).html("Upload ("+count+")");
        console.log( foto_file[id])
    }
    function removeImage(i){
        var formData = new FormData();
        formData.append("filename", filename[id_inspeksi][i]);
        formData.append("_token", "{{ csrf_token() }}");
        $.ajax({
            url: "{{url('inspeksi/remove-upload')}}",
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(res)
            {
                console.log('deleted image')
            },
            error: function(err){
                alertError(err.statusText);
            }
        });
        delete foto_file[id_inspeksi][i];
        delete filename[id_inspeksi][i];
        foto_file[id_inspeksi] = foto_file[id_inspeksi].filter(function (el) {
            return el != null;
        });
        filename[id_inspeksi] = filename[id_inspeksi].filter(function (el) {
            return el != null;
        });

        updateCount(id_inspeksi);
        appendFoto(id_inspeksi);
    }
    
    $("#customFile").change(function(){
        is_loading(true);
        const imageCompressor = new ImageCompressor();
        var i = 0;
        var type = $(this)[0].files[i].type;
        var size = $(this)[0].files[i].size;
        if (type == "image/jpeg" || type == "image/png") {
            if (size > 2097152*3) {
                $(this)[0].value = ""
                swal({
                    title: "Failed!",
                    text: "Gambar tidak boleh melebihi 6MB",
                    type: "error"
                });
            } else {
                imageCompressor.compress($(this)[0].files[i], {
                    quality: .8,
                    maxWidth: 1600,
                    maxHeight: 1600
                })
                .then((result) => {
                    $(this)[0].files[i] =null;
                    $(this)[0].value =null;
                    $(".custom-file-label").html('');
                    typeof foto_file[id_inspeksi] === 'undefined' && (foto_file[id_inspeksi] = []);
                    typeof filename[id_inspeksi] === 'undefined' && (filename[id_inspeksi] = []);
                    var formData = new FormData();
                    formData.append('file', result);
                    formData.append("_token", "{{ csrf_token() }}");
                    formData.append("id_inspeksi", id_inspeksi);
                    $.ajax({
                        url: "{{url('inspeksi/upload')}}",
                        type: 'POST',
                        data: formData,
                        cache: false,
                        dataType: 'json',
                        processData: false, // Don't process the files
                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                        success: function(res)
                        {
                            foto_file[id_inspeksi].push(result);
                            filename[id_inspeksi].push(res.file);
                            var i = foto_file[id_inspeksi].length - 1;
                            var last = foto_file[id_inspeksi][foto_file[id_inspeksi].length - 1];
                            $("#forFoto").append(
                            `<tr>
                                <td width="50%" valign="middle">
                                    <div style="position: relative;">
                                        <img width="100%" id="here${i}"/>
                                        <img onclick="removeImage(${i})" class="remove-image" width="25px" src="{{Pel::customUrl('img/remove.svg')}}" style="position: absolute; top: 4px; right: 5px"/>
                                    </div>
                                </td>
                            </tr>`)
                            .promise().done(function() {
                                var fr = new FileReader();
                                fr.onload = function(ev2) {
                                    $('#here'+i).attr('src', ev2.target.result);
                                }
                                fr.readAsDataURL(last);
                            });
                            updateCount(id_inspeksi);
                            is_loading(false);
                        },
                        error: function(err){
                            alertError(err.statusText);
                        }
                    });
                }).catch((err) => {
                    is_loading(false);
                    console.log(err)
                })
            }
        } else {
            $(this)[0].value = ""
            swal({
                title: "Failed!",
                text: "Hanya bisa berupa gambar jpg dan png",
                type: "error"
            });
        }
    });
    $(document).ready(function(){
        buttonsubmit(true);
        getCabang();
        $("#checklist").hide();
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
                    buttonsubmit(false);
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

    $('#submit').click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');

        form.validate({
            rules: {
                kd_regional: {
                    required: true
                },
                kd_cabang: {
                    required: true
                },
                cluster: {
                    required: true
                },
                sub_cluster: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{url('api/inspeksi/checklist')}}",
            data: { _token: "{{ csrf_token() }}" },
            headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
            type: 'GET',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 0){
                    resendToken(response);
                    showErrorMsg(form, 'danger', 'Checklist tidak ditemukan');
                }else{
                    $("#checklist").find(".m-portlet__head-text").html('Inspeksi Fasilitas Pelabuhan ('+response.data_sub.nama_sub_cluster+')');
                    $("#view_checklist").html(response.html);
                    $('#filter').fadeOut("slow",function(){
                        $('#checklist').fadeIn(1000);
                    });
                }
                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            },
            error : function(err){
                showErrorMsg(form, 'danger', err.statusText);
                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            }
        });
    });

    $("#back").click(function(e){
        e.preventDefault();
        $('#checklist').fadeOut("slow",function(){
            $('#filter').fadeIn(1000);
        });
    })
    
</script>