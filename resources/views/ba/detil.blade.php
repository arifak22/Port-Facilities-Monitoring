{!!Pel::headerTitle($title)!!}
<div class="m-content">
    <div class="row">
        <div class="col-lg-6">
            <?php
            $url =url('berita-acara/detail?id='.$ba->ba_id.'&tipe='.$ba->tipe_ba.'&mode=print');
            ?>
            {!!Pel::portletStart('Berita Acara', false, false, false, 
            "<li class=\"m-portlet__nav-item\">
                <a target=\"_blank\" href=\"$url\" class=\"m-portlet__nav-link btn btn-secondary m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill\">
                    <i class=\"la la-print\"></i>
                </a>
            </li>"
            )!!}
                <table class="table">
                    <tr>
                        <td>Nomor</td>
                        <td>{{$ba->nomor_ba}}</td>
                    </tr>
                    <tr>
                        <td>Judul</td>
                        <td>{{$ba->judul}}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>{{$ba->tanggal}}</td>
                    </tr>
                    <tr>
                        <td>Isi</td>
                        <td>{!!$ba->isi!!}</td>
                    </tr>
                    @if($ba->tipe_ba == 2)
                    <tr>
                        <td>BA Dasar</td>
                        <td>
                            <ul>
                                @foreach($dasar as $d)
                                    <li><a href="{{url('berita-acara/detail?id='.$d->ba_id.'&tipe='.$d->tipe_ba.'&mode=view')}}">{{$d->nomor_ba}}</a></li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endif
                </table>
            {!!Pel::portletEnd()!!}
        </div>
        <div class="col-lg-6">
            {!!Pel::portletStart('Approval', false, false, false, null)!!}
                <table class="table">
                    @foreach ($approval as $ap)
                        <tr>
                            <td>{{$ap->nama}}</td>
                            <td>@if($ap->status_approval==1)
                                    {{'Sudah Approve'}}
                                @elseif($ap->status_approval==9)
                                    {{'Ditolak'}}
                                @else
                                    {{'Belum Diapproval'}}
                                @endif
                            </td>
                            <td>
                                @if($ap->id == Auth::user()->id && $ap->status_approval == 0)
                                    <button onclick="approvalBa({{$ap->ba_id}}, 1)" class="btn btn-outline-success m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill m-btn--air">
                                        <i class="la la-check"></i>
                                    </button>
                                    <button onclick="approvalBa({{$ap->ba_id}}, 9)" class="btn btn-outline-danger m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill m-btn--air">
                                        <i class="la la-times"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            {!!Pel::portletEnd()!!}
        </div>
    </div>
    @if($ba->tipe_ba == 1)
    <div class="row">
        <div class="col-lg-12">
            {!!Pel::portletStart('Detail Inspeksi', false, false, false, null)!!}
            <div class="row">
                <div class="col-lg-6">
                    <table class="table">
                        <tr>
                            <td>Cabang</td>
                            <td>{{$inspeksi->nama_cabang}}</td>
                        </tr>
                        <tr>
                            <td>Cluster</td>
                            <td>{{$inspeksi->nama_cluster}}</td>
                        </tr>
                        <tr>
                            <td>Sub Cluster</td>
                            <td>{{$inspeksi->nama_sub_cluster}}</td>
                        </tr>
                        <tr>
                            <td>Pemeriksa</td>
                            <td>{{$inspeksi->nama_periksa}}</td>
                        </tr>
                        <tr>
                            <td>Waktu Periksa</td>
                            <td>{{$inspeksi->tanggal_periksa}}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table class="table">
                        <tr>
                            <td>Status</td>
                            <td>{!!$inspeksi->flag == 0 ? "<span class=\"m-badge m-badge--danger m-badge--wide\">Belum Close</span>" :  "<span class=\"m-badge m-badge--success m-badge--wide\">Closed</span>" !!}</td>
                        </tr>
                        <tr>
                            <td>User Menanggapi</td>
                            <td>{{$inspeksi->nama_tanggapan}}</td>
                        </tr>
                        <tr>
                            <td>Waktu Menanggapi</td>
                            <td>{{$inspeksi->tanggal_tanggapan}}</td>
                        </tr>
                        <tr>
                            <td>User Close</td>
                            <td>{{$inspeksi->nama_close}}</td>
                        </tr>
                        <tr>
                            <td>Waktu Close</td>
                            <td>{{$inspeksi->waktu_close}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <table class="table table-bordered m-table m-table--border-success m-table--head-bg-success">
                <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>
                            Fasilitas
                        </th>
                        <th>
                            Kondisi
                        </th>
                        <th>
                            Keterangan
                        </th>
                        @if($inspeksi->suhu)
                        <th>
                            Suhu (&#8451;)
                        </th>
                        @endif
                        @if($inspeksi->getaran)
                        <th>
                            Getaran (Rpm)
                        </th>
                        @endif
                        @if($inspeksi->noise)
                        <th>
                            Noise (dbA)
                        </th>
                        @endif
                        <th width="5px">
                            
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inspeksi_detil as $i => $id)
                        <tr>
                            <th scope="row">
                                {{$i+1}}
                            </th>
                            <td>
                                {{$id->nama_fasilitas}}
                            </td>
                            <td>
                                {{$id->kondisi}}
                            </td>
                            <td>
                                {{$id->keterangan}}
                            </td>
                            @if($inspeksi->suhu)
                                <td>{!!$id->suhu ?  $id->suhu . ' &#8451;' : ''!!}</td>
                            @endif
                            @if($inspeksi->getaran)
                            <td>{!!$id->getaran ?  $id->getaran . ' Rpm' : ''!!}</td>
                            @endif
                            @if($inspeksi->noise)
                            <td>{!!$id->noise ?  $id->noise . ' dbA' : ''!!}</td>
                            @endif
                            <td>
                            @if($id->foto)
                            <button onclick="lookFoto({{$id->foto}})" class="btn btn-success m-btn m-btn--icon m-btn--icon-only">
                                <i class="la la-file-photo-o"></i>
                            </button>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!!Pel::portletEnd()!!}
        </div>
    </div>
    @endif
</div>
<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            Foto
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
        </div>
      </div>
    </div>
</div>
<script>
    function lookFoto(value){
        $("#forFoto").html('');
        value.forEach(function(v,i) {
            $("#forFoto").append(
                `<tr>
                    <td width="50%" valign="middle">
                        <div style="position: relative;">
                            <img width="100%" src="{{Pel::storageUrl()}}/${v}" id="here${i}"/>
                        </div>
                    </td>
                </tr>`);
        });
        $('#m_modal_1').modal('show');
    }
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
                                    window.location.reload();
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
</script>