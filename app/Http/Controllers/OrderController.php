<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\OrderStatuses;
use App\Models\Categories;
use App\Models\Orders;
use App\Models\Pages;
use App\Models\Cart;

class OrderController extends Controller
{
    public function index(){
		$output = [];
		
		if(!\Session::has('data_is_sent') && !sizeof(\Session::get('cart'))){
			return redirect('/cart');
		}
		
		$Categories = new Categories;
		$Pages = new Pages;
		$Cart = new Cart;
		
		$aSumm = $Cart->get_total_sum();
		$total_sum = !empty($aSumm['total_sum_with_discount'])? $aSumm['total_sum_with_discount']: $aSumm['total_sum'];
		$page = $Pages->where('slug', 'order')->first();
		
		// если цена оформления ниже порога, то переходим назад в корзину
		if(	!empty(\Session::get('aSettings.cart_min_treshhold')) && 
			($total_sum < \Session::get('aSettings.cart_min_treshhold')) ){
			return redirect('/cart');
		}
		
		/* стандартные данные */
		$output['title'] = $page->title;
		$output['meta_keywords'] = $page->meta_keywords;
		$output['meta_desc'] = $page->meta_desc;
		$output['description'] = $page->description;
		$output['total_sum'] = html_price($total_sum);
		$output['total_products'] = $Cart->get_total_products();
		$output['aTopMenuPages'] = $Pages->get_top_menu();
		$output['aCategoryTopMenu'] = $Categories->get_top_menu();
		$output['aFooterLinks'] = $Pages->get_links_for_footer();
		
		/* данные для персонального шаблона */
		$output['aUserData'] = !empty($_COOKIE['client_data'])? unserialize($_COOKIE['client_data']): [];
		$output['data_is_sent'] = \Session::has('data_is_sent')? 1: 0;
		$output['user_email'] = \Session::has('user_email')? \Session::get('user_email'): ""; // после завершения заказа, покажем пользователю эту переменную
		
		return view('order', $output);
	}
	public function check(Request $request){
		$this->validate($request, array(
			'email' => 'required|email',
			'tel' => 'required|min:5|max:20',
			'name' => 'sometimes|string|min:2|max:50',
		));
		
		$Cart = new Cart;
		$Orders = new Orders;
		$OrderStatuses = new OrderStatuses;
		
		$aSumm = $Cart->get_total_sum();
		$total_sum = !empty($aSumm['total_sum_with_discount'])? $aSumm['total_sum_with_discount']: $aSumm['total_sum'];
		$colOrderStatuses = $OrderStatuses->where('is_default', 1)->first();
		
		// создадим запись о заказе
			$Orders->email = $request->email;
			$Orders->tel = $request->tel;
			$Orders->name = $request->name;
			$Orders->comment = $request->comment;
			$Orders->ip = $request->ip();
			$Orders->products = serialize($Cart->get_products());
			$Orders->status_id = (!is_null($colOrderStatuses) && $colOrderStatuses->count())? $colOrderStatuses->id: 0;
			$Orders->total_sum = $total_sum;
			$Orders->total_products = $Cart->get_total_products();
		$Orders->save();
		
		// запомним данные пользователя, чтоб дважды потом не вбивать
		setcookie('client_data', serialize([
			'email' => $request->email,
			'tel' => $request->tel,
			'name' => $request->name,
			'comment' => $request->comment,
		]), time() + 86400 * 30);
		
		// оповестим о новом заказе
		$Orders->send_to_email($Orders->id);
		
		$request->session()->flash('data_is_sent', 1);
		$request->session()->flash('user_email', $request->email);
		$request->session()->forget('cart');
		
		return redirect('/order');
	}
}
