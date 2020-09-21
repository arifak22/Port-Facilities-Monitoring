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

class TestController extends MiddleController
{

    public function getIndex(){
         #SUKSES
         $res['api_status']  = 1;
         $res['api_message'] = "success";
         $res['no_sampah']   = JWTAuth::getPayload();
         return $this->api_output($res);
    }
}