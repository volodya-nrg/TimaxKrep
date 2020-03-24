<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Attributes;

class AdminProductsController extends Controller
{
	private $max_height_img = 600;
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $output = [];
		
		$Categories = new Categories;
		
		$output['aProducts'] = Products::orderBy('id', 'desc')->get();
		
		// подхватим категории, чтоб понимать какие продукты не присудствуют ни в одной категории
		$output['colCategoryIds'] = $Categories->select('id')->get();
		
		return view('admin.products', $output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $output = [];
		$Categories = new Categories;
		
		$output['aCategories'] = $Categories->get_as_tree();
		$output['aAttributes'] = Attributes::all();
		
		return view('admin.products_create_edit', $output);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$errors = [];
			
		$this->validate($request, array(
			'name' => 'required|min:2|max:255',
			'title' => 'required|min:2|max:255',
			'category_id' => 'required|integer|min:0',
		));
		
		$slug = str_slug($request->name, '-');
		if($slug == ""){
			return redirect()->back()->withInput()->withErrors('не корректное название');
		}
		
		$future_id = 0;
		foreach(\DB::select('SHOW TABLE STATUS') as $obj){
			if($obj->Name == 'products' && !empty($obj->Auto_increment) && is_numeric($obj->Auto_increment)){
				$future_id = $obj->Auto_increment;
				break;
			}	
		}
		
		$Products = new Products;
		
		if(!$future_id){
			$future_id = $Products->all()->count()+1;
		}
		
		$slug .= "_".$future_id;
		
		if($Products->where('slug', $slug)->count()){
			$errors[] = 'такой продукт в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
		
			$Products->slug = $slug;
			$Products->sku = $request->sku;
			$Products->title = $request->title;
			$Products->meta_keywords = $request->meta_keywords;
			$Products->meta_desc = $request->meta_desc;
			$Products->name = $request->name;
			$Products->category_id = $request->category_id;
			$Products->desc_short = $request->desc_short;
			$Products->desc_full = $request->desc_full;
			$Products->price = (float)$request->price; // цена может быть с копейками
			$Products->discount = $request->discount;
			$Products->is_hide = !empty($request->is_hide)? 1: 0;
			$Products->is_absent = !empty($request->is_absent)? 1: 0;
			$Products->position = (int)$request->position;
			$Products->similar = $request->similar;
		$Products->save();
		
		// подхватим подгруженные изображения с формы
		if($request->hasFile('images')){
			$aImages = [];
			$total_images = 0;
			
			foreach($request->file('images') as $file){
				$new_name = time()."_".str_random(5).".".$file->getClientOriginalExtension();
				$is_move = $file->move(public_path().'/'.DIR_IMAGES, $new_name);
				
				if($is_move){
					$file_path = public_path().'/'.DIR_IMAGES.'/'.$new_name;
					$img = \Image::make($file_path);
					unlink($file_path);
					$img->resize(NULL, $this->max_height_img, function($constraint){
    					$constraint->aspectRatio();
						$constraint->upsize();
					});
					$img->insert(public_path().'/img/watermark.png', 'bottom-right', 10, 10);
					$img->save($file_path);
					
					$aImages[] = array(	 'product_id' => $Products->id, 
										'path' => '/'.DIR_IMAGES.'/'.$new_name, 
										'position' => ++$total_images);
				}
			}
			
			if(sizeof($aImages)){
				\DB::table('product_images')->insert($aImages);
			}
		}
		
		// получим аттрибуты, attribute_id[]
		if(!empty($request->attribute_id) && is_array($request->attribute_id)){
			$tmp = [];
			foreach($request->attribute_id as $key => $attr_id){
				if(!in_array($attr_id, $tmp) && isset($request->attribute_value[$key])){
					$Products->set_attr($Products->id, $attr_id, $request->attribute_value[$key]);
					$tmp[] = $attr_id;
				}
			}
		}
		
		return redirect('/admin/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Products = new Products;
		$Categories = new Categories;
		
		$output = [];
		$output['aCategories'] = $Categories->get_as_tree();
		$output['aAttributes'] = Attributes::all();
		$output['data'] = $Products->get_data($id, 1);
		
		return view('admin.products_create_edit', $output);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$errors = [];
		$this->validate($request, array(
			'name' => 'required|min:2|max:255',
			'title' => 'required|min:2|max:255',
			'category_id' => 'required|integer|min:0',
		));
		
		$slug = str_slug($request->name, '-');
		
		if($slug == ""){
			$errors[] = 'не корректное название';
			return redirect()->back()->withInput()->withErrors($errors);
		}
		else{
			$slug .= "_".$id;	
		}
		
		$Products = new Products;
		
		if($Products->where('slug', $slug)->where('id', '<>', $id)->count()){
			$errors[] = 'такая страница в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
		$oTmp = $Products->find($id);
			$oTmp->slug = $slug;
			$oTmp->sku = $request->sku;
			$oTmp->title = $request->title;
			$oTmp->meta_keywords = $request->meta_keywords;
			$oTmp->meta_desc = $request->meta_desc;
			$oTmp->name = $request->name;
			$oTmp->category_id = $request->category_id;
			$oTmp->desc_short = $request->desc_short;
			$oTmp->desc_full = $request->desc_full;
			$oTmp->price = !empty($request->price)? (float)$request->price: 0; // цена может быть с копейками
			$oTmp->discount = !empty($request->discount)? abs(intval($request->discount)): 0;
			$oTmp->is_hide = !empty($request->is_hide)? 1: 0;
			$oTmp->is_absent = !empty($request->is_absent)? 1: 0;
			$oTmp->position = (int)$request->position;
			$oTmp->similar = $request->similar;
		$oTmp->save();
		unset($oTmp);
		
		$Products->delete_all_attrs($id);
		
		// получим новые данные продукта, в том числе картинки и атрибуты
		$aData = $Products->get_data($id, 1);
		$total_images = 0;

		// если есть старые изображения, то пробежимся по ним
		if($aData->images->count()){	
			foreach($aData->images as $val1){
				$key_file_exists = FALSE; // ключ определяющий удалять ли файл или нет
				
				// посмотрим изображения из того что осталось
				if(!empty($request->a_img_path) && is_array($request->a_img_path)){
					foreach($request->a_img_path as $key2 => $val2){
						// если это изображение есть, то обновим у него данные
						if($val1->path == $val2){
							$key_file_exists = TRUE;
							$path = $val2;
							$pos = !empty($request->a_img_position[$key2])? abs(intval($request->a_img_position[$key2])): 0;
							$is_hide = !empty($request->a_img_is_hide[$key2])? 1: 0;
							
							\DB::table('product_images')
								->where('path', $path)
								->update(['position'=>$pos, 'is_hide'=>$is_hide]);
							
							$total_images++;
							break;
						}	
					}
				}		
				
				// удалим не пришедшие из старых файлов
				if(!$key_file_exists){
					$Products->delete_some_images($val1->path);	
				}
			}
		}
		
		// подхватим подгруженные изображения с формы
		if($request->hasFile('images')){
			$aImages = [];
			
			foreach($request->file('images') as $file){
				$new_name = time()."_".str_random(5).".".$file->getClientOriginalExtension();
				$is_move = $file->move(public_path().'/'.DIR_IMAGES, $new_name);
				
				if($is_move){
					$file_path = public_path().'/'.DIR_IMAGES.'/'.$new_name;
					$img = \Image::make($file_path);
					unlink($file_path);
					$img->resize(NULL, $this->max_height_img, function($constraint){
    					$constraint->aspectRatio();
						$constraint->upsize();
					});
					$img->insert(public_path().'/img/watermark.png', 'bottom-right', 10, 10);
					$img->save($file_path);
					
					$aImages[] = array(	 'product_id' => $id, 
										'path' => '/'.DIR_IMAGES.'/'.$new_name, 
										'position' => ++$total_images);
				}
			}
			
			if(sizeof($aImages)){
				\DB::table('product_images')->insert($aImages);
			}
		}
		
		// получим аттрибуты, attribute_id[]
		if(!empty($request->attribute_id) && is_array($request->attribute_id)){
			$tmp = [];
			foreach($request->attribute_id as $key => $attr_id){
				if(!in_array($attr_id, $tmp) && isset($request->attribute_value[$key])){
					$Products->set_attr($id, $attr_id, $request->attribute_value[$key]);
					$tmp[] = $attr_id;
				}
			}
		}
		
		return redirect('/admin/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Products = new Products;
		
		$data = $Products->get_data($id, 1);
		
		foreach($data->images as $val){
			$Products->delete_some_images($val->path);		
		}
		
		delete_images_from_description($data->desc_full);
		
		$oTmp = $Products->find($id);
		$oTmp->delete();
		
		$Products->delete_all_attrs($id);
		
		return redirect('/admin/products');
    }
}
