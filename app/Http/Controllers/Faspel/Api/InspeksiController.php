<?php  

namespace App\Http\Controllers\Faspel\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MiddleController;

use App;
use Cache;
use Config;
use Crypt;
use DB;
use File;
use Excel;
use Hash;
use Log;
use PDF;
use Request;
use Route;
use Session;
use Storage;
use Schema;
use Validator;
use Str;
use Pel;
use Model;
use JWTAuth;

class InspeksiController extends MiddleController
{
    var $res = array('api_status'=> 0, 'api_message'=> 'API Error');
    #VIEW CHECKLIST
    public function getChecklist(){
        #INPUT
        $id    = $this->input('sub_cluster', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $sess = JWTAuth::parseToken()->getPayload();
        #AMBIL DATA FASILITAS
        $data = DB::table('object_subcluster')
            ->select('object_subcluster.id_sub_cluster','nama_fasilitas', 'object_subcluster.id_fasilitas', 'mandatory', 'id_inspection', 'is_number', 'gambar', 'keterangan')
            ->join('fasilitas','fasilitas.id_fasilitas', '=', 'object_subcluster.id_fasilitas')
            ->where('object_subcluster.id_sub_cluster', $id)
            ->where('object_subcluster.active',1)
            ->get();
        #AMBIL CABANG
        $data_sub = DB::table('cluster')
            ->select('kd_cabang','nama_sub_cluster', 'suhu', 'getaran', 'noise')
            ->join('subcluster','subcluster.id_cluster', '=', 'cluster.id_cluster')
            ->where('id_sub_cluster', $id)
            ->first();


        if(count($data) > 0){
            if($sess['device'] == 'web'){
                #AMBIL VIEW LIST UNTUK WEB
                $out_data['data']  = $data;
                $out_data['id_sub_cluster'] = $id;
                $out_data['data_sub']      = $data_sub;
                $out_data['kondisi'] = array(
                    array('value'=>'', 'name'=>'--- Tidak dicek ---'),
                    array('value'=>'BAIK', 'name'=>'BAIK'),
                    array('value'=>'TIDAK BAIK', 'name'=>'TIDAK BAIK')
                );

                $output = view('api/checklist', $out_data)->render();
                $this->res['html']        = $output;
            }
            $this->res['api_status']     = 1;
            $this->res['api_message']    = 'success';
            $this->res['data']           = $data;
            $this->res['id_sub_cluster'] = $id;
            $this->res['data_sub']      = $data_sub;

        }else{
            $this->res['api_message'] = 'Data Kosong';
        }

        #SUKSES
        return $this->api_output($this->res);
    }

    #AMBIL VIEW TANGGAPAN
    public function getTanggapan(){
        #INPUT
        $kode_periksa = $this->input('kode_periksa','required');
        $sess = JWTAuth::parseToken()->getPayload();
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $data = DB::table('inspection_data_detil')
            ->select('nama_fasilitas','kondisi','inspection_data_detil.keterangan','object_subcluster.id_sub_cluster', 'tanggapan', 'suhu', 'getaran', 'noise','foto','inspection_data_detil.id_inspection')
            ->join('object_subcluster','object_subcluster.id_inspection', '=', 'inspection_data_detil.id_inspection')
            ->join('fasilitas','fasilitas.id_fasilitas', '=', 'object_subcluster.id_fasilitas')
            ->where('kode_periksa', $kode_periksa)->get();
        $data_sub = DB::table('subcluster')->where('id_sub_cluster', $data[0]->id_sub_cluster)->first();
        if(count($data) > 0){
            if($sess['device'] == 'web'){
                #AMBIL VIEW LIST UNTUK WEB
                $out_data['data']         = $data;
                $out_data['kode_periksa'] = $kode_periksa;
                $out_data['data_sub']     = $data_sub;
                $output = view('api/tanggapan', $out_data)->render();
                $this->res['html']        = $output;
            }
            $this->res['api_status']   = 1;
            $this->res['api_message']  = 'success';
            $this->res['data']         = $data;
            $this->res['kode_periksa'] = $kode_periksa;
        }else{
            $this->res['api_message'] = 'Data Kosong';
        }
        
        #OUTPUT
        return $this->api_output($this->res);
    }

    #SIMPAN CHECKLIST
    public function postSimpan(){
        #INPUT
        $id         = $this->input('id_sub_cluster', 'required');
        $kd_cabang  = $this->input('kd_cabang', 'required');
        $kondisi    = $this->input('kondisi');
        $keterangan = $this->input('keterangan');
        $suhu       = $this->input('suhu');
        $getaran    = $this->input('getaran');
        $noise      = $this->input('noise');
        $file       = $this->input('file');
        $sess       = JWTAuth::parseToken()->getPayload();
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $masterApproval = DB::table('ba_mapproval')->where('kd_cabang', $kd_cabang)->where('tipe_ba', 1)->get();
        if(count($masterApproval) == 0){
            $this->res['api_message'] = 'Master BA Pemeriksaan belum ada.';
            return $this->api_output($this->res);
        }
        #DEKLARASI VARIABEL
        $save_detail = null;
        $save        = null;
        $i           = 0;
        $cek         = FALSE;
        $masterflag  = 1;

        #AMBIL SEQ
        $kode_periksa = Model::seq('inspection_data','kode_periksa');

        #PROSES ULANG JUMLAH KONDISI DI ISI
        foreach ($kondisi as $key => $k) {
            $foto_baru = null;
            #CEK JIKA KONDISI DI ISI
            if(!empty($k)){
                
                #INPUT FOTO
                if(!empty($file[$key])){
                    foreach ($file[$key] as $f => $filename) {
                        $ext = explode('.', $filename)[1];
                        $newname = 'inspeksi/'.$kode_periksa.'-'.$key.'-'.$f.Str::random(3).'.'.$ext;
                        Storage::move($filename,  $newname);
                        $foto_baru[$f] = $newname;
                    }
                    $foto_baru = json_encode($foto_baru);
                }
                #DATA DETIL
                $save_detail[$i]['id_inspection'] = $key;
                $save_detail[$i]['kode_periksa']  = $kode_periksa;
                $save_detail[$i]['kondisi']       = $kondisi[$key];
                $save_detail[$i]['keterangan']    = $keterangan[$key];
                $save_detail[$i]['suhu']          = $suhu[$key];
                $save_detail[$i]['getaran']       = $getaran[$key];
                $save_detail[$i]['noise']         = $noise[$key];
                $save_detail[$i]['flag']          = $k == 'BAIK' ? 1 : 0;
                if(!empty($file[$key])){
                    $foto                       = $foto_baru;
                }else{
                    $foto                       = '';                    
                }
                $save_detail[$i]['foto']          = $foto;
                $save_detail[$i]['application_name']    = $sess['device'];
                $cek                            = TRUE; //ada data yang dicek
                ($k == 'BAIK') || $masterflag = 0;
                $i++;
            }
        }

        if($cek){
            #INSERT DATA DETIL
            DB::table('inspection_data_detil')->insert($save_detail);

            #DATA
            $save['kode_periksa']    = $kode_periksa;
            $save['id_sub_cluster']  = $id;
            $save['kd_cabang']       = $kd_cabang;
            $save['flag']            = $masterflag;
            $save['user_create']     = JWTAuth::user()->id;
            $save['owner_priv']      = 9;
            $save['tanggal_periksa'] = new \DateTime();
            if($masterflag == 1){ //jika baik semua = close
                $save['waktu_close']    = new \DateTime();
                $save['user_close']    = JWTAuth::user()->id;
            }
            $save['application_name']    = $sess['device'];
            #INSERT DATA
            DB::table('inspection_data')->insert($save);

            #CREATE BERITA ACARA
            #AMBIL SEQ
            $ba_id = Model::seq('berita_acara','ba_id');
            $subCluster = DB::table('subcluster')->select('nama_sub_cluster','nama_cluster')
                ->join('cluster','cluster.id_cluster','=','subcluster.id_cluster')
                ->where('id_sub_cluster', $id)->first();
            $baSave['ba_id']            = $ba_id;
            $baSave['tipe_ba']          = 1;
            $baSave['pk_id']            = $kode_periksa;
            $baSave['judul']            = "BA Pemeriksaan Fasilitas Pada: ". $subCluster->nama_sub_cluster;
            $baSave['tanggal']          = date('Y-m-d');
            $baSave['isi']              = Pel::isiBa($subCluster);
            $baSave['nomor_ba']         = Pel::createNomor($kd_cabang, date('Y-m-d'), 1);
            $baSave['usr_id']           = JWTAuth::user()->id;
            $baSave['kd_cabang']        = $kd_cabang;
            $baSave['flag']             = 1;
            $baSave['application_name'] = $sess['device'];
            $baSave['created_at']       = new \DateTime();
            $baSave['status']           = 0;
            #INSERT DATA BA
            DB::table('berita_acara')->insert($baSave);

            #CREATE APPROVAL
            $j = 0;
            foreach($masterApproval as $approval){
                $approvalSave[$j]['ba_id']           = $ba_id;
                $approvalSave[$j]['usr_id']          = $approval->usr_id;
                $approvalSave[$j]['checksum']        = Str::random(10);
                $approvalSave[$j]['status_approval'] = 0;
                $approvalSave[$j]['jabatan']         = $approval->jabatan;
                $approvalSave[$j]['urutan_jabatan']  = $approval->urutan_jabatan;
                $j++;
            }
            #INSERT APPROVAL
            DB::table('ba_approval')->insert($approvalSave);

        }


        #CEK DATA ADA YANG DI CEK
        if($cek){
            $this->res['api_status']  = 1;
            $this->res['api_message'] = 'success';
        }else{
            $this->res['api_status']  = 0;
            $this->res['api_message'] = 'Tidak ada data yang di cek';
        }
        #OUTPUT
        return $this->api_output($this->res);
    }

    #SIMPAN TANGGAPAN
    public function postSimpanTanggapan(){
        $kode_periksa = $this->input('kode_periksa', 'required');
        $tanggapan    = $this->input('tanggapan');
        $sess       = JWTAuth::parseToken()->getPayload();
        $cek = false;
        #PROSES ULANG JUMLAH KONDISI DI ISI
        foreach ($tanggapan as $key => $k) {
            if($k){
                $update['tanggapan']         = $k;
                $update['user_tanggapan']    = JWTAuth::user()->id;
                $update['tanggal_tanggapan'] = new \DateTime();
                $update['flag']              = 1;
                $update['application_name']  = $sess['device'];
                DB::table('inspection_data_detil')->where('kode_periksa', $kode_periksa)
                    ->where('id_inspection', $key)->update($update);
                $cek = true;
            }
        }
        if($cek){
            $updateData['user_tanggapan'] = JWTAuth::user()->id;
            $updateData['tanggal_tanggapan'] = new \DateTime();
            DB::table('inspection_data')->where('kode_periksa', $kode_periksa)
                ->update($updateData);
        }
        $this->res['api_status']  = 1;
        $this->res['api_message'] = 'success';
        return $this->api_output($this->res);
    }

    #AMBIL DATA MONITORING
    public function getMonitoring(){
        $kd_cabang   = Request::input('kd_cabang');
        $cluster     = Request::input('cluster');
        $sub_cluster = Request::input('sub_cluster');
        $tgl_start   = Request::input('tgl_start');
        $tgl_finish  = Request::input('tgl_finish');
        
        $table = DB::table('inspection_data')
            // ->select(DB::raw('sum(inspection_data_detil.flag) as jumlah'))
            ->select(DB::raw('sum(faspel_inspection_data_detil.flag) jumlah'),DB::raw('count(faspel_inspection_data_detil.flag) total'), 
                    'nama_cluster','ba_id', 'nama_sub_cluster', 'tanggal_periksa', 'nama as pemeriksa','inspection_data.kode_periksa')
            ->join('users','users.id','=','inspection_data.user_create')
            ->join('subcluster','subcluster.id_sub_cluster','=','inspection_data.id_sub_cluster')
            ->join('cluster','subcluster.id_cluster','=','cluster.id_cluster')
            ->join('berita_acara', function ($join) {
                $join->on('berita_acara.pk_id','=','inspection_data.kode_periksa')->on('berita_acara.tipe_ba','=',DB::raw('1'));
            })
            ->join('inspection_data_detil','inspection_data.kode_periksa','=','inspection_data_detil.kode_periksa')
            ->groupBy('inspection_data.id_sub_cluster','nama_cluster', 'ba_id', 'nama_sub_cluster', 'tanggal_periksa', 'nama','inspection_data.kode_periksa')
            ->where('inspection_data.flag', 0);
        if($kd_cabang){
            $table->where('inspection_data.kd_cabang', $kd_cabang);
        }
        if($cluster != '-all-' && $cluster != ''){
            $table->where('cluster.id_cluster', $cluster);
        }
        if($sub_cluster != '-all-' && $sub_cluster != ''){
            $table->where('inspection_data.id_sub_cluster', $sub_cluster);
        }
        if($tgl_start && $tgl_finish){
            $between = [date($tgl_start. ' 00:00:00'), date($tgl_finish. ' 23:59:59')];
            $table->whereBetween('tanggal_periksa',$between);
        }
        $result = Model::dataTable($table, 'inspection_data.id_sub_cluster', 'desc');
        $this->res['api_status']  = 1;
        $this->res['api_message'] = 'success.';
        $this->res['kd_cabang']   = $kd_cabang;
        $this->res['table']       = $result;
        return $this->api_output($this->res);
    }

    #AMBIL DATA REPORT
    public function getReport(){
        $kd_cabang   = Request::input('kd_cabang');
        $cluster     = Request::input('cluster');
        $sub_cluster = Request::input('sub_cluster');
        $tgl_start   = Request::input('tgl_start');
        $tgl_finish  = Request::input('tgl_finish');
        $status      = Request::input('status');
        
        $table = DB::table('inspection_data')
            // ->select(DB::raw('sum(inspection_data_detil.flag) as jumlah'))
            ->select(DB::raw('sum(faspel_inspection_data_detil.flag) jumlah'),DB::raw('count(faspel_inspection_data_detil.flag) total'), 
                    'nama_cluster','ba_id', 'nama_sub_cluster',
                    'createu.nama as nama_periksa','tanggal_periksa',
                    'createt.nama as nama_tanggapan', 'inspection_data.tanggal_tanggapan',
                    'createc.nama as nama_close', 'waktu_close',
                    'inspection_data.kode_periksa', 'inspection_data.flag')
            ->join('users as createu','createu.id','=','inspection_data.user_create')
            ->leftJoin('users as createt','createt.id','=','inspection_data.user_tanggapan')
            ->leftJoin('users as createc','createc.id','=','inspection_data.user_close')
            ->join('subcluster','subcluster.id_sub_cluster','=','inspection_data.id_sub_cluster')
            ->join('cluster','subcluster.id_cluster','=','cluster.id_cluster')
            ->join('berita_acara', function ($join) {
                $join->on('berita_acara.pk_id','=','inspection_data.kode_periksa')->on('berita_acara.tipe_ba','=',DB::raw('1'));
            })
            ->join('inspection_data_detil','inspection_data.kode_periksa','=','inspection_data_detil.kode_periksa')
            ->groupBy('inspection_data.id_sub_cluster','nama_cluster', 'ba_id', 'nama_sub_cluster',
            'createu.nama','tanggal_periksa',
            'createt.nama', 'inspection_data.tanggal_tanggapan',
            'createc.nama', 'waktu_close',
            'inspection_data.kode_periksa', 'inspection_data.flag');
        if($status != '-all-'){
            $table->where('inspection_data.flag', $status);
        }
        if($kd_cabang){
            $table->where('inspection_data.kd_cabang', $kd_cabang);
        }
        if($cluster != '-all-' && $cluster != ''){
            $table->where('cluster.id_cluster', $cluster);
        }
        if($sub_cluster != '-all-' && $sub_cluster != ''){
            $table->where('inspection_data.id_sub_cluster', $sub_cluster);
        }
        if($tgl_start && $tgl_finish){
            $between = [date($tgl_start. ' 00:00:00'), date($tgl_finish. ' 23:59:59')];
            $table->whereBetween('tanggal_periksa',$between);
        }
        $result = Model::dataTable($table, 'inspection_data.id_sub_cluster', 'desc');
        $this->res['api_status']  = 1;
        $this->res['api_message'] = 'success.';
        $this->res['kd_cabang']   = $kd_cabang;
        $this->res['table']       = $result;
        return $this->api_output($this->res);
    }

    #CLOSE INSPEKSI
    public function postClose(){
        $kode_periksa = $this->input('kode_periksa', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $cek = DB::table('inspection_data_detil')->where('kode_periksa', $kode_periksa)
            ->where('flag', 0)->count();
        if($cek > 0){
            $this->res['api_message'] =  "Maaf, belum selesai ditanggapi";
            return $this->api_output($this->res);
        }
        $update['flag']        = 1;
        $update['waktu_close'] = new \DateTime();
        $update['user_close']  = JWTAuth::user()->id;
        $sukses = DB::table('inspection_data')->where('kode_periksa', $kode_periksa)->update($update);
        if($sukses){
            $this->res['api_message'] = "success";
            $this->res['api_status']  = 1;
        }
        return $this->api_output($this->res);
    }
}