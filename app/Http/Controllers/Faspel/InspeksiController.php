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

class InspeksiController extends MiddleController
{
    var $title      = 'Inspeksi';
    var $data       = array();
    var $template   = 'template';
    var $view       = 'faspel';

    #INDEX - INSPEKSI FASPEL
    public function getIndex(){
        $regional = Model::getRegional(Auth::user()->kd_regional);
        $this->data['title'] = $this->title. ' Fasilitas';
        $this->data['jwt'] = Pel::getSession('jwt_token');
        $this->data['regional'] = Pel::makeOption($regional, 'kd_regional', 'nama_regional', true);
        return Pel::load($this->template, $this->view.'/inspeksi', $this->data);
    }
    #UPLOAD TEMPORARY
    public function postUpload(){
        $id   = $this->input('id_inspeksi', 'required');
        $file = Request::file('file')->storeAs('temp_inspeksi', $id .'-'.Str::random() .'.'.Request::file('file')->extension());
        #CEK VALID
        if($this->validator()){
            return  $this->validator(true);
        }
        return $this->api_output(array('id'=>$id, 'file'=> $file));
    }
    #REMOVE UPLOAD TEMPORARY
    public function postRemoveUpload(){
        $filename   = $this->input('filename', 'required');
        Storage::delete($filename);
        return $this->api_output(array('status'=>1));
    }


    #MONITORING
    public function getMonitoring(){
        $regional   = Model::getRegional(Auth::user()->kd_regional);
        $this->data['title']    = $this->title. ' Monitoring';
        $this->data['regional'] = Pel::makeOption($regional, 'kd_regional', 'nama_regional', true);
        return Pel::load($this->template, $this->view.'/monitoring', $this->data);
    }

    #REPORT
    public function getReport(){
        $regional   = Model::getRegional(Auth::user()->kd_regional);
        $status = array(
            array('value'=>'-all-', 'name'=>'-- Semua Status --'),
            array('value'=>'1', 'name'=>'Selesai Close'),
            array('value'=>'0', 'name'=>'Belum Selesai')
        );
        $this->data['title']    = $this->title. ' Report';
        $this->data['status']   = $status;
        $this->data['regional'] = Pel::makeOption($regional, 'kd_regional', 'nama_regional', true);
        return Pel::load($this->template, $this->view.'/report', $this->data);
    }
}
