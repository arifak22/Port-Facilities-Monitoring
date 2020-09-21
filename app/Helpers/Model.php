<?php
	
namespace App\Helpers;

use App;
use Cache;
use Config;
use DB;
use Excel;
use File;
use Hash;
use Log;
use Mail;
use PDF;
use Request;
use Route;
use Session;
use Storage;
use Schema;
use Validator;
use Auth;

class Model
{
    /**
     * 
     * MODEL
     */


    #DATATABLE
    public static function dataTable($query, $field_sort, $sort='desc'){
        $table = $query;
        $datatable = array_merge(array('pagination' => array(), 'sort' => array(), 'query' => array()), Request::all());

        #SEARCHING
		$filter = isset($datatable['query']['generalSearch'])&&is_string($datatable['query']['generalSearch'])?$datatable['query']['generalSearch']:'';
		$field_filter = isset($datatable['query']['Field'])&&is_string($datatable['query']['Field'])?$datatable['query']['Field']:'';
		if (!empty($filter)&&$filter!="") {
            $table->where($field_filter, 'like', "%$filter%");
            $query->where($field_filter, 'like', "%$filter%");
        }
        $total = $query->count();

        #SORTING
        $sort  = !empty($datatable['sort']['sort'])?$datatable['sort']['sort']:$sort;
        $field = !empty($datatable['sort']['field'])?$datatable['sort']['field']:$field_sort;
        $table->orderBy($field, $sort);        
        $table->orderBy($field_sort, $sort);   

        $page    = !empty($datatable['pagination']['page'])?(int)$datatable['pagination']['page']:1;
		$perpage = !empty($datatable['pagination']['perpage'])?(int)$datatable['pagination']['perpage']:-1;
        
        $pages = 1;
        if ($perpage > 0) {
			$pages  = ceil($total/$perpage ); // calculate total pages
			$page   = max($page,1); // get 1 page when $_REQUEST['page'] <= 0
			$page   = min($page,$pages); // get last page when $_REQUEST['page'] > $totalPages
			$offset = ($page-1)*$perpage;
			if ($offset < 0) {
				$offset = 0;
            }
            $table->offset($offset)->limit($perpage);
        }
        $data = $table->get();

        $meta    = array();
        $meta = array(
			'page'    => $page,
			'pages'   => $pages,
			'perpage' => $perpage,
			'total'   => $total,
        );
        $result = array(
			'meta' => $meta + array(
					'sort'  => $sort,
					'field' => $field,
				),
			'data' => $data
        );
        return $result;
    }

    #GET REGIONAL
    public static function getRegional($kd_regional){
        $regional = [];
        if($kd_regional == 0){
            $regional = DB::table('tsto')->select('kd_regional','nama_regional')->distinct('kd_regional')->get();
        }else{
            $regional = DB::table('tsto')->select('kd_regional','nama_regional')->distinct('kd_regional')->where('kd_regional', $kd_regional)->get();
        }
        return $regional;
    }

    #GET SEQUENCE
    public static function seq($nama_seq, $value, $prefix= ''){
        $id = DB::table($nama_seq)->max($value);
        $id = $id + 1;
        // $id = DB::getSequence()->nextValue($prefix.$nama_seq);
        return $id;
    }

    public static function seqWhere($nama_seq, $value, $prefix= '', $where = ''){
        $id = DB::table($nama_seq)->where($where)->max($value);
        $id = $id + 1;
        // $id = DB::getSequence()->nextValue($prefix.$nama_seq);
        return $id;
    }
    

    #GENERATE NOSAMPAH
    public static function nosampah($id_lokasi, $year){
        $lokasi = DB::table('lokasis')->where('id_lokasi','=',$id_lokasi)->first();
        $data   = DB::table('nosampahs')->where('kode_lokasi',$lokasi->kode_lokasi)->where('tahun','=',$year);
        
        if($data->count() == 0){ //BELUM ADA NOMOR
            $result  = $lokasi->kode_lokasi .substr($year,2). '-'. sprintf("%04d", 1);
            $save['kode_lokasi'] = $lokasi->kode_lokasi;
            $save['tahun']       = $year;
            $save['seq']         = 1;
            $save['result']      = $result;
            DB::table('nosampahs')->insert($save);
        }else{
            $old = $data->first();
            $seq = $old->seq + 1;
            $result = $lokasi->kode_lokasi .substr($year,2). '-'. sprintf("%04d", $seq);
            $update['seq']         = $seq;
            $update['result']      = $result;
            DB::table('nosampahs')->where('kode_lokasi', $lokasi->kode_lokasi)->where('tahun', $year)->update($update);
        }
        return $result;
    }

    #GENERATE NOPILAHAN
    public static function nopilahan($id_lokasi, $id_jenis, $year){
        $lokasi = DB::table('lokasis')->where('id_lokasi','=',$id_lokasi)->first();
        $jenis  = DB::table('jenis')->where('id_jenis','=',$id_jenis)->first();
        $data   = DB::table('nopilahans')->where('kode_lokasi',$lokasi->kode_lokasi)->where('kode_jenis',$jenis->kode_jenis)->where('tahun','=',$year);
        
        if($data->count() == 0){ //BELUM ADA NOMOR
            $result  = $lokasi->kode_lokasi . $jenis->kode_jenis .substr($year,2). '-'. sprintf("%04d", 1);
            $save['kode_lokasi'] = $lokasi->kode_lokasi;
            $save['kode_jenis'] = $jenis->kode_jenis;
            $save['tahun']       = $year;
            $save['seq']         = 1;
            $save['result']      = $result;
            DB::table('nopilahans')->insert($save);
        }else{
            $old = $data->first();
            $seq = $old->seq + 1;
            $result = $lokasi->kode_lokasi . $jenis->kode_jenis .substr($year,2). '-'. sprintf("%04d", $seq);
            $update['seq']         = $seq;
            $update['result']      = $result;
            DB::table('nopilahans')->where('kode_lokasi', $lokasi->kode_lokasi)->where('kode_jenis',$jenis->kode_jenis)->where('tahun', $year)->update($update);
        }
        $res['result'] = $result;
        $res['jenis']  = $jenis->nama_jenis;
        return $res;
    }

    public static function getListMT($status, $tipe, $cabang, $bulan){
        $query = DB::table('maintenances')
        ->select('id_mt','nama_lokasi','kode_cctv','nama_cctv','waktu','kegiatan','code_mt',
        'maintenances.created_at','ucreate.nama AS created_name','ufinish.nama AS finish_name',
        'foto_before', 'foto_after',
        'finish_at');
        $query->join('lokasis', 'maintenances.id_lokasi', '=', 'lokasis.id_lokasi')
            ->join('cctvs','maintenances.id_cctv','=','cctvs.id_cctv')
            ->join('users as ucreate', 'ucreate.username', '=', 'maintenances.user_created') 
            ->leftJoin('users as ufinish', 'ufinish.username', '=', 'maintenances.user_finish');
        if($tipe === 'bulan'){
            if($cabang != '')
            $query->where('maintenances.id_cabang', '=',$cabang);

            $query->whereYear('waktu','=',substr($bulan, 0, 4));
            $query->whereMonth('waktu','=',substr($bulan, 5, 2));
        }else if($tipe === 'hari'){

        }
        if(!($status === null || $status === '' || $status ===' ')){
            $query->where('maintenances.code_mt','=',$status);
        }
        $query->whereNull('maintenances.deleted_at');
        return $query;
    }
}