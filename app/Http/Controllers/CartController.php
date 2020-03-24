<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Pages;
use App\Models\Cart;

class CartController extends Controller
{
    public function index(){
		$output = [];
		
		$Categories = new Categories;
		$Pages = new Pages;
		$Cart = new Cart;
		
		$aSumm = $Cart->get_total_sum();
		
		$total_sum = !empty($aSumm['total_sum_with_discount'])? $aSumm['total_sum_with_discount']: $aSumm['total_sum'];
		$page = $Pages->where('slug', 'cart')->first();
		
		/* стандартные данные */
		$output['title'] = $page->title;
		$output['meta_keywords'] = $page->meta_keywords;
		$output['meta_desc'] = $page->meta_desc;
		$output['description'] = $page->description;
		$output['total_sum'] = html_price($total_sum);
		$output['total_sum_src'] = $total_sum;
		$output['total_products'] = $Cart->get_total_products();
		$output['aTopMenuPages'] = $Pages->get_top_menu();
		$output['aFooterLinks'] = $Pages->get_links_for_footer();
		$output['aCategoryTopMenu'] = $Categories->get_top_menu();
		
		/* данные для персонального шаблона */
		$output['aProducts'] = $Cart->get_products();
		
		return view('cart', $output);
	}
	public function store(Request $request){
		$result = false;
		$msg = "";
		$product_id = (!empty($request->product_id) && is_numeric($request->product_id))? (int)$request->product_id: 0;
		$amount = !empty($request->amount)? (int)$request->amount: 1;
		$Cart = new Cart;
		
		if($product_id && $amount){
			$Cart->add($product_id, $amount);	
			$result = true;
		}
		
		$aSumm = $Cart->get_total_sum();
		$total_sum = !empty($aSumm['total_sum_with_discount'])? $aSumm['total_sum_with_discount']: $aSumm['total_sum'];
		$total_sum = html_price($total_sum);
		$total_products = $Cart->get_total_products();
		
		return response()->json(['result'=>$result, 'msg'=>$msg, 'total_sum'=>$total_sum, 'total_products'=>$total_products]);
	}
	public function update(Request $request){
		$result = false;
		$msg = "";
		$product_id = (!empty($request->product_id) && is_numeric($request->product_id))? (int)$request->product_id: 0;
		$amount = !empty($request->amount)? (int)$request->amount: 0;
		$Cart = new Cart;
		
		if($product_id && $amount && $request->session()->has('cart.'.$product_id)){
			$Cart->refresh($product_id, $amount);
			$result = true;
		}
		
		$aSumm = $Cart->get_total_sum();
		$total_sum = !empty($aSumm['total_sum_with_discount'])? $aSumm['total_sum_with_discount']: $aSumm['total_sum'];
		$total_sum = html_price($total_sum);
		$total_products = $Cart->get_total_products();
		
		return response()->json(['result'=>$result, 'msg'=>$msg, 'total_sum'=>$total_sum, 'total_products'=>$total_products]);
	}
	public function destroy(Request $request){
		$result = false;
		$msg = "";
		$product_id = (!empty($request->product_id) && is_numeric($request->product_id))? $request->product_id: 0;
		$Cart = new Cart;
		
		if($product_id && $request->session()->has('cart.'.$product_id)){
			$Cart->remove($product_id);
			$result = true;
		}
		
		$aSumm = $Cart->get_total_sum();
		$total_sum = !empty($aSumm['total_sum_with_discount'])? $aSumm['total_sum_with_discount']: $aSumm['total_sum'];
		$total_sum = html_price($total_sum);
		$total_products = $Cart->get_total_products();
		
		return response()->json(['result'=>$result, 'msg'=>$msg, 'total_sum'=>$total_sum, 'total_products'=>$total_products]);
	}
	
}
