<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Categories;
use App\Models\Products;

class AdminController extends Controller
{	
	public function index(){
		$output = [];
		
		return view('admin.index', $output);
	}
	public function update_basic_info(Request $request){
		$output = [];
		
		//1. возьмем старое лого, если есть
		$logo = !empty($request->logo_old)? $request->logo_old: '';
		//2. если есть новое, подхватим
		if($request->hasFile('logo_new')){
			$file = $request->file('logo_new');
			$new_name = time()."_".str_random(5).".".$file->getClientOriginalExtension();
			$is_move = $file->move(public_path().'/'.DIR_IMAGES, $new_name);
			
			if($is_move){
				$logo = '/'.DIR_IMAGES.''.$new_name;
				
				$file_path = public_path().'/'.DIR_IMAGES.'/'.$new_name;
				$img = \Image::make($file_path);
				unlink($file_path);
				$img->resize(NULL, 100, function($constraint){
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$img->save($file_path);
			}
		}
		//3. посмотрим что есть в базе, если что-то другое пришло, то удалим старое
		$tmp = \DB::table('basic')->select('value AS logo')->where('name', 'logo')->first();
		if(!empty($tmp->logo) && $tmp->logo != $logo && is_file(public_path().$tmp->logo)){
			unlink(public_path().$tmp->logo);
		}
		
		$aData[] = ['name'=>'mode', 				'value'=>(int)$request->mode];
		$aData[] = ['name'=>'name_sait', 			'value'=>$request->name_sait];
		$aData[] = ['name'=>'logo', 				'value'=>$logo];
		$aData[] = ['name'=>'counters', 			'value'=>$request->counters];
		$aData[] = ['name'=>'email', 				'value'=>filter_var($request->email, FILTER_VALIDATE_EMAIL)? $request->email:''];
		$aData[] = ['name'=>'smtp_host', 			'value'=>$request->smtp_host];
		$aData[] = ['name'=>'smtp_port', 			'value'=> (int)$request->smtp_port];
		$aData[] = ['name'=>'smtp_login', 			'value'=>$request->smtp_login];
		$aData[] = ['name'=>'smtp_pass', 			'value'=>$request->smtp_pass];
		$aData[] = ['name'=>'dop_info_for_footer', 'value'=>trim($request->dop_info_for_footer)];
		$aData[] = ['name'=>'cart_min_treshhold',  'value'=>abs(intval($request->cart_min_treshhold))];
		$aData[] = ['name'=>'use_as_shop', 			'value'=>!empty($request->use_as_shop)? 1: 0];
		$aData[] = ['name'=>'set_photo_cat_to_photo_product', 'value'=>!empty($request->set_photo_cat_to_photo_product)? 1: 0];
		
		if(!empty($request->soc_link) && !empty($request->soc_html) && 
			is_array($request->soc_link) && is_array($request->soc_html)){	
			$aData[] = ['name'=>'soc_link', 		'value'=>serialize($request->soc_link)];
			$aData[] = ['name'=>'soc_html', 		'value'=>serialize($request->soc_html)];
		}
		if(!empty($request->phones) && is_array($request->phones)){	
			$aData[] = ['name'=>'phones', 			'value'=>serialize($request->phones)];
		}
		
		\DB::table('basic')->delete();
		\DB::table('basic')->insert($aData);
		
		$request->session()->forget('aSettings'); // удалим осн. настройки (данные) о сайте, после они создадутся сами
		$request->session()->flash('is_saved', 1);// оповестим о сохранении
		
		return redirect()->back();
	}
	public function update_price_for_category(Request $request){
		$result = false;
		$msg = "";
		
		$Categories = new Categories;
		$Products = new Products;
		
		$percent = (!empty($request->percent) && is_numeric($request->percent))? (int)$request->percent: 0;
		$category_id = (!empty($request->category_id) && is_numeric($request->category_id))? (int)$request->category_id: 0;
		$method = !empty($request->method)? 1: 0;
		
		if(empty($percent)){
			$msg = "не указан процент";
		}
		else{
			$aCategoryIds = $Categories->get_child_ids($category_id, true);
			
			// если есть где изменять 
			if(sizeof($aCategoryIds)){
				$Products->update_price_via_percent($percent, $aCategoryIds, $method);
				$result = true;
			}
			else{
				$msg = "не где изменять цену";
			}
		}
		
		return response()->json(['result'=>$result, 'msg'=>$msg]);
	}
	public function etc(){
		$output = [];
		
		$Categories = new Categories;
		
		$output['aCategories'] = $Categories->get_as_tree(1);
		
		return view('admin.etc', $output);		
	}
	public function login(){
		if(\Session::has('admin')){
			return redirect()->back();
		}
		else{
			return view('layouts.login');	
		}
	}
	public function logout(){
		\Session::forget('admin');
			
		return redirect('/');
	}
	public function check_login(Request $request){
		$output = [];
		$errors = [];
		
		if(empty($request->login)){
			$errors[] = "укажите логин";
		}
		if(empty($request->pass)){
			$errors[] = "впишите пароль";
		}
		if(sizeof($errors)){
			return redirect()->back()->withInput()->withErrors($errors);
		}
		
		if($request->login == "timax-krep@test.ru" && $request->pass == "test"){
			$request->session()->put('admin', 1);
			
			return redirect('admin');
		}
		else{
			$errors[] = "не верная пара логин/пароль";
			return redirect()->back()->withInput()->withErrors($errors);		
		}
	}
}
