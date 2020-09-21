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

class BaController extends MiddleController
{
    var $res = array('api_status'=> 0, 'api_message'=> 'API Error');
    #APPROVAL BA
    public function postApprove(){
        $id     = $this->input('id', 'required');
        $status = $this->input('status', 'required');
        $note   = $this->input('note','max:100');
        $sess = JWTAuth::parseToken()->getPayload();
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        if($status == 9 && $note == ''){
            $this->res['api_message'] = 'Note Wajib Di isi';
            return $this->api_output($this->res);
        }
        #CEK EKSEKUSI
        $ceklast = DB::table('ba_approval')->where('ba_id', $id)->where('status_approval', 0)->where('usr_id', JWTAuth::user()->id)->count();
        if($ceklast == 0){
            $this->res['api_message'] = 'Data sudah di approval';
            return $this->api_output($this->res);
        }
        #UPDATE APPROVE
        $update['status_approval']  = $status;
        $update['note']             = $note;
        $update['tgl_approval']     = new \DateTime();
        $update['application_name'] = $sess['device'];
        DB::table('ba_approval')->where('ba_id', $id)->where('usr_id', JWTAuth::user()->id)->update($update);

        #CEK APPROVAL
        $cek = DB::table('ba_approval')->where('ba_id',$id)->whereIn('status_approval', [0, 9])->count();
        if($cek == 0 && $status == 1){
            #GANTI STATUS APPROVE SUDAH DI APPROVE SEMUA
            $approved['status'] = 1;
            DB::table('berita_acara')->where('ba_id', $id)->update($approved);
        }
        if($status == 9){
            $approved['status'] = 9;
            DB::table('berita_acara')->where('ba_id', $id)->update($approved);
        }
        $this->res['api_status'] = 1;
        $this->res['api_message'] = $status == 9 ? 'Tolak' : 'Approve';
        return $this->api_output($this->res);
    }

    #LIST BA
    public function getList(){
        $status   = Request::input('status');
        $tipe     = Request::input('tipe');
        $nomor_ba = Request::input('nomor_ba');

        $table  = DB::table('berita_acara')
            ->select('ba_tipe.nama as tipe','berita_acara.tipe_ba','berita_acara.flag', 
            'nomor_ba','judul','tanggal','isi','created_at','berita_acara.ba_id')
            ->join('ba_tipe','berita_acara.tipe_ba','=','ba_tipe.tipe_ba')
            ->where('status', $status);
        if($tipe != '' && $tipe !='-all-'){
            $table->where('berita_acara.tipe_ba', $tipe);
        }
        if($nomor_ba != '' && $nomor_ba !='-all-'){
            $table->where('nomor_ba', 'like', "%$nomor_ba%");
        }
        $result = Model::dataTable($table, 'berita_acara.ba_id', 'desc');
        $this->res['api_status']  = 1;
        $this->res['api_message'] = 'success.';
        $this->res['table']       = $result;
        $this->res['nomor_ba']       = $nomor_ba;
        return $this->api_output($this->res);
    }

    #LIST BA
    public function getApproval(){
        $status   = Request::input('status');
        $tipe     = Request::input('tipe');
        $nomor_ba = Request::input('nomor_ba');
        $usr_id   = JWTAuth::user()->id;
        $table  = DB::table('berita_acara')
            ->select('ba_tipe.nama as tipe','berita_acara.tipe_ba','berita_acara.flag', 
            'nomor_ba','judul','tanggal','isi','created_at','berita_acara.ba_id')
            ->join('ba_tipe','berita_acara.tipe_ba','=','ba_tipe.tipe_ba')
            ->join('ba_approval', function ($join) use($status, $usr_id)  {
                $join->on('ba_approval.ba_id','=','berita_acara.ba_id')->on('ba_approval.status_approval','=', DB::raw("'$status'"))
                ->on('ba_approval.usr_id','=',DB::raw(intval($usr_id)));
            });
        if($tipe != '' && $tipe !='-all-'){
            $table->where('berita_acara.tipe_ba', $tipe);
        }
        if($nomor_ba != '' && $nomor_ba !='-all-'){
            $table->where('nomor_ba', 'like', "%$nomor_ba%");
        }
        $result = Model::dataTable($table, 'berita_acara.ba_id', 'desc');
        $this->res['api_status']  = 1;
        $this->res['api_message'] = 'success.';
        $this->res['table']       = $result;
        $this->res['nomor_ba']       = $nomor_ba;
        return $this->api_output($this->res);
    }

    #SIMPAN BA
    public function postSimpan(){
        $tipe_ba     = $this->input('tipe', 'required');
        $judul       = $this->input('judul', 'required');
        $tanggal     = $this->input('tanggal', 'required');
        $isi         = $this->input('isi', 'required');
        $kd_cabang   = $this->input('kd_cabang', 'required');
        $sub_cluster = $this->input('sub_cluster', 'required');

        $ba_dasar = $this->input('ba_dasar', 'required');
        $sess = JWTAuth::parseToken()->getPayload();
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $masterApproval = DB::table('ba_mapproval')->where('kd_cabang', $kd_cabang)->where('tipe_ba', $tipe_ba)->get();
        if(count($masterApproval) == 0){
            $this->res['api_message'] = 'Master BA belum ada.';
            return $this->api_output($this->res);
        }

        #CREATE BERITA ACARA
        #AMBIL SEQ
        $ba_id = Model::seq('berita_acara','ba_id');
        $save['ba_id']            = $ba_id;
        $save['tipe_ba']          = $tipe_ba;
        $save['pk_id']            = $sub_cluster;
        $save['judul']            = $judul;
        $save['tanggal']          = $tanggal;
        $save['isi']              = $isi;
        $save['nomor_ba']         = Pel::createNomor($kd_cabang, $tanggal, $tipe_ba);
        $save['usr_id']           = JWTAuth::user()->id;
        $save['kd_cabang']        = $kd_cabang;
        $save['flag']             = 0;
        $save['application_name'] = $sess['device'];
        $save['created_at']       = new \DateTime();
        $save['status']           = 0;
        #INSERT DATA BA
        DB::table('berita_acara')->insert($save);

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
        
        #CREATE BA DASAR
        $k = 0;
        foreach($ba_dasar as $bad){
            $dasarSave['ba_id']     = $ba_id;
            $dasarSave['id_relasi'] = $bad;
        }
        #INSERT DASAR
        DB::table('ba_relasi')->insert($dasarSave);

        $this->res['api_status']  = 1;
        $this->res['api_message'] = 'success';
        return $this->api_output($this->res);
    }

    public function postCancel(){
        $ba_id   = $this->input('ba_id', 'required');
        $tipe_ba = $this->input('tipe_ba', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $sess = JWTAuth::parseToken()->getPayload();

        $update['status'] = 9;
        DB::table('berita_acara')->where('ba_id', $ba_id)->update($update);

        $updateApp['status_approval']  = 9;
        $updateApp['tgl_approval']     = new \DateTime();
        $updateApp['application_name'] = $sess['device'];
        $updateApp['note']             = "System: Berita Acara Cancel";
        DB::table('ba_approval')->where('ba_id', $ba_id)->update($updateApp);
        
        DB::table('ba_relasi')->where('ba_id', $ba_id)->delete();

        $this->res['api_status']  = 1;
        $this->res['api_message'] = 'success';
        return $this->api_output($this->res);
    }

    #AMBIL DATA YANG DIUBAH
    public function getUbah(){
        #INPUT
        $ba_id   = $this->input('ba_id', 'required');
        $tipe_ba = $this->input('tipe_ba', 'required');
        
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }

        $query = DB::table('berita_acara')
            ->select('berita_acara.ba_id','tsto.kd_regional','tsto.kd_cabang','subcluster.id_sub_cluster', 'subcluster.id_cluster',
                'judul', 'isi', 'tanggal', 'tipe_ba')
            ->join('tsto', 'tsto.kd_cabang', '=', 'berita_acara.kd_cabang')
            ->where('ba_id', $ba_id);
        if($tipe_ba == 1){
            $query->join('inspection_data','inspection_data.kode_periksa', '=', 'berita_acara.pk_id');
            $query->join('subcluster','subcluster.id_sub_cluster', '=', 'inspection_data.id_sub_cluster');
        }else{
            $query->join('subcluster','subcluster.id_sub_cluster', '=', 'berita_acara.pk_id');
        }
        // $query->dd();
        $data = $query->first();
        $ba_dasar = DB::table('ba_relasi')->where('ba_id', $ba_id)->pluck('id_relasi');

        if($query->count() > 0){
            $this->res['api_status']  = 1;
            $this->res['api_message'] = 'success';
            $this->res['data']        = $data;
            $this->res['ba_dasar']    = $ba_dasar;
        }else{
            $this->res['api_message'] = 'Not Found';
        }
        return $this->api_output($this->res);
    }

    #UBAH BA
    public function postUbah(){
        $ba_id       = $this->input('uid', 'required');
        $judul       = $this->input('uJudul', 'required');
        $tanggal     = $this->input('uTanggal', 'required');
        $isi         = $this->input('isi', 'required');

        $ba_dasar = $this->input('uBa_dasar');
        $sess = JWTAuth::parseToken()->getPayload();
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }

        #UBAH BERITA ACARA
        $save['judul']            = $judul;
        $save['tanggal']          = $tanggal;
        $save['isi']              = $isi;
        $save['usr_id']           = JWTAuth::user()->id;
        $save['application_name'] = $sess['device'];
        $save['status']           = 0;

        #UPDATE DATA BA
        DB::table('berita_acara')->where('ba_id',$ba_id)->update($save);

        #CREATE APPROVAL
        $approvalSave['checksum']        = Str::random(10);
        $approvalSave['status_approval'] = 0;
        $approvalSave['tgl_approval']    = new \DateTime();
        $approvalSave['note']            = '';
        #UPDATE APPROVAL
        DB::table('ba_approval')->where('ba_id',$ba_id)->update($approvalSave);


        DB::table('ba_relasi')->where('ba_id',$ba_id)->delete();
        #CREATE BA DASAR
        $k = 0;
        if($ba_dasar){
            foreach($ba_dasar as $bad){
                $dasarSave[$k]['ba_id']     = $ba_id;
                $dasarSave[$k]['id_relasi'] = $bad;
                $k++;
            }
            #INSERT DASAR
            DB::table('ba_relasi')->insert($dasarSave);
        }

        $this->res['api_status']  = 1;
        $this->res['api_message'] = 'success';
        return $this->api_output($this->res);
    }

    public function getBaDasar(){
        #INPUT
        $sub_cluster = $this->input('sub_cluster');
        $id          = $this->input('id'); //exception
        $all         = $this->input('all');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $all = $all ? true : false;
        $relasi = DB::table('ba_relasi')->select('ba_id');
        if($id != ''){
            $relasi->where('ba_id','<>',$id);
        }
        $subcluster = [];
        $subcluster = DB::table('berita_acara')
            ->join('inspection_data','inspection_data.kode_periksa','=','berita_acara.pk_id')
            ->where('tipe_ba', 1)
            ->where('status', 1);
        if($relasi){
            $subcluster->whereNotIn('ba_id', $relasi);
        }
        if($sub_cluster != '-all-' && $sub_cluster !=''){
            $subcluster->where('inspection_data.id_sub_cluster', $sub_cluster);
        }
        $subcluster = $subcluster->get();
        
        $oSubCluster = Pel::makeOption($subcluster, 'ba_id', 'nomor_ba', $all);
        if(count($subcluster) > 0){
            $this->res['api_status']  = 1;
            $this->res['api_message'] = 'success';
            $this->res['data']        = $oSubCluster;
        }else{
            $this->res['api_message'] = 'Data Kosong';
        }

        #SUKSES
        return $this->api_output($this->res);
    }
}