<?php

use Illuminate\Http\Request;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Pages;
use App\Models\Cart;


Route::get('/', function(){
	$output = [];
	
	$Categories = new Categories;
	$Products = new Products;
	$Pages = new Pages;
	$Cart = new Cart;
	
	$aSumm = $Cart->get_total_sum();
	$total_sum = !empty($aSumm['total_sum_with_discount'])? $aSumm['total_sum_with_discount']: $aSumm['total_sum'];
	$page = $Pages->where('slug', 'index')->first();
	
	/* стандартные данные */
	$output['title'] = $page->title;
	$output['meta_keywords'] = $page->meta_keywords;
	$output['meta_desc'] = $page->meta_desc;
	$output['description'] = $page->description;
	$output['total_sum'] = html_price($total_sum);
	$output['total_products'] = $Cart->get_total_products();
	$output['aTopMenuPages'] = $Pages->get_top_menu();
	$output['aFooterLinks'] = $Pages->get_links_for_footer();
	$output['aCategoryTopMenu'] = $Categories->get_top_menu();
	$output['aCategoryOther'] = $Categories->get_top_menu(1);
	
	/* данные для персонального шаблона */
	$output['aProductBestsellers'] = $Products->get_bestsellers();
	$output['aProductLasted'] = $Products->get_lasted();
	
    return view('index', $output);
});
Route::get('index', function(){ return redirect('/'); });

Route::get('cart', 'CartController@index');
Route::get('search', 'SearchController@index');
Route::get('sitemap_xml', function(){
	$br = "\n";
	$Products = new Products;
	$domain = Config::get('app.url');
	
	$output = '<?xml version="1.0" encoding="UTF-8"?>'.$br.
				'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$br;
	
	$rows = $Products->select('slug')->get();
	foreach($rows as $val){
		$output .= "\t<url><loc>".$domain."/".$val->slug."</loc></url>".$br;	
	}
	
	$output .= '</urlset>';
	
	return Response::make($output, '200')->header('Content-Type', 'text/xml');
});
Route::get('sitemap', function(){
	abort(404);
});
Route::get('blogs/{slug?}', 'BlogsController@index')->where('slug', '[a-z][-a-z0-9_.]+');
Route::get('news/{slug?}', 'NewsController@index')->where('slug', '[a-z][-a-z0-9_.]+');
Route::get('catalog/{url?}', 'CatalogController@index')->where('url', '[a-z0-9][-a-z0-9/]*');
Route::get('imager/{src}/{w}/{h}', function($src, $w, $h){
	$file = public_path()."/".$src;
	$w = empty($w)? NULL: $w;
	$h = empty($h)? NULL: $h;
	
	if(!is_file($file) || (!$w && !$h)){
		return NULL;
	}
	
	$img = \Image::make($file);
	$fn = function($constraint){
		$constraint->aspectRatio();
		$constraint->upsize();
	};
	
	return $img->resize($w, $h, $fn)->response();
})->where(['src'=>'[-a-zA-Z0-9_/]+\.(jpg|jpeg|png|gif)', 'w'=>'[0-9]{1,4}', 'h'=>'[0-9]{1,4}']);

Route::get('login', 'AdminController@login');
Route::post('login', 'AdminController@check_login');

Route::get('order', 'OrderController@index');
Route::post('order', 'OrderController@check');

Route::group(['middleware' => 'admin_auth'], function(){
	Route::get('logout', 'AdminController@logout');
	Route::get('admin', 'AdminController@index');
	Route::get('admin/etc', 'AdminController@etc');
	
	Route::post('admin', 'AdminController@update_basic_info');
	Route::post('admin', 'AdminController@update_basic_info');
	Route::post('upload_image', function(Request $request){
		$msg = "";
		$output_file_path = "";
		$orig_name = $request->file('upload')->getClientOriginalName();
		$callback = $request->get('CKEditorFuncNum');
		$ext = $request->file('upload')->getClientOriginalExtension();
		$hash = sha1_file($request->file('upload')->getRealPath()).".".$ext;
		$dir_images = public_path()."/".DIR_IMAGES;
		
		if($request->file('upload')->isValid()){
			if(!$request->file('upload')->getSize()){
				$msg = 'файл '.(!empty($orig_name)? '-'.$orig_name.'-': "").' пустой';
			}
			elseif(!in_array($request->file('upload')->getMimeType(), ["image/gif", "image/jpeg", "image/png"])){
				$msg = 'файл '.(!empty($orig_name)? '-'.$orig_name.'-': "").' не соответствует формату';
			}
			elseif(!$request->file('upload')->move($dir_images, $hash)){
				$msg = 'файл '.(!empty($orig_name)? '-'.$orig_name.'-': "").' не переместился';
			}
			else{
				$file_path = $dir_images."/".$hash;
				$img = \Image::make($file_path);
				unlink($file_path);
				$img->resize(NULL, 600, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$img->insert(public_path().'/img/watermark.png', 'bottom-right', 10, 10);
				$img->save($file_path);
				$output_file_path = '/'.DIR_IMAGES.'/'.$hash;
			}
		}
		
		exit('<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("'.$callback.'","'.$output_file_path.'","'.$msg.'" );</script>');
	});
	
	Route::resource('admin/categories', 'AdminCategoriesController');
	Route::resource('admin/pages', 'AdminPagesController');
	Route::resource('admin/products', 'AdminProductsController');
	Route::resource('admin/attributes', 'AdminAttributesController');
	Route::resource('admin/orders', 'AdminOrdersController');
	Route::resource('admin/order_statuses', 'AdminOrderStatusesController');
	Route::resource('admin/blogs', 'AdminBlogsController');
	Route::resource('admin/news', 'AdminNewsController');
	
	if(\Request::ajax()){
		Route::put('admin/update_price_for_category', 'AdminController@update_price_for_category');
	}
});

Route::get('{product_slug}', 'ProductController@index')->where('product_slug', '[0-9a-z][-a-z0-9.+]*_[0-9]{1,11}');
Route::get('{page_slug}', 'PageController@index')->where('page_slug', '[0-9a-z][-a-z0-9.+]*');

if(\Request::ajax()){
	Route::post('cart/store', 'CartController@store');
	Route::put('cart/update', 'CartController@update');
	Route::delete('cart/destroy', 'CartController@destroy');
	
	Route::post('set_rating/{id}/{val}', 'ProductController@set_rating')->where(['id'=>'[0-9]+', 'val'=>'[0-9]+']);
}