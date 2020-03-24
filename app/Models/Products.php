<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Categories;

class Products extends Model
{
    protected $table = 'products';
	
	public function get_data($id, $append_images_all = 0){
		$output = [];
		$output = $this->find($id);
		
		// тут база возвращает именно массив
		if($append_images_all){
			$aImg = \DB::table('product_images')->where('product_id', $id)->orderBy('position', 'asc')->get();
		}
		else{
			$aImg = \DB::table('product_images')->where('product_id',$id)->where('is_hide',0)->orderBy('position','asc')->get();
		}
		
		$output->images = sizeof($aImg)? collect($aImg): collect(); // сделаем именно коллекцию, так удобней работать
		$output->inc_attributes = \DB::table('product_attributes')
									->select('attributes.id', 'attributes.name', 'product_attributes.value')
									->leftJoin('attributes', 'attributes.id', '=', 'product_attributes.attribute_id')
									->where('product_attributes.product_id', '=', $id)
									->get();
		$output->similar = !empty($output->similar)? explode(',', $output->similar): [];
		$output->already_installed_rating = $this->has_rating($id)? 1: 0;
		$output->in_cart = 0;
		$output->price = (float)$output->price;
		
		// если в настройках админки указанно что цеплять фото категории, при отсутствии фото у самого продукта
		if(!empty(\Session::has('aSettings.set_photo_cat_to_photo_product'))){
			$Categories = new Categories;
			$tmp = $Categories->select('img_path')->where('id', $output->category_id)->first();
			$output->spare_photo = $tmp->img_path;
		}
		else{
			$output->spare_photo = '';	
		}
		
		return $output;
	}
	public function get_data_via_slug($uniq_str){
		$result = $this->select('id')->where('slug', $uniq_str)->where('is_hide', 0)->orderBy('name', 'asc')->first();
		
		if(empty($result->id)){
			return FALSE; // тут нужно так, abort происходит в контроллере
		}
		
		return $this->get_data($result->id);
	}
	public function get_random(){
		$output = collect();
		$tmp 	= $this->select('id')
					   ->where('is_hide', 0)
					   ->orderBy(\DB::raw('RAND()'))
					   ->limit(6)
					   ->get();
		
		foreach($tmp as $val){
			$output->push($this->get_data($val->id));
		}
					   
		return $output;
	}
	public function set_attr($product_id, $attr_id, $value){
		\DB::insert('INSERT INTO `product_attributes` (product_id, attribute_id, value) values (?, ?, ?)', [$product_id, $attr_id, $value]);
	}
	public function delete_all_attrs($product_id){
		\DB::table('product_attributes')->where('product_id', $product_id)->delete();
	}
	public function get_via_category_id($category_id, $curPage = 1, $perPage = 16){
		$output = collect();
		$category_id = abs(intval($category_id));
		$rows = $this->select('id')->where('category_id', $category_id)->where('is_hide', 0)->orderBy('name', 'asc')->get();
		$col = $rows->forPage($curPage, $perPage);
		
		foreach($col as &$val){
			$output->push($this->get_data($val->id));
		}
		
		$paginator = new LengthAwarePaginator($output, $rows->count(), $perPage);
		$paginator->setPath(request()->getPathInfo());
		
		return $paginator;
	}
	public function get_similar($product_id, $category_id, $aIds = array()){
		$output = collect();
		
		// если нету данных которые вписали через админку, то подцепим похожие товары из данной категории
		if(!sizeof($aIds)){
			$tmp = $this->select('id')
						 ->where('category_id', $category_id)
						 ->where('id', '<>', $product_id)
						 ->orderBy(\DB::raw('RAND()'))
						 ->limit(5)
						 ->get();
			foreach($tmp as $val){
				$aIds[] = $val->id;
			}			 
		}
		
		foreach($aIds as $item){
			$output->push($this->get_data($item));
		}
		
		return $output;
	}
	public function search($q, $curPage = 1, $perPage = 16){
		$output = collect();
		
		if($q == "" || mb_strlen($q) < 3){
			return $output;
		}
		
		$rows = $this->select('id')->where('title', 'LIKE', '%'.$q.'%')->where('is_hide', 0)->orderBy('name', 'asc')->get();
		$col = $rows->forPage($curPage, $perPage);
		
		foreach($col as &$val){
			$output->push($this->get_data($val->id));
		}
		
		$paginator = new LengthAwarePaginator($output, $rows->count(), $perPage);
		$paginator->setPath(request()->getPathInfo());
		$paginator->appends(['q' => $q]);
		
		return $paginator;
	}
	public function get_bestsellers($limit = 8){
		$output = collect();
		
		$rows = $this->select('id')->where('is_hide', 0)->orderBy('rating', 'desc')->limit($limit)->get();
		foreach($rows as $val){
			$output->push($this->get_data($val->id));
		}
		
		return $output;
	}
	public function get_lasted($limit = 8){
		$output = collect();
		
		$rows = $this->select('id')->where('is_hide', 0)->orderBy('updated_at', 'desc')->limit($limit)->get();
		foreach($rows as $val){
			$output->push($this->get_data($val->id));
		}
		
		return $output;
	}
	public function get_rating($product_id){
		$total = $sum = 0;	
		
		$data = \DB::table('product_rating')->where('product_id', $product_id)->get();
		foreach($data as $val){
			$sum += $val->val;
			$total++;
		}
		
		$result = round($sum / $total);
		
		return $result;
	}
	public function set_rating($id, $val){
		\DB::insert('INSERT INTO `product_rating` (product_id, val, ses_id) values (?, ?, ?)', [$id, $val, \Session::getId()]);
		$this->where('id', $id)->update(['rating' => $this->get_rating($id)]);
	}
	public function has_rating($product_id){
		return \DB::table('product_rating')
					->where('product_id', $product_id)
					->where('ses_id', \Session::getId())
					->where('kogda', '>', \DB::raw('NOW() - 86400'))
					->count();
	}
	public function update_price_via_percent($percent, $category_ids, $method = 1){
		\DB::update('UPDATE `'.$this->table.'` 
						SET `price` = (`price` '.($method? '+': '-').' `price` * '.($percent/100).')
						WHERE category_id IN ('.implode(',', $category_ids).')');
	}
	
	public function delete_some_images($filepath){
		if(is_array($filepath)){
			foreach($filepath as $file){
				$this->delete_one_image($file);
			}
		}
		else{
			$this->delete_one_image($filepath);
		}
	}
	private function delete_one_image($file){
		$tmp = public_path().$file;
				
		if(file_exists($tmp) && unlink($tmp)){
			\DB::table('product_images')->where('path', $file)->delete();	
		}
	}
}
