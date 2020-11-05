<form id="form_check">
  <input type="hidden" name="id_sub_cluster" value="{{$id_sub_cluster}}"/>
  <input type="hidden" name="kd_cabang" value="{{$data_sub->kd_cabang}}"/>
  <div class="table-responsive">
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
                    Foto
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key=>$item)
              <tr>
                  <td>{{$key+1}}</td>
                  <td>
                    @if($item->gambar)
                    <a class="open-img" href="" onclick="openimg('{{$item->gambar}}')">{{$item->nama_fasilitas}}</a>
                    <br/> {{$item->keterangan}}
                    @else
                    {{$item->nama_fasilitas}} 
                    <br/> {{$item->keterangan}}
                    @endif
                  </td>
                  <td>{!!Pel::defaultSelect('kondisi',$kondisi,"kondisi[".$item->id_inspection."]")!!}</td>
                  <td>{!!Pel::defaultInput('keterangan','text',"keterangan[".$item->id_inspection."]")!!}</td>
                  <td><button type="button" onclick="showModal('{{$item->id_inspection}}')" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill btn-sm">
                    <span>
                      <i class="la la-cloud-upload"></i>
                      <span id="count_{{$item->id_inspection}}">
                        Upload (0)
                      </span>
                    </span>
                  </button></td>
              </tr>
            @endforeach
        </tbody>
    </table>
  </div>
  <hr/>
{!!Pel::formSubmit('Simpan', 'submit_check')!!}
</form>

<div class="modal fade" id="modal-img" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          Gambar
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">
            &times;
          </span>
        </button>
      </div>
      <div class="modal-body">
          <img width="100%" class="image-show" />
      </div>
    </div>
  </div>
</div>
<script>
  $(".open-img").click(function(e){
    e.preventDefault();
  })
  function openimg(id){
    if(id){
      $('#modal-img').modal('show');
      $('.image-show').attr('src', '{{Pel::storageUrl()}}/'+id);
    }
  }
    $('#view-table').DataTable();
    $("#submit_check").click(function(e){
      e.preventDefault();
      var btn = $(this);
      var form = $(this).closest('form');
      btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
      form.ajaxSubmit({
          url : "{{Pel::baseUrl('api/inspeksi/simpan')}}",
          data: {file: filename },
          headers: {"Authorization": "Bearer " + localStorage.getItem('jwt_token')},
          type: 'POST',
          success: function(response, status, xhr, $form) {
            if(response.api_status == 1){
                btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                showErrorMsg(form, 'success', response.api_message);
                setTimeout(function() {
                  window.location.reload();
                }, 1000);
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