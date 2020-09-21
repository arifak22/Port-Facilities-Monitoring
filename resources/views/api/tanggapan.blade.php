<form id="form_check">
    <input type="hidden" name="kode_periksa" value="{{$kode_periksa}}"/>
    <table id="view-table" width="100%">
        <thead>
            <tr>
                <th>
                    No
                </th>
                <th>
                    Chekclist
                </th>
                <th>
                    Kondisi
                </th>
                <th>
                    Keterangan
                </th>
                <th>
                    Tanggapan
                </th>
                <th>
                    Foto
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key=>$item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item->nama_fasilitas}}</td>
                    <td>{{$item->kondisi}}</td>
                    <td>{{$item->keterangan}}</td>
                    <td>@if($item->kondisi =='TIDAK BAIK')
                        {!!Pel::defaultInput('tanggapan','text',"tanggapan[".$item->id_inspection."]", $item->tanggapan)!!}
                        @endif
                    </td>
                    <td>@if($item->foto)
                        <button onclick="lookFoto({{$item->foto}})" class="btn btn-success m-btn m-btn--icon m-btn--icon-only">
                            <i class="la la-file-photo-o"></i>
                        </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr/>
    {!!Pel::formSubmit('Simpan', 'submit_tanggapan')!!}
</form>
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
    $('#view-table').DataTable();
    function lookFoto(value){
        $("#forFoto").html('');
        value.forEach(function(v,i) {
            $("#forFoto").append(
                `<tr>
                    <td width="50%" valign="middle">
                        <div style="position: relative;">
                            <img width="100%" src="{{Pel::storageUrl()}}${v}" id="here${i}"/>
                        </div>
                    </td>
                </tr>`);
        });
        $('#m_modal_1').modal('show');
    }

    $("#submit_tanggapan").click(function(e){
      e.preventDefault();
      var btn = $(this);
      var form = $(this).closest('form');
      btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
      form.ajaxSubmit({
          url : "{{Pel::baseUrl('api/inspeksi/simpan-tanggapan')}}",
          headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
          type: 'POST',
          success: function(response, status, xhr, $form) {
            if(response.api_status == 1){
                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                showErrorMsg(form, 'success', response.api_message);
                datatable.reload();
                main_page(true);
            }else{
                resendToken(response);
                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                showErrorMsg(form, 'danger', response.api_message);
            }
          },
          error : function(err){
            btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
            showErrorMsg(form, 'danger', err.statusText);
          }
      });
    })
</script>