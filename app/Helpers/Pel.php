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
	
	class Pel
	{

		#VIEW TEMPLATE
		public static function load($template = '', $view = '' , $view_data = array(), $view_add = array())
		{   
			$set  = $view_data;
			$data = array_merge($set, $view_add);
			$data['contents'] = view($view, $view_data);
			$data['menus']    = Request::session()->get('menus');

			return view($template, $data);
		}

		/**
		 * VIEW TEMPLATE
		 */

		#START PORTLET
		public static function portletStart($title, $icon = false, $color = 'success', $tools = array('min' => true, 'full'=> true, 'close'=> true), $addtools = null){
			$viewIcon 	= "";
			$viewMin 	= "";
			$viewFull 	= "";
			$viewClose 	= "";
			$color = $color ? 'm-portlet--'.$color. '  m-portlet--head-solid-bg' : '';
			if($icon){
				$viewIcon = "<span class=\"m-portlet__head-icon\">
								<i class=\"$icon\"></i>
							</span>";
			}
			if($tools['min']){
				$viewMin 	= "<li class=\"m-portlet__nav-item\">
									<a href=\"#\"  data-portlet-tool=\"toggle\" class=\"m-portlet__nav-link m-portlet__nav-link--icon\">
										<i class=\"la la-angle-down\"></i>
									</a>
								</li>";
			}
			if($tools['full']){
				$viewFull = "<li class=\"m-portlet__nav-item\">
								<a href=\"#\"  data-portlet-tool=\"fullscreen\" class=\"m-portlet__nav-link m-portlet__nav-link--icon\">
									<i class=\"la la-expand\"></i>
								</a>
							</li>";
			}
			if($tools['close']){
				$viewClose = "<li class=\"m-portlet__nav-item\">
									<a href=\"#\" data-portlet-tool=\"remove\" class=\"m-portlet__nav-link m-portlet__nav-link--icon\">
										<i class=\"la la-close\"></i>
									</a>
								</li>";
			}
			
			return "<div class=\"m-portlet $color m-portlet--head-sm\" data-portlet=\"true\">
			<div class=\"m-portlet__head\">
				<div class=\"m-portlet__head-caption\">
					<div class=\"m-portlet__head-title\">
							$viewIcon
						<h3 class=\"m-portlet__head-text\">
							$title
						</h3>
					</div>
				</div>
				<div class=\"m-portlet__head-tools\">
					<ul class=\"m-portlet__nav\">
						$viewMin
						$viewFull
						$viewClose
						$addtools
					</ul>
				</div>
			</div>
			<div class=\"m-portlet__body\">";
		}
		public static function portletEnd(){
			return "
				
				</div>
			</div>";
		}
		#FINISH PORTLET

		#HEADER
		public static function headerTitle($title, $subheader=''){
			// <li class=\"m-nav__item m-nav__item--home\">
			// 	<a href=\"#\" class=\"m-nav__link m-nav__link--icon\">
			// 		<i class=\"m-nav__link-icon la la-home\"></i>
			// 	</a>
			// </li>
			// <li class=\"m-nav__separator\">
			// 	-
			// </li>
			// <li class=\"m-nav__item\">
			// 	<a href=\"#\" class=\"m-nav__link\">
			// 		<span class=\"m-nav__link-text\">
			// 			Base
			// 		</span>
			// 	</a>
			// </li>
			// <li class=\"m-nav__separator\">
			// 	-
			// </li>
			// <li class=\"m-nav__item\">
			// 	<a href=\"#\" class=\"m-nav__link\">
			// 		<span class=\"m-nav__link-text\">
			// 			Accordions
			// 		</span>
			// 	</a>
			// </li>
			return "<div class=\"m-subheader\">
						<div class=\"d-flex align-items-center\">
							<div class=\"mr-auto\">
								<h3 class=\"m-subheader__title\">
									$title
								</h3>
								<ul id=\"sub_header_add\" class=\"m-subheader__breadcrumbs m-nav m-nav--inline\">
									
								</ul>
							</div>
							<div>
								$subheader
							</div>
						</div>
					</div>";
		}

		#TAB
		public static function space($jumlah){
			$return = '';
			for ($i=0; $i < $jumlah; $i++) { 
				$return = $return . '&nbsp;';
			}
			return $return;
		}
		
		/**
		 * FORM TEMPLATE
		 */

		#INPUT HIDDEN
		public static function formHidden($name, $value = ''){
			return "<input type=\"hidden\" name=\"$name\" id=\"$name\" value=\"$value\" />";
		}

		#INPUT
		public static function formInput($label, $type, $name, $value = '', $add=''){
			return "<div class=\"form-group m-form__group\">
                <label>
                    $label
                </label>
                <input name=\"$name\" id=\"$name\" type=\"$type\" value=\"$value\" class=\"form-control m-input m-input--pill\" placeholder=\"$label\" $add>
            </div>";
		}

		public static function defaultInput($label, $type, $name, $value = '', $add=''){
			return "<input name=\"$name\" id=\"$name\" type=\"$type\" value=\"$value\" class=\"form-control m-input m-input--pill\" style=\"width:100%\" placeholder=\"$label\" $add>";
		}
		
		#MAKE OPTION DATA
		public static function makeOption($data, $value, $name, $all = false){
			$res = [];
			if($all && count($data) > 1){
				$res[0]['value'] = '-all-';
				$res[0]['name'] = '--- Pilih Semua ---';
				$all = true;
			}else{
				$all = false;
			}
			foreach($data as $key => $d){
				if($all){
					$res[$key + 1]['value'] = $d->$value;
					$res[$key + 1]['name'] = $d->$name;
				}else{
					$res[$key]['value'] = $d->$value;
					$res[$key]['name'] = $d->$name;
				}
			}
			
			return $res;
		}
		public static function defaultFile($label, $name, $add='', $msg ='', $file = ''){
			if($file)
			$filetext =  "(<a href=\"".self::storageUrl($file)."\">File</a>)";
			else
			$filetext = '';
			$msg = $msg ? "<small class=\"form-text text-muted\">$msg $filetext</small>" : '';
			return "<div class=\"form-group m-form__group\">
				<input name=\"$name\" id=\"$name\" type=\"file\" class=\"form-control-file\" $add>
				$msg
            </div>";
		}
		
		public static function formFile($label, $name, $add='', $msg ='', $file = ''){
			if($file)
			$filetext =  "(<a href=\"".self::storageUrl($file)."\">File</a>)";
			else
			$filetext = '';
			$msg = $msg ? "<small class=\"form-text text-muted\">$msg $filetext</small>" : '';
			return "<div class=\"form-group m-form__group\">
                <label>
                    $label
                </label>
				<input name=\"$name\" id=\"$name\" type=\"file\" class=\"form-control-file\" $add>
				$msg
            </div>";
		}
		#SELECT
		public static function formSelect($label, $data = null, $name, $value = '', $add=''){
			$option = '';
			if($data){
				foreach($data as $d){
					$option .= "<option value=\"$d[value]\">$d[name]</option>";
				}
			}
			return "<div class=\"form-group m-form__group\">
                <label>
                    $label
                </label>
				<select class=\"form-control m-input m-input--pill\" name=\"$name\" id=\"$name\" $add>
					$option
				</select>
            </div>";
		}
		#SELECT 
		public static function formSelect2($label, $data = null, $name, $value = '', $multipe=false){
			$option = '';
			$varmultiple = $multipe ? " multiple=\"multiple\"" : '';
			$id = $multipe ? $name."[]" : $name;
			if($data){
				foreach($data as $d){
					$option .= "<option value=\"$d[value]\">$d[name]</option>";
				}
			}
			return "<div class=\"form-group m-form__group\">
                <label>
                    $label
                </label>
				<select class=\"form-control m-input m-input--pill\" id=\"$name\" name=\"$id\" $varmultiple>
					$option
				</select>
            </div>";
		}

		#SELECT
		public static function defaultSelect($label, $data = null, $name, $value = '', $add=''){
			$option = '';
			if($data){
				foreach($data as $d){
					$option .= "<option value=\"$d[value]\">$d[name]</option>";
				}
			}
			return "
				<select class=\"form-control m-input m-input--pill\" name=\"$name\" id=\"$name\" $add>
					$option
				</select>";
		}

		#SUBMIT
		public static function formSubmit($label, $name, $icon=null){
			$icon = $icon ? "<i class=\"$icon\"></i>" : "";
			return "<div class=\"form-group m-form__group\">
						<button id=\"$name\" class=\"btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill btn-sm pull-right\">
							<span>
								$icon
								<span>
									$label
								</span>
							</span>
						</button>
					</div>
					<br/><br/>";
		}
		/* =============== */
		


		#GET MENU
		public static function getMenu($id_privilege){
			$menu_data = DB::table('menus')
				->distinct()->select('urutan','menus.id_menu','nama_menu as nama','link','ikon')
				->join('permissions','permissions.id_menu','=','menus.id_menu')
				->where('id_privilege',$id_privilege)
				->orderBy('urutan')->get();
			foreach($menu_data as $key => $mn){
				$from = DB::table('submenus')
					->select('menus.id_menu','submenus.id_sub_menu','submenus.nama_sub_menu as nama_sub','submenus.link as link_sub','submenus.urutan')
					->join('menus','menus.id_menu','=','submenus.id_menu')
					->whereRaw(DB::raw("faspel_menus.id_menu=$mn->id_menu"))
					->orderBy('submenus.urutan');
				$sub_menu_data = DB::table(DB::raw("({$from->toSql()}) faspel_sub"))
					->select('sub.*')
					// ->mergeBindings($from->getQuery())
					->join('permissions', function ($join) {
						$join->on('permissions.id_menu', '=', 'sub.id_menu')
							->on('permissions.id_sub_menu','=','sub.id_sub_menu');
					})
					->where('permissions.id_privilege',$id_privilege)->get();
				$menu_data[$key]->sub_menu = $sub_menu_data;
			}
			return $menu_data;
		}

		#GET ACCESS
		public static function getAccess($id_privilege){
			$menu = DB::table('permissions')
				->select('menus.link as link_menu','submenus.link as link_sub_menu')
				->leftJoin('submenus', 'submenus.id_sub_menu', '=', 'permissions.id_sub_menu')
				->join('menus', 'menus.id_menu', '=', 'permissions.id_menu')
				->orderBy('menus.id_menu','asc')
				->where('id_privilege', $id_privilege)->get();
			$access_menu     = array_filter(array_column($menu->toArray(),'link_menu'));
			$access_sub_menu = array_filter(array_column($menu->toArray(),'link_sub_menu'));
			$access = array_merge($access_menu,$access_sub_menu);
			$forbidden  = DB::table('menus')
				->select('submenus.link as link_sub_menu','menus.link as link_menu')
				->leftJoin('submenus', 'submenus.id_menu', '=', 'menus.id_menu')
				->whereNotIn('menus.link', $access)
				->orWhere(function ($query) use($access){
					$query->whereNotIn('submenus.link', $access);
				})->get();
			$sec_menu     = array_filter(array_column($forbidden->toArray(),'link_menu'));
			$sec_sub_menu = array_filter(array_column($forbidden->toArray(),'link_sub_menu'));
			$sec = array_merge($sec_menu,$sec_sub_menu);
			return array('access_list' => $access, 'forbidden_list' => $sec);
		}

		/**
		 * HELPERS
		 */

		#PRINT DEBUG
		public static function print($data, $pre = TRUE){
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		}

		#CREATE NOMOR BA
		public static function createNomor($cabang, $date, $tipe){
			$tahun  = self::dateFormat($date,'Y');
			$bulan  = self::dateFormat($date,'m');
			$romawi = self::getRomawi($bulan);
			$getOld = DB::table('ba_nomor')->select('nomor')
				->where('kd_cabang', $cabang)->where('tahun', $tahun)->where('tipe', $tipe)->first();
			$kodeBa = DB::table('ba_tipe')->select('kode')->where('tipe_ba', $tipe)->first();
			#EXIST NOMOR
			if(@$getOld->nomor){
				$nomor = $getOld->nomor + 1;
				$nomorBA = $nomor .'/'. $kodeBa->kode .'/faspel/'. $romawi .'/'. $cabang .'-'. $tahun;
				$update['nomor'] = $nomor;
				DB::table('ba_nomor')->where('kd_cabang', $cabang)->where('tahun', $tahun)->where('tipe', $tipe)
					->update($update);
			}else{
				$nomor = 1;
				$nomorBA = $nomor .'/'. $kodeBa->kode .'/faspel/'. $romawi .'/'. $cabang .'-'. $tahun;
				$save['nomor']     = $nomor;
				$save['kd_cabang'] = $cabang;
				$save['tahun']     = $tahun;
				$save['tipe']      = $tipe;
				DB::table('ba_nomor')->insert($save);
			}
			return $nomorBA;
		}

		#MAKE QR CODE
		public static function createQr($data){
			$qr = new \chillerlan\QRCode\QRCode;
			$create = $qr->render($data);
			return $create;
		}

		

		public static function getRomawi($bulan){
			switch ($bulan){
				case 1: 
					return "I";
					break;
				case 2:
					return "II";
					break;
				case 3:
					return "III";
					break;
				case 4:
					return "IV";
					break;
				case 5:
					return "V";
					break;
				case 6:
					return "VI";
					break;
				case 7:
					return "VII";
					break;
				case 8:
					return "VIII";
					break;
				case 9:
					return "IX";
					break;
				case 10:
					return "X";
					break;
				case 11:
					return "XI";
					break;
				case 12:
					return "XII";
					break;
			}
		}

		#CEK COUNT
		

		#EMAIL
		public static function sendMail($to_email, $subject, $view, $data = null){
			$data = array('name'=>"Ogbonna Vitalis(sender_name)", "body" => "A test mail");
			Mail::send($view, $data, function($message) use ($to_email, $subject) {
			$message->to($to_email)
				->subject($subject)
				->from('arif.sideakun@gmail.com','Pelindo');
			});
		}
		#ROUTING
        public static function routeController($prefix, $controller, $token = false, $namespace = null)
		{
			
			$prefix = trim($prefix, '/') . '/';
			
			$namespace = ($namespace) ?: 'App\Http\Controllers';
			
			try {
				Route::get($prefix, ['uses' => $controller . '@getIndex', 'as' => $controller . 'GetIndex']);
				
				$controller_class = new \ReflectionClass($namespace . '\\' . $controller);
				$controller_methods = $controller_class->getMethods(\ReflectionMethod::IS_PUBLIC);
				$wildcards = '/{one?}/{two?}/{three?}/{four?}/{five?}';
				foreach ($controller_methods as $method) {
					if ($method->class != 'Illuminate\Routing\Controller' && $method->name != 'getIndex') {
						if (substr($method->name, 0, 3) == 'get') {
							$method_name = substr($method->name, 3);
							$slug = array_filter(preg_split('/(?=[A-Z])/', $method_name));
							$slug = strtolower(implode('-', $slug));
							$slug = ($slug == 'index') ? '' : $slug;
							if($token){
								Route::get($prefix . $slug . $wildcards, ['uses' => $controller . '@' . $method->name, 'as' => $controller . 'Get' . $method_name]);
							}else{
								Route::get($prefix . $slug . $wildcards, ['uses' => $controller . '@' . $method->name, 'as' => $controller . 'Get' . $method_name]);
							}
						} elseif (substr($method->name, 0, 4) == 'post') {
							$method_name = substr($method->name, 4);
							$slug = array_filter(preg_split('/(?=[A-Z])/', $method_name));
							if($token){
								Route::post($prefix . strtolower(implode('-', $slug)) . $wildcards, [
									'uses' => $controller . '@' . $method->name,
									'as' => $controller . 'Post' . $method_name,
								]);
							}else{
								Route::post($prefix . strtolower(implode('-', $slug)) . $wildcards, [
									'uses' => $controller . '@' . $method->name,
									'as' => $controller . 'Post' . $method_name,
								]);
							}
						}
					}
				}
			} catch (\Exception $e) {
			
			}
		}

		#TO BASE64
		public static function toBase64($path){
			$type = pathinfo($path, PATHINFO_EXTENSION);
			if($type){
				$aa = file_get_contents($path);
				$base64 = 'data:image/' . $type . ';base64,' . base64_encode($aa);
				return $base64;
			}else{
				return null;
			}
		}



		#BASE URL
		public static function baseUrl($path=''){
			return url($path);
		}

		#PATH URL
		public static function storageUrl($path=''){
			return url('/storage/app/' . $path);
			// return 'https://app.pel.co.id/faspel/storage/app/'.$path;
		}
		
		#TEMPLATE URL
		public static function templateUrl($path=''){
			return  url('/assets/template/' . $path);
		}

		#CUSTOM URL
		public static function customUrl($path=''){
			return url('/public/assets/custom/' . $path);
		}

		#VALIDASI
		public static function Validator($data = [])
		{
			
			$validator = Validator::make(Request::all(), $data);
			if ($validator->fails()) {
				$result = array();
				$message = $validator->errors();
				$result['api_status'] = 0;
				$result['api_code'] = 401;
				$result['api_message'] = $message;
				// $result['api_message'] = $message->all(':message')[0];
				$res = response()->json($result);
				return $res->send();
			}
		}
		

		#REQUEST & VALIDASI
		public static function Input($name = null, $rule = []){
			$rule =  array($name => $rule);
			$validator = Validator::make(Request::all(), $rule);
			if ($validator->fails()) {
				$result = array();
				$message = $validator->errors();
				$result['api_status'] = 0;
				$result['api_code'] = 401;
				$result['api_message'] = $message;
				// $result['api_message'] = $message->all(':message')[0];
				$res = response()->json($result);
				$res->send();
				exit;
			}
		}

		public static function insert($table, $save){
			$save['created_at']	= new \DateTime();
			$result = DB::table($table)->insert($save);
			if($result){
				self::createLog('CREATE_TB '.$table);
				return $result;
			}
			return false;
		}
		public static function insertID($table, $save){
			$save['created_at']	= new \DateTime();
			$result = DB::table($table)->insertGetId($save);
			if($result){
				self::createLog('CREATE_TB '.$table);
				return $result;
			}
			return false;
		}
		public static function delete($note, $data){
			$temp = json_encode($data->first());
			$result = $data->delete();
			if($result){
				self::createLog('DELETE_TB '.$note, $temp);
				return true;
			}
			return $result;
		}
		public static function update($table, $save, $parameter){
			$temp = DB::table($table)->where($parameter)->first();
			$temp = json_encode($temp);
			$save['updated_at']	= new \DateTime();
			$result = DB::table($table)->where($parameter)->update($save);
			if($result){
				self::createLog('UPDATE_TB '.$table, $temp);
				return true;
			}
			return false;
		}
		public static function createLog($errors, $note = null,  $type = 'info'){
			$ip      = Request::ip();
			$input   = json_encode(Request::input());
			$url     = Request::url();
			$message = is_array($errors) ? json_encode($errors) : $errors;
			$user    = Auth::user() ? Auth::user()->username : '';
			$method  = Request::getMethod();
			$text    = "[IP: ". $ip. "] [USER: ".$user."] [URL: ".$url."] [METHOD: ".$method."] [PARAMETER: ".$input."] [MESSAGE: ".$message."] [KETERANGAN: ".$note."]";

			switch ($type) {
				case 'info':
					Log::info($text);
					break;

				case 'emergency':
					Log::emergency($text);
					break;
					
				case 'alert':
					Log::alert($text);
					break;

				case 'critical':
					Log::critical($text);
					break;

				case 'error':
					Log::error($text);
				break;

				case 'warning':
					Log::warning($text);
					break;

				case 'notice':
					Log::notice($text);
					break;
					
				case 'debug':
					Log::debug($text);
					break;
				default:
					# code...
					break;
			}
		}

		/**
		 * DATE 
		 * example: 2020-03-17 12:00:00
		 */

		
		#Selasa, 17 Maret 2019
		public static function getFullDate($date){
			date_default_timezone_set('Asia/Jakarta');
            $tanggal = self::getTanggal($date);
            $bulan   = self::bulan(self::getBulan($date));
            $tahun   = self::getTahun($date);
            return self::hari($tanggal) .', '.$tanggal.' '.$bulan.' '.$tahun;  
		}

		public static function dateFormat($date, $format = 'Y-m-d H:i:s'){
			return date($format, strtotime($date));
		}


		public static function getTanggal($date){
			return substr($date,8,2);
		}
		public static function getBulan($date){
			return substr($date,5,2);
		}
		public static function getTahun($date){
			return substr($date,0,4);
		}

		public static function getHour($date){
			return substr($date, 11,5);
		}

		public static function hari($date){
			$hari = date('D', strtotime($date));
			switch ($hari) {
				case 'Sun':
					return 'Minggu';
					break;
				case 'Mon':
					return 'Senin';
					break;
				case 'Tue':
					return 'Selasa';
					break;
				case 'Wed':
					return 'Rabu';
					break;
				case 'Thu':
					return 'Kamis';
					break;
				case 'Fri':
					return 'Jumat';
					break;
				case 'Sat':
					return 'Sabtu';
					break;
			}
		}
		public static function bulan($bln){
			switch ($bln){
				case 1: 
					return "Januari";
					break;
				case 2:
					return "Februari";
					break;
				case 3:
					return "Maret";
					break;
				case 4:
					return "April";
					break;
				case 5:
					return "Mei";
					break;
				case 6:
					return "Juni";
					break;
				case 7:
					return "Juli";
					break;
				case 8:
					return "Agustus";
					break;
				case 9:
					return "September";
					break;
				case 10:
					return "Oktober";
					break;
				case 11:
					return "November";
					break;
				case 12:
					return "Desember";
					break;
			}
		} 

		#ISI BA
		public static function pembukaBa($tanggal){
			return "Pada hari ini ".ucwords(self::hari($tanggal))." tanggal ".ucwords(self::terbilang(self::dateFormat($tanggal,'d')))." bulan ".self::bulan(self::dateFormat($tanggal,'m'))." ".ucwords(self::terbilang(self::dateFormat($tanggal,'Y')))." ( ".$tanggal." ), ";

		}

		public static function isiBa($value){
			return " Telah mengadakan pemeriksaan / inspeksi di ". $value->nama_cluster." ( ". $value->nama_sub_cluster." ). <br/>";
		}

		/**
		 * ANGKA
		 */
		public static function penyebut($nilai) {
			$nilai = abs($nilai);
			$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
			$temp = "";
			if ($nilai < 12) {
				$temp = " ". $huruf[$nilai];
			} else if ($nilai <20) {
				$temp = self::penyebut($nilai - 10). " belas";
			} else if ($nilai < 100) {
				$temp = self::penyebut($nilai/10)." puluh". self::penyebut($nilai % 10);
			} else if ($nilai < 200) {
				$temp = " seratus" . self::penyebut($nilai - 100);
			} else if ($nilai < 1000) {
				$temp = self::penyebut($nilai/100) . " ratus" . self::penyebut($nilai % 100);
			} else if ($nilai < 2000) {
				$temp = " seribu" . self::penyebut($nilai - 1000);
			} else if ($nilai < 1000000) {
				$temp = self::penyebut($nilai/1000) . " ribu" . self::penyebut($nilai % 1000);
			} else if ($nilai < 1000000000) {
				$temp = self::penyebut($nilai/1000000) . " juta" . self::penyebut($nilai % 1000000);
			} else if ($nilai < 1000000000000) {
				$temp = self::penyebut($nilai/1000000000) . " milyar" . self::penyebut(fmod($nilai,1000000000));
			} else if ($nilai < 1000000000000000) {
				$temp = self::penyebut($nilai/1000000000000) . " trilyun" . self::penyebut(fmod($nilai,1000000000000));
			}     
			return $temp;
		}
	 
		public static function terbilang($nilai) {
			if($nilai<0) {
				$hasil = "minus ". trim(self::penyebut($nilai));
			} else {
				$hasil = trim(self::penyebut($nilai));
			}     		
			return $hasil;
		}
		/**
		 * SESSION
		 */

		public static function getSession($nama){
			return Request::session()->get($nama);
		}
		
    }