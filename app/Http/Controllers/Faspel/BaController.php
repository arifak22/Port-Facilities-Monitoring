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
class BaController extends MiddleController
{
    var $title      = 'Berita Acara';
    var $data       = array();
    var $template   = 'template';
    var $view       = 'ba';

    public function getIndex(){
        $regional = Model::getRegional(Auth::user()->kd_regional);
        $tipe_ba  = DB::table('ba_tipe')->where('manual', 1)->get();
        $all_tipe  = DB::table('ba_tipe')->get();
        $this->data['title']    = $this->title. ' - List';
        $this->data['regional'] = Pel::makeOption($regional, 'kd_regional', 'nama_regional', true);
        $this->data['tipe_ba']  = Pel::makeOption($tipe_ba, 'tipe_ba', 'nama', true);
        $this->data['all_tipe'] = Pel::makeOption($all_tipe, 'tipe_ba', 'nama', true);
        return Pel::load($this->template, $this->view.'/list', $this->data);
    }
    public function getApproval(){
        $all_tipe  = DB::table('ba_tipe')->get();
        $this->data['title']    = $this->title. ' - Approval';
        $this->data['all_tipe'] = Pel::makeOption($all_tipe, 'tipe_ba', 'nama', true);
        return Pel::load($this->template, $this->view.'/approval', $this->data);
    }

    public function pemeriksaan($dataBa, $mode){
        $kode_periksa       = $dataBa->pk_id;
        $dataInspeksi       = DB::table('inspection_data')
            ->select('nama_cabang','nama_cluster','nama_sub_cluster',
                'createu.nama as nama_periksa','tanggal_periksa',
                'createt.nama as nama_tanggapan', 'tanggal_tanggapan',
                'createc.nama as nama_close', 'waktu_close', 'subcluster.suhu', 'subcluster.getaran', 'subcluster.noise',
                'flag')
            ->join('users as createu','createu.id','=','inspection_data.user_create')
            ->leftJoin('users as createt','createt.id','=','inspection_data.user_tanggapan')
            ->leftJoin('users as createc','createc.id','=','inspection_data.user_close')
            ->join('subcluster','subcluster.id_sub_cluster','=','inspection_data.id_sub_cluster')
            ->join('cluster','subcluster.id_cluster','=','cluster.id_cluster')
            ->join('tsto','tsto.kd_cabang','=','cluster.kd_cabang')
            ->where('kode_periksa', $kode_periksa)->first();
        $dataInspeksiDetail = DB::table('inspection_data_detil')
            ->select('nama_fasilitas','kondisi','inspection_data_detil.keterangan','foto', 'suhu', 'getaran', 'noise')
            ->join('object_subcluster','object_subcluster.id_inspection','inspection_data_detil.id_inspection')
            ->join('fasilitas','fasilitas.id_fasilitas','=','object_subcluster.id_fasilitas')
            ->where('kode_periksa', $kode_periksa)->get();
        $this->data['inspeksi']       = $dataInspeksi;
        $this->data['inspeksi_detil'] = $dataInspeksiDetail;
        if($mode=="view"){
            return Pel::load($this->template, $this->view.'/detil', $this->data);
        }else if($mode=="print"){
            #GET TIPE BA
            $mpdfConfig = array(
                'mode' => 'utf-8', 
                'format' => 'A4',
                'margin_top' => 45,     // 30mm not pixel
                'orientation' => 'P'    
            );
            $mpdf = new \Mpdf\Mpdf($mpdfConfig);

            //HEADER
            $htmlHeader = view('pdf/header')->render();
            $mpdf->SetHTMLHeader($htmlHeader);
            
            //FOOTER
            $htmlFooter = view('pdf/footer')->render();
            $mpdf->SetFooter($htmlFooter);

            //ISIAN
            $html = view('pdf/ba-cetak', $this->data)->render();
            $mpdf->WriteHTML($html);

            // Output a PDF file directly to the browser
            $mpdf->Output();
        }
    }

    public function penyelesaian($dataBa, $mode){
        $this->data['dasar'] = DB::table('ba_relasi')
                ->select('berita_acara.ba_id','nomor_ba','tipe_ba')
                ->join('berita_acara','berita_acara.ba_id','=','ba_relasi.id_relasi')
                ->where('ba_relasi.ba_id', $dataBa->ba_id)->get();
        if($mode=="view"){
            return Pel::load($this->template, $this->view.'/detil', $this->data);
        }else if($mode=="print"){
            #GET TIPE BA
            $mpdfConfig = array(
                'mode' => 'utf-8', 
                'format' => 'A4',
                'margin_top' => 45,     // 30mm not pixel
                'orientation' => 'P'    
            );
            $mpdf = new \Mpdf\Mpdf($mpdfConfig);

            //HEADER
            $htmlHeader = view('pdf/header')->render();
            $mpdf->SetHTMLHeader($htmlHeader);
            
            //FOOTER
            $htmlFooter = view('pdf/footer')->render();
            $mpdf->SetFooter($htmlFooter);

            //ISIAN
            $html = view('pdf/ba-cetak', $this->data)->render();
            $mpdf->WriteHTML($html);

            // Output a PDF file directly to the browser
            $mpdf->Output();
        }
    }

    public function getDetail(){
        $id   = $this->input('id', 'required');
        $tipe = $this->input('tipe', 'required');
        $mode = $this->input('mode', 'required');
        #CEK VALID
        if($this->validatorView()){
            return  $this->validatorView(true);
        }
        $this->data['tipe_ba'] = DB::table('ba_tipe')->where('tipe_ba', $tipe)->first();

        #AMBIL DATA
        $queryBa            = DB::table('berita_acara')->where('ba_id',$id)->where('tipe_ba', $tipe);
        $countBa            = $queryBa->count();

        #AMBIL APPROVAL
        $dataApproval       = DB::table('ba_approval')->select('ba_id', 'checksum', 'nama','users.id','status_approval','tgl_approval')
            ->join('users', 'users.id','=','ba_approval.usr_id')
            ->where('ba_id',$id)->get();
        #CEK COUNT
        if($countBa == 0)
        abort(404);

        $dataBa             = $queryBa->first();
            
        $this->data['title']          = $this->title. ' - Detil';
        $this->data['ba']             = $dataBa;
        $this->data['approval']       = $dataApproval;
        if($tipe == 1){
            return $this->pemeriksaan($dataBa, $mode);
        }else{
            return $this->penyelesaian($dataBa, $mode);
        }
    }
}
