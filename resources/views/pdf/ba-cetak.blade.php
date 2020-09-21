<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>Cetak Berita Acara</title>
    <link rel="stylesheet" href="{{Pel::customUrl()}}/style-report-pdf.css">
</head>
    <body>
        <div id="content">
            <table width="100%">
                <tr>
                    <td align="center"><b><u>{{$tipe_ba->nama}}</u></b></td>
                </tr>
                <tr>
                    <td align="center">Nomor: {{$ba->nomor_ba}}</td>
                </tr>
            </table>
            <br/>
            <br/>
            <table width="100%">
                <tr>
                    <td>
                        <p>{!!Pel::space(5)!!}  {{Pel::pembukaBa($ba->tanggal)}}
                            {!!$ba->isi!!}<br/>
                            @if($ba->tipe_ba == 1)
                            {!!Pel::space(5)!!}  Dengan detail pemeriksaan & foto terlampir.
                            @else
                            Dengan dasar BA Pemeriksaan berikut: 
                            <ul>
                                @foreach($dasar as $d)
                                    <li>{{$d->nomor_ba}}</li>
                                @endforeach
                            </ul>
                            @endif
                        </p>

                    </td>
                </tr>
            </table>
            
            <br/>
            <br/>
            <br/>
            <table width="100%" class="table2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Approval</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($approval as $key => $ap)
                    <tr>
                        <td align="center">{{$key+1}}</td>
                        <td align="center">{{$ap->nama}}</td>
                        <td align="center">{{$ap->tgl_approval}}</td>
                        <td align="center">
                            @if($ap->status_approval == 1)
                            <img height="130px" src="{{Pel::createQr(json_encode(array('tipe'=>'ba_faspel','data'=>$ap)))}}"/>
                            @elseif($ap->status_approval == 0)
                            Belum Approve
                            @else
                            Ditolak
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($ba->tipe_ba == 1)
                <pagebreak/>
                <table width="100%">
                    <tr>
                        <td>
                            <p><u>Lampiran Pemeriksaan :</u></p>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%;" class="table2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Fasilitas</th>
                            <th>Kondisi</th>
                            <th>Keterangan</th>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br/>
                <br/>
                <table width="100%">
                    <tr>
                        <td>
                            <p><u>Lampiran Foto :</u></p>
                        </td>
                    </tr>
                </table>
                @foreach($inspeksi_detil as $i => $id)
                    <?php
                        if($id->foto){
                            $foto = json_decode($id->foto);
                        }else{
                            $foto = [];
                        }
                        $count = count($foto);
                        $j     = 1;
                    ?>
                    @if($count>0)
                        <table style="width: 100%;" class="table1">
                            <thead>
                                <tr>
                                    <th colspan="2">{{$id->nama_fasilitas}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($foto as $f)
                                @if($j == $count && $j%2 == 1)
                                    <tr>
                                        <td colspan="2" align="center"> <img style="width:250px;top:0px;max-height:300px" src="{{Pel::storageUrl($f)}}"> </td>
                                    </tr>
                                @elseif($j%2==1)
                                    <tr>
                                        <td align="center"> <img style="width:250px;top:0px;max-height:300px" src="{{Pel::storageUrl($f)}}"> </td>
                                @else
                                        <td align="center"> <img style="width:250px;top:0px;max-height:300px" src="{{Pel::storageUrl($f)}}"> </td>
                                    </tr>
                                @endif
                                <?php $j++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endforeach
            @endif
        </div>
    </body>
</html>