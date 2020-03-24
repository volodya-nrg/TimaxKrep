<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Pages;
use App\Models\Cart;

class SearchController extends Controller
{
    public function index(Request $request){
		$output = [];
		
		$Categories = new Categories;
		$Products = new Products;
		$Pages = new Pages;
		$Cart = new Cart;
		
		$aSumm = $Cart->get_total_sum();
		$total_sum = !empty($aSumm['total_sum_with_discount'])? $aSumm['total_sum_with_discount']: $aSumm['total_sum'];
		$search = !empty($request->q)? str_limit(strip_tags($request->q), 255): ''; // обработку нужно делать тут
		$page = (!empty($request->page) && is_numeric($request->page))? abs(intval($request->page)): 1;
		
		/* стандартные данные */
		$output['title'] = '';
		$output['meta_keywords'] = '';
		$output['meta_desc'] = '';
		$output['search_query'] = $search;
		$output['total_sum'] = html_price($total_sum);
		$output['total_products'] = $Cart->get_total_products();
		$output['aTopMenus'] = $Pages->get_top_menu();
		$output['aCategoryTopMenu'] = $Categories->get_top_menu();
		$output['aFooterLinks'] = $Pages->get_links_for_footer();
		
		/* данные для персонального шаблона */
		$output['is_searched'] = isset($request->q)? 1: 0;
		$output['paginator'] = $Products->search($search, $page);
		
		return view('search', $output);
	}
}
