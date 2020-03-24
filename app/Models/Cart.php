<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Products;
use App\Models\Categories;

class Cart extends Model
{
    protected $table = NULL;
	
	public function get_total_products(){
		$output = 0;
		
		if(!\Session::has('cart')){
			return $output;
		}
		
		foreach(\Session::get('cart') as $key => $val){
			$output += $val;
		}
		
		return $output;
	}
	public function get_total_sum(){
		if(!\Session::has('cart')){
			return ['total_sum' => 0, 'total_sum_with_discount' => 0];
		}
		
		$Products = new Products;
		
		$output = $aProductId = [];
		$total_price = $total_discount = 0;
		
		foreach(\Session::get('cart') as $key => $val){
			$aProductId[] = $key;
		}
		
		$data = $Products->select('id', 'price', 'discount')->whereIn('id', $aProductId)->get();
		foreach($data as $val){
			$product_id = $val->id;
			$amount = \Session::get('cart.'.$product_id);
			$price = $amount * $val->price;
			$discount_price = ($price * $val->discount) / 100;
			
			$total_price += $price;
			$total_discount += $price - $discount_price;
		}
		
		return ['total_sum' => round($total_price, 2) , 'total_sum_with_discount' => round($total_discount, 2)];
	}
	public function get_products(){
		$output = collect();
		$Categories = new Categories;
		
		if(\Session::has('cart')){
			$Products = new Products;
			$aCart = \Session::get('cart');
			
			foreach($aCart as $product_id => $amount){
				if(empty($amount)){
					continue;	
				}
				
				$tmp = $Products->get_data($product_id);
				$tmp->in_cart = $amount;
				
				$aCategoryIds = $Categories->get_parent_ids($tmp->category_id, 1);
				$tmp->category_names = $Categories->get_names($aCategoryIds);
				
				$output->push($tmp);
			}
		}
		
		return $output;		
	}
	public function remove($product_id){
		\Session::forget('cart.'.$product_id);	
	}
	public function add($product_id, $amount){
		if(\Session::has('cart.'.$product_id)){
			$value = \Session::get('cart.'.$product_id);
			\Session::put('cart.'.$product_id, $value + $amount);
		}
		else{
			\Session::push('cart.'.$product_id, 0); // нужно именно так, чтоб не было массива
			\Session::put('cart.'.$product_id, $amount);
		}
	}
	public function refresh($product_id, $amount){
		\Session::put('cart.'.$product_id, $amount);	
	}
}
