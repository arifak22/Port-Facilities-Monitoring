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
use Pel;
use JWTAuth;
use Model;

class MasterController extends MiddleController
{
    var $res = array('api_status'=> 0, 'api_message'=> 'API Error');

    public function getCabang(){
        #INPUT
        $kd_regional = $this->input('kd_regional');
        $all         = $this->input('all');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $all = $all ? true : false;
        #USER SESSION
        $uRegional = JWTAuth::user()->kd_regional;
        $uCabang   = JWTAuth::user()->kd_cabang;

        $cabang = [];
        $cabang = DB::table('tsto')->select('kd_cabang','nama_cabang')->distinct('kd_cabang');
        #JIKA REGIONAL YANG DI PILIH ALL
        if($kd_regional == '-all-'){
            if($uRegional != 0){
                $cabang->where('kd_regional', $uRegional);
            }
        }else{
            $cabang->where('kd_regional', $kd_regional);
        }
        $cabang = $cabang->get();

        $oCabang = Pel::makeOption($cabang, 'kd_cabang', 'nama_cabang', $all);
        if(count($cabang) > 0){
            $this->res['api_status']  = 1;
            $this->res['api_message'] = 'success';
            $this->res['data']        = $oCabang;
        }else{
            $this->res['api_message'] = 'Data Kosong';
        }

        #SUKSES
        return $this->api_output($this->res);
    }

    public function getCluster(){
        #INPUT
        $kd_cabang = $this->input('kd_cabang');
        $all       = $this->input('all');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $all = $all ? true : false;

        $cluster = [];
        $cluster = DB::table('cluster')->where('kd_cabang', $kd_cabang)->orderBy('id_cluster','desc')->get();

        $oCluster = Pel::makeOption($cluster, 'id_cluster', 'nama_cluster', $all);
        if(count($cluster) > 0){
            $this->res['api_status']  = 1;
            $this->res['api_message'] = 'success';
            $this->res['data']        = $oCluster;
        }else{
            $this->res['api_message'] = 'Data Kosong';
        }

        #SUKSES
        return $this->api_output($this->res);
    }

    public function getSubCluster(){
        #INPUT
        $cluster   = $this->input('cluster');
        $kd_cabang = $this->input('kd_cabang');
        $all       = $this->input('all');
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        $all = $all ? true : false;

        $subcluster = [];
        $subcluster = DB::table('subcluster')->join('cluster','cluster.id_cluster','=','subcluster.id_cluster');
        if($cluster != '-all-' && $cluster !=''){
            $subcluster->where('subcluster.id_cluster', $cluster);
        }
        if($kd_cabang != '-all-' && $kd_cabang !=''){
            $subcluster->where('kd_cabang', $kd_cabang);
        }
        $subcluster = $subcluster->get();
        
        $oSubCluster = Pel::makeOption($subcluster, 'id_sub_cluster', 'nama_sub_cluster', $all);
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

    #LIST SUB CLUSTER
    public function getListSubcluster(){
        $id_cluster   = Request::input('cluster');
        $table  = DB::table('subcluster');
        if($id_cluster != '' && $id_cluster !='-all-'){
            $table->where('subcluster.id_cluster', $id_cluster);
        }
        $result = Model::dataTable($table, 'subcluster.id_sub_cluster', 'desc');
        $this->res['api_status']  = 1;
        $this->res['api_message'] = 'success.';
        $this->res['table']       = $result;
        return $this->api_output($this->res);
    }

    #LIST SUB CLUSTER
    public function getListApproval(){
        $kd_cabang = Request::input('kd_cabang');
        $tipe_ba   = Request::input('tipe_ba');
        $table  = DB::table('ba_mapproval')
            ->join('users','users.id','=','ba_mapproval.usr_id')
            ->join('tsto','tsto.kd_cabang','=','ba_mapproval.kd_cabang')
            ->join('ba_tipe','ba_tipe.tipe_ba','=','ba_mapproval.tipe_ba')
            ->select('mid','users.nama as nama', 'ba_mapproval.jabatan', 'nama_cabang', 'ba_tipe.nama as nama_tipe');
        if($kd_cabang != '' && $kd_cabang !='-all-'){
            $table->where('ba_mapproval.kd_cabang', $kd_cabang);
        }
        if($tipe_ba != '' && $tipe_ba !='-all-'){
            $table->where('ba_mapproval.tipe_ba', $tipe_ba);
        }
        $result = Model::dataTable($table, 'ba_mapproval.urutan_jabatan', 'desc');
        $this->res['api_status']  = 1;
        $this->res['api_message'] = 'success.';
        $this->res['table']       = $result;
        return $this->api_output($this->res);
    }
}