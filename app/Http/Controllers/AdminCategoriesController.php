<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Categories;

class AdminCategoriesController extends Controller
{
    private $pattern_for_name = '/^[a-zа-я0-9][-a-zа-я_0-9,.() ]+$/ui';
	private $pattern_for_parents = '/^([0-9]+)|([0-9][_0-9]+)$/';
	private $max_height_img = 400;
	
	public function index(){
		$output = [];
		$Categories = new Categories;
		
		$output['aCategories'] = $Categories->get_as_tree(1);
		
		return view('admin.categories', $output);
	}
	public function create(){
		$output = [];
		$Categories = new Categories;
		
		$output['aCategories'] = $Categories->get_as_tree(1);
		
		return view('admin.categories_create_edit', $output);
	}
	public function store(Request $request){
		$errors = [];
		$this->validate($request, array(
			'name' => 'required|min:2|max:255|regex:'.$this->pattern_for_name,
			'img_new' => 'image',
			'parents' => array('sometimes', 'regex:'.$this->pattern_for_parents),
		));
		
		$slug = str_slug($request->name, '-');
		if($slug == ""){
			$errors[] = 'впишите название';
			return redirect()->back()->withInput()->withErrors($errors);
		}
		
		$Categories = new Categories;
		
		if($Categories->where('slug', $slug)->where('parents', $request->parents)->count()){
			$errors[] = 'такая категория в базе уже присудствует';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
			$Categories->slug = $slug;
			$Categories->name = $request->name;
			$Categories->parents = $request->parents;
			$Categories->position = (int)$request->position;
			$Categories->is_hide = !empty($request->is_hide)? 1: 0;
			
			if(!empty($Categories->parents)){
				if(substr_count($Categories->parents, '_')){
					$tmp = explode('_', $Categories->parents);
					$Categories->parent = end($tmp);
				}
				else{
					$Categories->parent = $Categories->parents;
				}
			}
			else{
				$Categories->parent = 0;	
			}
			
			if($request->hasFile('img_new')){
				$file = $request->file('img_new');
				$new_name = time()."_".str_random(5).".".$file->getClientOriginalExtension();
				$target = $file->move(public_path().'/'.DIR_IMAGES, $new_name);
				
				if($target){
					$file_path = public_path().'/'.DIR_IMAGES.'/'.$new_name;
					$file_watermark = public_path().'/img/watermark.jpg';
					
					$img = \Image::make($file_path);
					unlink($file_path);
					$img->resize(NULL, $this->max_height_img, function ($constraint) {
    					$constraint->aspectRatio();
						$constraint->upsize();
					});
					//$img->insert($file_watermark, 'bottom-right', 10, 10);
					$img->save($file_path);
					
					$Categories->img_path = '/'.DIR_IMAGES.'/'.$new_name;	
				}
			}
			
			$Categories->show_as_product = !empty($request->show_as_product)? 1: 0;
			$Categories->show_in_top_menu = !empty($request->show_in_top_menu)? 1: 0;
			$Categories->description = $request->description;
			$Categories->title = $request->title;
			$Categories->meta_keywords = $request->meta_keywords;
			$Categories->meta_desc = $request->meta_desc;
		$Categories->save();
		
		return redirect('/admin/categories');
	}
	public function show($id){
		abort(404);
	}
	public function edit($id){
		$output = [];
		$Categories = new Categories;
		
		$output['aCategories'] = $Categories->get_as_tree(1);
		$output['data'] = Categories::find($id);
		
		return view('admin.categories_create_edit', $output);
	}
	public function update(Request $request, $id){
		$errors = [];
		
		$this->validate($request, array(
			'name' => 'required|min:2|max:255|regex:'.$this->pattern_for_name,
			'img_new' => 'image',
			'parents' => array('sometimes', 'regex:'.$this->pattern_for_parents),
		));
		
		$slug = str_slug($request->name, '-');
		if($slug == ""){
			$errors[] = 'впишите название';
			return redirect()->back()->withInput()->withErrors($errors);
		}
		
		$Categories = new Categories;
		
		if($Categories->where('slug', $slug)->where('parents', $request->parents)->where('id', '<>', $id)->count()){
			$errors[] = 'такая категория в базе уже присудствует';
			return redirect()->back()->withInput()->withErrors($errors);
		}
		
		$oTmp = $Categories->find($id);
		
		// если удалили предыдущую картинку
		$img = !empty($request->img_old)? $request->img_old: '';
		if($img == '' && !empty($oTmp->img_path) && is_file(public_path().$oTmp->img_path)){
			unlink(public_path().$oTmp->img_path);	
		}
			
		$oTmp->slug = $slug;
		$oTmp->name = $request->name;
		$oTmp->parents = $request->parents;
		$oTmp->position = (int)$request->position;
		$oTmp->is_hide = !empty($request->is_hide)? 1: 0;
		$oTmp->img_path = $img;
			
		if(!empty($oTmp->parents)){
			if(substr_count($oTmp->parents, '_')){
				$tmp = explode('_', $oTmp->parents);
				$oTmp->parent = end($tmp);
			}
			else{
				$oTmp->parent = $oTmp->parents;
			}
		}
		else{
			$oTmp->parent = 0;	
		}
		
		if($request->hasFile('img_new')){
			$file = $request->file('img_new');
			$new_name = time()."_".str_random(5).".".$file->getClientOriginalExtension();
			$target = $file->move(public_path().'/'.DIR_IMAGES, $new_name);
			
			if($target){
				$file_path = public_path().'/'.DIR_IMAGES.'/'.$new_name;
				$img = \Image::make($file_path);
				unlink($file_path);
				$img->resize(NULL, $this->max_height_img, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				//$img->insert(public_path().'/img/watermark.jpg', 'bottom-right', 10, 10);
				$img->save($file_path);
				
				// удалим старый файл
				if($oTmp->img_path != ''){
					unlink(public_path().$oTmp->img_path);
				}
				
				$oTmp->img_path = '/'.DIR_IMAGES.'/'.$new_name;
			}
		}
			
		$oTmp->show_as_product = !empty($request->show_as_product)? 1: 0;
		$oTmp->show_in_top_menu = !empty($request->show_in_top_menu)? 1: 0;
		$oTmp->description = $request->description;
		$oTmp->title = $request->title;
		$oTmp->meta_keywords = $request->meta_keywords;
		$oTmp->meta_desc = $request->meta_desc;
	
		$oTmp->save();
		
		return redirect('/admin/categories');
	}
	public function destroy($id){
		$id = (int)$id;
		
		// возьмем данные об удаляемой категории, так же нужно подхватит данные потомков, чтоб их тоже удалить.
		$aIds = [];
		$aIds[] = $id;
		
		$Categories = new Categories;
		$colChids = $Categories->get_childs($id, 1);
		
		foreach($colChids as $val){
			$aIds[] = $val->id;
		}
		
		foreach($aIds as $val){
			$oTmp = Categories::find($val);
			$img = $oTmp->img_path;
			delete_images_from_description($oTmp->description);
			$oTmp->delete();
			
			if(!empty($img) && is_file(public_path().$img)){
				unlink(public_path().$img);
			}
		}
		
		return redirect('/admin/categories');
	}
}
