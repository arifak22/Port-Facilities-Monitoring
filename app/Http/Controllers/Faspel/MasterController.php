<?php  
namespace App\Http\Controllers\Faspel;

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
use Auth;
use Pel;
use Model;
use URL;
use Mail;
use Str;

class MasterController extends MiddleController
{
    var $title      = 'Master';
    var $data       = array();
    var $template   = 'template';
    var $view       = 'master';

    public function getInspeksi(){
        $cabang = DB::table('tsto')->select('kd_cabang','nama_cabang')->orderBy('nama_cabang')->whereNotNull('kd_cabang')->get();
        $this->data['title'] = $this->title. ' Inspeksi';
        $this->data['cabang'] = Pel::makeOption($cabang, 'kd_cabang', 'nama_cabang', false);
        return Pel::load($this->template, $this->view.'/inspeksi', $this->data);
    }
    public function postTambahCluster(){
        $kd_cabang = $this->input('kd_cabang', 'required');
        $cluster   = $this->input('tCluster', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        #AMBIL SEQ
        $id_cluster = Model::seq('cluster','id_cluster');

        $save['id_cluster']   = $id_cluster;
        $save['kd_cabang']    = $kd_cabang;
        $save['nama_cluster'] = $cluster;
        $save['koordinat']    = '-';
        DB::table('cluster')->insert($save);

        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function postTambahSubcluster(){
        $cluster    = $this->input('cluster', 'required');
        $subcluster = $this->input('tSubCluster', 'required');
        $suhu       = $this->input('tSuhu', 'required');
        $getaran    = $this->input('tGetaran', 'required');
        $noise      = $this->input('tNoise', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        #AMBIL SEQ
        $id_sub_cluster = Model::seq('subcluster','id_sub_cluster');

        $save['id_sub_cluster']   = $id_sub_cluster;
        $save['id_cluster']       = $cluster;
        $save['nama_sub_cluster'] = $subcluster;
        $save['suhu']             = $suhu;
        $save['getaran']          = $getaran;
        $save['noise']            = $noise;
        DB::table('subcluster')->insert($save);

        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function postTambahFasilitas(){
        $id_sub_cluster = $this->input('id_sub_cluster', 'required');
        $fasilitas      = $this->input('fasilitas', 'required');
        $status         = $this->input('status', 'required');
        $keterangan     = $this->input('keterangan');

        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        // $config['allowed_type'] = 'png|jpeg|jpg';
        // $config['max_size']     = '9216';
        // $config['required']     = true;
        // $gambar = $this->uploadFile('gambar', 'fasilitas', Str::random(5).'-'.$id_sub_cluster, $config);
        // if(!$gambar['is_uploaded']){
        //     return $this->api_output($gambar['msg']);
        // }
        #AMBIL SEQ
        $id_fasilitas  = Model::seq('fasilitas','id_fasilitas');
        $id_inspection = Model::seq('object_subcluster','id_inspection');
        $urutan = Model::seqWhere('object_subcluster','urutan',null, [['id_sub_cluster','=', $id_sub_cluster]]);

        $save['id_fasilitas']   = $id_fasilitas;
        $save['nama_fasilitas'] = $fasilitas;
        $save['status']         = $status;
        // $save['gambar']         = $gambar['filename'];
        $save['keterangan']     = $keterangan;
        DB::table('fasilitas')->insert($save);

        $saveObject['id_sub_cluster'] = $id_sub_cluster;
        $saveObject['id_fasilitas']   = $id_fasilitas;
        $saveObject['urutan']         = $urutan;
        $saveObject['active']         = $status;
        $saveObject['id_inspection']  = $id_inspection;
        DB::table('object_subcluster')->insert($saveObject);

        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function postAddGambarFasilitas(){
        $id_fasilitas = $this->input('id_fasilitas');

        $oldFoto = DB::table('fasilitas')->where('id_fasilitas', $id_fasilitas)->value('gambar');
        $config['allowed_type'] = 'png|jpeg|jpg';
        $config['max_size']     = '9216';
        $config['required']     = true;
        $gambar = $this->uploadFile('gambar', 'fasilitas', Str::random(5).'-'.$id_fasilitas, $config);
        if(!$gambar['is_uploaded']){
            return $this->api_output($gambar['msg']);
        }
        
        if($oldFoto){
            $foto = json_decode($oldFoto);
            array_push($foto, $gambar['filename']);
            $save['gambar'] = json_encode($foto);
        }else{
            $save['gambar'] = json_encode(array($gambar['filename']));
        }
        DB::table('fasilitas')->where('id_fasilitas', $id_fasilitas)->update($save);
        
        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }
    
    public function postHapusGambarFasilitas(){
        $id_fasilitas = $this->input('id_fasilitas');
        $nama         = $this->input('nama');
        $oldFoto = DB::table('fasilitas')->where('id_fasilitas', $id_fasilitas)->value('gambar');
        $foto = json_decode($oldFoto);
        if (($key = array_search($nama, $foto)) !== false) {
            unset($foto[$key]);
        }
        if($foto){
            $save['gambar'] = json_encode(array_values($foto));
        }else{
            $save['gambar'] = null;
        }
        DB::table('fasilitas')->where('id_fasilitas', $id_fasilitas)->update($save);

        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function postUbahFasilitas(){
        $id_sub_cluster = $this->input('id_sub_cluster', 'required');
        $id_fasilitas   = $this->input('id_fasilitas', 'required');
        $fasilitas      = $this->input('fasilitas', 'required');
        $status         = $this->input('status', 'required');
        $keterangan     = $this->input('keterangan');

        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }

        $config['allowed_type'] = 'png|jpeg|jpg';
        $config['max_size']     = '9216';
        $config['required']     = false;
        $gambar = $this->uploadFile('gambar', 'fasilitas', Str::random(5).'-'.$id_sub_cluster, $config);
        if(!$gambar['is_uploaded']){
            return $this->api_output($gambar['msg']);
        }

        $save['nama_fasilitas'] = $fasilitas;
        $save['status']         = $status;
        if($gambar['filename']){
            $save['gambar']       = $gambar['filename'];
        }
        $save['keterangan']     = $keterangan;
        DB::table('fasilitas')->where('id_fasilitas', $id_fasilitas)->update($save);

        $saveObject['active']         = $status;
        DB::table('object_subcluster')->where('id_fasilitas', $id_fasilitas)->update($saveObject);

        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function getUbahSubcluster(){
        $id = $this->input('id', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $res['data']        = DB::table('subcluster')->where('id_sub_cluster', $id)->first();
        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function postUbahSubcluster(){
        $id         = $this->input('uidSubCluster', 'required');
        $subcluster = $this->input('uSubCluster', 'required');
        $suhu       = $this->input('uSuhu', 'required');
        $getaran    = $this->input('uGetaran', 'required');
        $noise      = $this->input('uNoise', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }

        $save['nama_sub_cluster'] = $subcluster;
        $save['suhu']             = $suhu;
        $save['getaran']          = $getaran;
        $save['noise']            = $noise;
        DB::table('subcluster')->where('id_sub_cluster', $id)->update($save);

        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function postUbahCluster(){
        $id = $this->input('uidCluster', 'required');
        $cluster   = $this->input('uCluster', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }

        $save['nama_cluster'] = $cluster;
        DB::table('cluster')->where('id_cluster', $id)->update($save);

        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function postHapusSubcluster(){
        $id = $this->input('id', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $cek = DB::table('object_subcluster')->where('id_sub_cluster',$id)->count();
        if($cek > 0){
            $res['api_status']  = 0;
            $res['api_message'] = 'Tidak bisa dihapus, memiliki Objek Fasilitas';
            return $this->api_output($res);
        }

        DB::table('subcluster')->where('id_sub_cluster',$id)->delete();
        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function postHapusCluster(){
        $id = $this->input('id', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $cek = DB::table('subcluster')->where('id_cluster',$id)->count();
        if($cek > 0){
            $res['api_status']  = 0;
            $res['api_message'] = 'Tidak bisa dihapus, memiliki sub cluster';
            return $this->api_output($res);
        }

        DB::table('cluster')->where('id_cluster',$id)->delete();
        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function postHapusFasilitas(){
        $id = $this->input('id', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $cek = DB::table('inspection_data_detil')->where('id_inspection',$id)->count();
        if($cek > 0){
            $res['api_status']  = 0;
            $res['api_message'] = 'Tidak bisa dihapus, memiliki transaksi inspeksi';
            return $this->api_output($res);
        }
        $id_fasilitas = DB::table('object_subcluster')->where('id_inspection',$id)->pluck('id_fasilitas');
        DB::table('object_subcluster')->where('id_inspection',$id)->delete();
        DB::table('fasilitas')->where('id_fasilitas',$id_fasilitas)->delete();
        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function getUbahFasilitas(){
        $id = $this->input('id', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $res['data']        = DB::table('object_subcluster')
            ->select('nama_fasilitas','id_inspection', 'object_subcluster.id_fasilitas','active', 'keterangan')
            ->join('fasilitas', 'fasilitas.id_fasilitas','=','object_subcluster.id_fasilitas')
            ->where('id_inspection', $id)->first();
        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function getFasilitas(){
        $id = $this->input('id', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $data['no'] = 1;
        $data['data'] = DB::table('object_subcluster')
            ->select('object_subcluster.id_fasilitas', 'active', 'id_inspection', 'nama_fasilitas', 'gambar', 'keterangan')
            ->join('fasilitas', 'fasilitas.id_fasilitas','=','object_subcluster.id_fasilitas')
            ->orderBy('nama_fasilitas')
            ->orderBy('keterangan')
            ->where('id_sub_cluster', $id)->get();
        $data['id'] = $id;
        $output = view('master/fasilitas', $data)->render();
        $res['html']        = $output;
        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    /**
     * USER
     */
    #VIEW USER
    public function getUser(){
        $this->data['title'] = $this->title . ' User';
        $this->data['data'] = DB::table('users')
            ->select('id','username','nama','email_address','keterangan','nama_privilege')
            ->join('privileges','privileges.id_privilege','=','users.id_privilege')
            ->where('status','1')->get();
        $this->data['privilege'] = DB::table('privileges')->get();
        $this->data['regional'] = DB::table('tsto')->select(DB::raw('distinct kd_regional, nama_regional'))->orderBy('kd_regional','asc')->get();
        return Pel::load($this->template, $this->view.'/user', $this->data);
    }

    #INSERT USER
    public function postUser(){
        #INPUT
        $username     = Request::input('username');
        $nama         = Request::input('nama');
        $password     = Request::input('password');
        $id_privilege = Request::input('id_privilege');
        $email        = Request::input('email');
        $kd_regional  = Request::input('kd_regional');
        $kd_cabang    = Request::input('kd_cabang');
        $keterangan   = Request::input('keterangan');

        #CEK USERNAME EXIST
        $cek_username = DB::table('users')->where('username', $username)->count();
        if($cek_username > 0){
            $res['api_status']  = 0;
            $res['api_message'] = 'username sudah terpakai.';
            return $this->api_output($res);
        }
        $save['id']            = Model::seq('users','id');
        $save['username']      = $username;
        $save['nama']          = $nama;
        $save['kd_regional']   = $kd_regional;
        if($kd_cabang && $kd_cabang != '-all-')
        $save['kd_cabang']     = $kd_cabang;

        $save['status']        = 1;
        $save['password']      = Hash::make($password);
        $save['id_privilege']  = $id_privilege;
        $save['email_address'] = $email;
        $save['keterangan']    = $keterangan;
        Pel::insert('users', $save);

        #SUKSES
        $res['api_status']  = 1;
        $res['api_message'] = 'Data berhasil ditambahkan.';
        return $this->api_output($res);
    }

    #DELETE USER
    public function getDeleteUser(){
        #INPUT  
        $id = Request::input('id');

        #CHECK FOREIGN, etc.
        try {
    
            $update['status'] = 0;
            $where = array(['id','=', $id]);
            $result = Pel::update('users', $update, $where);
            if($result){
                $res['api_status']  = 1;
                $res['api_message'] = 'Data berhasil dihapus.';
            }else{
                $res['api_status']  = 0;
                $res['api_message'] = 'Mengalami masalah.';
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $res['api_status']  = 0;
            $res['api_message'] = 'DB Error: Query Error.';
        }
        return $this->api_output($res);
    }

    #GET DATA UPDATE
    public function getUserBy(){
        #INPUT  
        $id = Request::input('id');
    
        #GET DATA
        $data = DB::table('users')->where('id', $id);

        #CEK DATA FOUND
        if($data->count() == 0){
            $res['api_status']  = 0;
            $res['api_message'] = 'Data Not Found';
            return $this->api_output($res);
        }

        #SUKSES
        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        $res['data']        = $data->first();
        return $this->api_output($res);
    }

    #UPDATE USER
    public function postUpdateUser(){
        #INPUT
        $id           = Request::input('id');
        $nama         = Request::input('u_nama');
        $id_privilege = Request::input('u_id_privilege');
        $email        = Request::input('u_email');        
        $kd_regional  = Request::input('u_kd_regional');
        $kd_cabang    = Request::input('u_kd_cabang');
        $keterangan   = Request::input('u_keterangan');

        $update['nama']          = $nama;
        $update['id_privilege']  = $id_privilege;
        $update['email_address'] = $email;
        $update['kd_regional']   = $kd_regional;
        if($kd_cabang && $kd_cabang != '-all-')
        $update['kd_cabang']     = $kd_cabang;
        $update['keterangan']    = $keterangan;
        $where = array(['id','=', $id]);
        Pel::update('users', $update, $where);

        #SUKSES
        $res['api_status']  = 1;
        $res['api_message'] = 'Data berhasil diubah.';
        return $this->api_output($res);
    }

    /**
     * MASTER APPROVAL
     */

    public function getApproval(){
        $cabang   = DB::table('tsto')->select('kd_cabang','nama_cabang')->whereNotNull('kd_cabang')->get();
        $all_tipe = DB::table('ba_tipe')->get();
        $user     = DB::table('users')->where('id_privilege','<>','3')->get();
        $this->data['title']    = $this->title. ' Approval';
        $this->data['cabang']   = Pel::makeOption($cabang, 'kd_cabang', 'nama_cabang', false);
        $this->data['all_tipe'] = Pel::makeOption($all_tipe, 'tipe_ba', 'nama', false);
        $this->data['user']     = Pel::makeOption($user, 'id', 'nama', false);
        return Pel::load($this->template, $this->view.'/approval', $this->data);
    }

    public function postTambahApproval(){
        $kd_cabang      = $this->input('kd_cabang', 'required');
        $tipe_ba        = $this->input('tipe_ba', 'required');
        $id_user        = $this->input('id_user', 'required');
        $jabatan = $this->input('jabatan', 'required');

        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }

        #CEK
        $cek = DB::table('ba_mapproval')->where(
            [
                ['kd_cabang', '=', $kd_cabang],
                ['tipe_ba', '=', $tipe_ba],
                ['usr_id', '=', $id_user],
            ]
        )->count();
        
        if($cek > 0){
            $res['api_status']  = 0;
            $res['api_message'] = 'User sudah terdaftar';
            return $this->api_output($res);
        }

        #AMBIL SEQ
        $mid  = Model::seq('ba_mapproval','mid');
        $urutan = Model::seqWhere('ba_mapproval','urutan_jabatan',null, [['kd_cabang','=', $kd_cabang], ['tipe_ba','=', $tipe_ba]]);

        $save['mid']            = $mid;
        $save['kd_cabang']      = $kd_cabang;
        $save['usr_id']         = $id_user;
        $save['tipe_ba']        = $tipe_ba;
        $save['jabatan']        = $jabatan;
        $save['urutan_jabatan'] = $urutan;
        DB::table('ba_mapproval')->insert($save);

        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function postHapusApproval(){
        $id = $this->input('id', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        DB::table('ba_mapproval')->where('mid',$id)->delete();
        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }
    public function getUbahApproval(){
        $id = $this->input('id', 'required');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $res['data']        = DB::table('ba_mapproval')
            ->select('nama','mid', 'jabatan')
            ->join('users','users.id','=','ba_mapproval.usr_id')
            ->where('mid', $id)->first();
        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

    public function postUbahApproval(){
        $id      = $this->input('mid', 'required');
        $jabatan = $this->input('uJabatan', 'required');

        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }

        #AMBIL SEQ
        $save['jabatan']        = $jabatan;
        DB::table('ba_mapproval')->where('mid', $id)->update($save);

        $res['api_status']  = 1;
        $res['api_message'] = 'success';
        return $this->api_output($res);
    }

}
