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
                @if($data_sub->suhu)
                <th>
                    Suhu (&#8451;)
                </th>
                @endif
                @if($data_sub->getaran)
                <th>
                    Getaran (Rpm)
                </th>
                @endif
                @if($data_sub->noise)
                <th>
                    Noise (dbA)
                </th>
                @endif
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

                  @if($data_sub->suhu)
                    <td>{!!Pel::defaultInput('suhu','number',"suhu[".$item->id_inspection."]")!!}</td>
                  @else
                    {!!Pel::formHidden('suhu')!!}
                  @endif

                  @if($data_sub->getaran)
                    <td>{!!Pel::defaultInput('getaran','number',"getaran[".$item->id_inspection."]")!!}</td>
                  @else
                    {!!Pel::formHidden('getaran')!!}
                  @endif
                  
                  @if($data_sub->noise)
                    <td>{!!Pel::defaultInput('noise','number',"noise[".$item->id_inspection."]")!!}</td>
                  @else
                    {!!Pel::formHidden('noise')!!}
                  @endif
                  
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
          <center><div id="page-list"></div></center>
      </div>
    </div>
  </div>
</div>
<script>
  $(".open-img").click(function(e){
    e.preventDefault();
  })
  let list_gambar = [];
  function openimg(id){
    if(id){
      let list = JSON.parse(id);
      list_gambar = id;
      html = "";
      list.forEach((value, index) => {
        no = index + 1;
        html += `  <a style="font-size:18px; margin-right:10px;" href="#" onclick="toPage(${index})">${no}</a>  `;
      });
      $("#page-list").html(html);
      $('#modal-img').modal('show');
      $('.image-show').attr('src', '{{Pel::storageUrl()}}/'+JSON.parse(id)[0]);
    }
  }
  
  function toPage(nomor){
      // e.preventDefault();
      $('.image-show').attr('src', '{{Pel::storageUrl()}}/'+JSON.parse(list_gambar)[nomor]);
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