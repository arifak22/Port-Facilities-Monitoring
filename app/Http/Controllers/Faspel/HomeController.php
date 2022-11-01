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
use URL;
use Mail;
use Carbon;

class HomeController extends MiddleController
{
    var $title = 'Home';
    public function getLogout(){
        Auth::logout();
        return redirect()->intended('login');
    }

    public function getIndex(){
        $usr_id = Auth::user()->id;
        $data['ba'] = DB::table('berita_acara')
            ->join('ba_approval', function ($join) use($usr_id)  {
                $join->on('ba_approval.ba_id','=','berita_acara.ba_id')->on('ba_approval.status_approval','=', DB::raw("'0'"))
                ->on('ba_approval.usr_id','=',DB::raw(intval($usr_id)));
            })->count();
        $data['inspeksi'] = DB::table('inspection_data')->where('flag','0')->count();
        $data['title']    = $this->title;
        return Pel::load('template', 'home/index', $data);
    }

    public function getDua(){
        // print_r(Request::session()->get('access'));
        print_r(Hash::make('admin'));
    }

    public function postGantiPassword(){
        $old_password  = $this->input('old_password', 'required');
        $new_password  = $this->input('new_password', 'required');
        $new_password2 = $this->input('new_password2', 'required');
        $user = DB::table('users')->where('username', Auth::user()->username)->first();
        if (Hash::check($old_password, $user->password)) {
            $res['api_status']  = 1;
            $res['api_message'] = 'sukses.';
            $update['password'] = Hash::make($new_password2);
            $where = array(['username','=', Auth::user()->username]);
            Pel::update('users', $update, $where);
        }else{
            $res['api_status']  = 0;
            $res['api_message'] = 'Password Lama yang anda masukan salah.';
        }
        return $this->api_output($res);
    }

}