<form>
    <table class="table m-table m-table--head-separator-primary">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Fasilitas</th>
                <th>Status</th>
                <th>Gambar</th>
                <th>Keterangan</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
                <tr>
                    <td>#</td>
                    <td>
                    <input type="hidden" name="id_sub_cluster" id="id_sub_cluster" value="{{$id}}">
                    <input type="hidden" name="id_fasilitas" id="id_fasilitas">
                        {!!Pel::defaultInput('Fasilitas', 'text','fasilitas')!!}</td>
                    <td>{!!Pel::defaultSelect('Status', 
                        array(array('name'=>'Aktif', 'value'=>'1'), array('name'=>'Tidak Aktif', 'value'=>'0')),
                        'status')!!}</td>
                    <td></td>
                    <td>{!!Pel::defaultInput('Keterangan', 'text','keterangan')!!}</td>                    
                    <td>
                        <button id="addFasilitas" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill btn-sm pull-right">
                            <span>
                                <span>
                                    Tambah
                                </span>
                            </span>
                        </button>
                        <button id="ubahFasilitas" class="btn btn-warning m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill btn-sm pull-right">
                            <span>
                                <span>
                                    Ubah
                                </span>
                            </span>
                        </button>
                    
                    </td>
                </tr>
            @foreach($data as $d)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$d->nama_fasilitas}}</td>
                <td>{{$d->active ? 'Aktif' : 'Tidak Aktif'}}</td>
                <td>
                    <input id='fileid-{{$d->id_fasilitas}}' type='file' accept="image/*" hidden/>
                    <button onclick="addGambar({{$d->id_fasilitas}})" type="button" class="btn btn-info m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill btn-sm pull-right">
                        Add Gambar
                    </button>
                    @if($d->gambar)
                    <ul>
                        @foreach(json_decode($d->gambar) as $g)
                            <li>
                                <a href="{{Pel::storageUrl($g)}}" target="_blank">File</a> - 
                                <a style="color:red" onclick="hapusFoto('{{$d->id_fasilitas}}','{{$g}}', event)" href="">x</a>
                            </li>
                        @endforeach
                    </ul>
                    @endif
                </td>
                <td>{{$d->keterangan}}</td>
                <td>
                    <button onclick="changeFasilitas({{$d->id_inspection}})" type="button" class="btn btn-warning m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill btn-sm pull-right">
                        <span>
                            <span>
                                Ubah
                            </span>
                        </span>
                    </button>
                    <button onclick="deleteFasilitas({{$d->id_inspection}})" type="button" class="btn btn-danger m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill btn-sm pull-right">
                        <span>
                            <span>
                                Hapus
                            </span>
                        </span>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>
<script>
    
    $(document).ready(function(){
        $("#ubahFasilitas").hide();
    })
    function refreshModal(){
        $("#modal-fasilitas").modal('hide');
        detailSubCluster("{{$id}}");
    }
    $("#addFasilitas").click(function(e){
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');
        // return false;
        form.validate({
            rules: {
                id_sub_cluster: {
                    required: true
                },
                fasilitas: {
                    required: true
                },
                status: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{Pel::baseUrl('master/tambah-fasilitas')}}",
            data: { _token: "{{ csrf_token() }}"},
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 1){
                    setTimeout(function() {
                        btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                        showErrorMsg(form, 'success', response.api_message);
                        setTimeout(function() {
                            refreshModal();
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
    $("#ubahFasilitas").click(function(e){
        e.preventDefault();
        var btn = $(this);
        var form = $(this).closest('form');
        // return false;
        form.validate({
            rules: {
                id_sub_cluster: {
                    required: true
                },
                id_inspection: {
                    required: true
                },
                fasilitas: {
                    required: true
                },
                status: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            return;
        }

        btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        form.ajaxSubmit({
            url : "{{Pel::baseUrl('master/ubah-fasilitas')}}",
            data: { _token: "{{ csrf_token() }}"},
            type: 'POST',
            success: function(response, status, xhr, $form) {
                if(response.api_status == 1){
                    setTimeout(function() {
                        btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                        showErrorMsg(form, 'success', response.api_message);
                        setTimeout(function() {
                            refreshModal();
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
    function deleteFasilitas(id){
        swal({
            title: "Apakah Anda yakin?",
            text: "Menghapus Faasilitas ini?",
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
                    url : "{{url('master/hapus-fasilitas')}}",
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
                                    refreshModal();
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
    var uuid = 0;
    function addGambar(id){
        uuid = id;
        $("#fileid-"+id).click();
        $("#fileid-"+id).change(function (){
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
                        var formData = new FormData();
                        formData.append('id_fasilitas', uuid);
                        formData.append('gambar', result);
                        formData.append('_token', "{{ csrf_token() }}");
                        $.ajax({
                            url : "{{url('master/add-gambar-fasilitas')}}",
                            type : 'POST',
                            data : formData,
                            processData: false,  // tell jQuery not to process the data
                            contentType: false,  // tell jQuery not to set contentType
                            success : function(data) {
                                swal({
                                    title: "Success!",
                                    text: "Gambar Berhasil di upload.",
                                    type: "success"
                                }).then((result) => {
                                    if (result.value) {
                                        refreshModal();
                                    }
                                });
                            }
                        });
                    }).catch((err) => {
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
    }

    function hapusFoto(id_fasilitas, nama, e){
        e.preventDefault();
        $.ajax({
            url : "{{url('master/hapus-gambar-fasilitas')}}",
            data: { id_fasilitas: id_fasilitas, nama: nama, _token: "{{ csrf_token() }}" },
            type: 'POST',
            success: function(res, status, xhr, $form) {
                swal({
                    title: "Success!",
                    text: "Gambar Berhasil di hapus.",
                    type: "success"
                }).then((result) => {
                    if (result.value) {
                        refreshModal();
                    }
                });
            }
        });
    }

     
    function changeFasilitas(id){
        $.ajax({
            url : "{{url('master/ubah-fasilitas')}}",
            data: { id: id, _token: "{{ csrf_token() }}" },
            type: 'GET',
            success: function(res, status, xhr, $form) {
                if(res.api_status == 1){
                    var val = res.data;
                    $("#id_fasilitas").val(val.id_fasilitas);
                    $("#fasilitas").val(val.nama_fasilitas);
                    $("#status").val(val.active);
                    $("#keterangan").val(val.keterangan);
                    $("#addFasilitas").hide();
                    $("#ubahFasilitas").show();

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
        });
    }
</script>