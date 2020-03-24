<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Categories;
use App\Models\Pages;
use App\Models\Cart;

class PageController extends Controller
{
   public function index($page_slug){
		$output = [];
		
		if($page_slug == ""){
			abort(404);	
		}
		
		$Categories = new Categories;
		$Pages = new Pages;
		$Cart = new Cart;
		
		$data = $Pages->where('slug', $page_slug)->where('is_hide', 0)->first();
		if(empty($data->id) || !empty($data->is_hide)){
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
		
		/* данные для персонального шаблона */
		$output['description'] = $data->description;
		
		return view('page', $output);
	}
}
