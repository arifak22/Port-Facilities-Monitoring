<style>

.liter_icon {
    position:relative;
}    
.liter_icon:before {
    content: "\f26d";  /* this is your text. You can also use UTF-8 character codes as I do here */
    font-family: LineAwesome;
    right:30px;
    position:absolute;
    font-size: 90px;
    color: #5867dd !important;
    opacity: 0.5;
    top:0;
    }

.transaksi_icon {
    position:relative;
}    
.transaksi_icon:before {
    content: "\f29c";  /* this is your text. You can also use UTF-8 character codes as I do here */
    font-family: LineAwesome;
    right:30px;
    position:absolute;
    font-size: 90px;
    color: #ffb822 !important;
    opacity: 0.5;
    top:0;
}
.go-pointer{
    cursor: pointer;
}
</style>
<div class="m-content">
    <div class="col-xl-12">
        <div style="padding: 0px;" class="m-subheader">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title m-subheader__title--separator">
                    DASHBOARD
                    </h3>
                </div>
            </div>
        </div>
        <!--begin:: Widgets/Quick Stats-->
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6 go-pointer" id="inspeksi">
                <div style="height:120px;margin-bottom:0px;" class="m-portlet m-portlet--half-height m-portlet--border-bottom-primary ">
                    <div class="m-portlet__body liter_icon">
                        <div class="m-widget26">
                            <div class="m-widget26__number">
                                {{$inspeksi}}
                                <small>
                                    Inspeksi belum ditanggapi
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m--space-30"></div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 go-pointer" id="approval">
                <div style="height:120px;margin-bottom:0px;" class="m-portlet m-portlet--half-height m-portlet--border-bottom-warning ">
                    <div class="m-portlet__body transaksi_icon">
                        <div class="m-widget26">
                            <div class="m-widget26__number">
                                {{$ba}}
                                <small>
                                    Approval BA
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m--space-30"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#inspeksi").click(function(){
        window.location.href="{{url('inspeksi/monitoring')}}";
    });
    $("#approval").click(function(){
        window.location.href="{{url('berita-acara/approval')}}";
    })
</script>