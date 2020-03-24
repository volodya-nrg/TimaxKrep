<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Pages;
use App\Models\Cart;

class ProductController extends Controller
{
    public function index($product_slug){
		$output = [];
	
		$Categories = new Categories;
		$Products = new Products;
		$Pages = new Pages;
		$Cart = new Cart;
		
		$data = $Products->get_data_via_slug($product_slug);
		
		if($data === FALSE || !empty($data->is_hide)){
			abort(404);	
		}
		
		$aSumm = $Cart->get_total_sum();
		$total_sum = !empty($aSumm['total_sum_with_discount'])? $aSumm['total_sum_with_discount']: $aSumm['total_sum'];
		
		/* стандартные данные */
		$output['title'] = $data->title;
		$output['meta_keywords'] = $data->meta_keywords;
		$output['meta_desc'] = $data->meta_desc;
		
		$output['total_sum'] = html_price($total_sum);
		$output['total_products'] = $Cart->get_total_products();
		$output['aTopMenuPages'] = $Pages->get_top_menu();
		$output['aCategoryTopMenu'] = $Categories->get_top_menu();
		$output['aFooterLinks'] = $Pages->get_links_for_footer();
		
		$aParentIds = $Categories->get_parent_ids($data->category_id, true);
		$output['aBreadcrumbs'] = $Categories->get_breadcrumbs($aParentIds, 1);
		
		/* данные для персонального шаблона */
		$output['data'] = $data;
		$output['aDopProducts'] = $Products->get_similar($data->id, $data->category_id, $data->similar);
		
		return view('product', $output);
	}
	public function set_rating(Request $request, $id, $val){
		$result = false;
		$msg = "";
		$product_id = $id;
		$Products = new Products;
		
		if(!$Products->has_rating($product_id)){
			$Products->set_rating($product_id, $val);
			$msg = $Products->get_rating($product_id);
			$result = true;
		}
		else{
			$msg = "Запрос не обработан!";
		}
		
		return response()->json(['result' => $result, 'msg' => $msg]);
	}
}
