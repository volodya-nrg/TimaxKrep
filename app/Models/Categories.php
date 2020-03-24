<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	/*
	 *  $array_slugs - ['slug1', 'slug2', ... 'slugN']	
	 */
	public function convert_slug_to_id($array_slugs){
		$output = [];
		$total_slugs = sizeof($array_slugs);
		$parents = "";
		
		if(!$total_slugs){
			return $output;
		}
		
		foreach($array_slugs as $key => $val){
			$tmp = $this->select('id')
						->where('slug', $val)
						->where('parents', $parents)
						->first();
			
			if(empty($tmp->id)){
				return FALSE;	
			}
			
			$output[] = $tmp->id;
			
			if($key === 0){
				$parents = (string)$tmp->id;	
			}
			else{
				$parents .= "_".(string)$tmp->id;		
			}
		}
		
		return $output;
	}
	/*
	 *  возвращает коллекцию всех данных о каждом элементе
	 */
	public function get_as_tree($show_all = 0){
		$receiver = collect();
		
		// для админки используются все, в продакшене только открытые	
		if($show_all){
			$data = $this->orderBy('parent')->get();	
		}
		else{
			$data = $this->where('is_hide', 0)->orderBy('parent', 'asc')->get();	
		}
		
		// подхватим в начале самых главных
		foreach($data as $val){
			if(empty($val->parent)){
				$receiver->push($val);
			}
		}
		
		// далее подхватим потомков
		foreach($data as $val){
			if(empty($val->parent)){
				continue;
			}
			$this->append_el($receiver, $val->parent, $val);
		}
		
		return $receiver;
	}
	/*
	 * $array_ids - [1,2, ... N]	
	 * output collection
	 */
	public function get_breadcrumbs($array_ids, $last_active = 0){
		$output = collect();
		$output->push(['slug'=>'/', 		'name'=>'<i class="fa fa-home"></i>', 	'active'=> 1]);
		$output->push(['slug'=>'catalog', 	'name'=>'каталог', 						'active'=> 1]);
						  
		$rows = $this->select('slug', 'name')->whereIn('id', $array_ids)->orderBy('id', 'asc')->get();
		$total = $rows->count();
		foreach($rows as $key => $val){
			$active = 1;
			
			if($total == ($key+1) && !$last_active){
				$active = 0;
			}
			
			$output->push(['slug'=>'/'.$val->slug, 'name'=>$val->name, 'active'=>$active]);
		}
		
		return $output;
	}
	public function get_parent_ids($category_id, $append_cur_id = false){
		$output = [];
		$result = $this->select('parents')->where('id', $category_id)->first();
		
		if(!empty($result->parents)){
			$target = $result->parents;
			
			if(substr_count($target, "_")){
				$output = explode("_", $target);	
			}
			else{
				$output[] = $target;	
			}
		}
		
		if($append_cur_id){
			$output[] = $category_id;	
		}
		
		if(sizeof($output)){
			foreach($output as &$val){
				$val = (int)$val;	
			}
		}
		
		return $output;
	}
	/*
	 *  достает id потомков, включая текущий id (опционально)
	 *  $array_ids - [1,2, ... N]	
	 */
	public function get_child_ids($parent_id, $append_cur_id = false){
		$output = [];
		
		// если достать все
		if($parent_id === 0){
			// тут нужно достать все id
			$tmp = $this->select('id')->orderBy('id', 'asc')->get();
			
			foreach($tmp as $val){
				$output[] = $val->id;
			}
		}
		else{
			// при необходимости предка поместим в массив
			if($append_cur_id){
				$output[] = $parent_id;
			}
			
			$this->collect_all_child_ids($output, $this->get_as_tree(1), $parent_id);
		}
		
		return $output;
	}
	 
	public function get_childs($parent_id, $show_all = 0){
		if($show_all){
			return $this->where('parent', abs(intval($parent_id)))->orderBy('position', 'asc')->get();	
		}
		else{
			return $this->where('parent', abs(intval($parent_id)))->where('is_hide', 0)->orderBy('position', 'asc')->get();	
		}
	}
	public function get_names($array_parent_ids){
		$output = [];
		$tmp = $this->select('name')->whereIn('id', $array_parent_ids)->get()->toArray();
		
		foreach($tmp as $val){
			$output[] = $val['name'];
		}
		
		return 	$output;
	}
	public function get_top_menu($reverse = 0, $show_all = 0){
		$receiver = collect();
		
		if($show_all){
			$data = $this->where('show_in_top_menu', $reverse? 0: 1)->orderBy('parent', 'asc')->get();	
		}
		else{
			$data = $this->where('show_in_top_menu', $reverse? 0: 1)->where('is_hide', 0)->orderBy('parent', 'asc')->get();
		}

		// подхватим в начале самых главных
		foreach($data as $val){
			if(empty($val->parent)){
				$receiver->push($val);
			}
		}
		
		// далее подхватим потомков
		foreach($data as $val){
			if(empty($val->parent)){
				continue;
			}
			$this->append_el($receiver, $val->parent, $val);
		}
		
		return $receiver;
	}
	
	private function append_el(&$receiver, $parent_id, $input){
		foreach($receiver as &$val){
			if($val->id == $parent_id){
				if(!isset($val->childs)){
					$val->childs = collect();
				}
				$val->childs->push($input);
			}
			elseif(isset($val->childs) && $val->childs->count()){
				$this->append_el($val->childs, $parent_id, $input);
			}
		}
	}
	private function collect_all_child_ids(&$receiver, $colTree, $parent_id, $is_save = false){
		foreach($colTree as $val){
			// если пошло запоминание
			if($is_save){
				$receiver[] = $val->id;
				
				if(isset($val->childs) && $val->childs->count()){
					$this->collect_all_child_ids($receiver, $val->childs, $parent_id, true);
				}
			}
			// если мы добрались до нужной точки
			elseif($val->id == $parent_id && isset($val->childs) && $val->childs->count()){
				$this->collect_all_child_ids($receiver, $val->childs, $parent_id, true);
			}
			// иначе продолжим поиск по потомкам
			elseif(isset($val->childs) && $val->childs->count()){
				$this->collect_all_child_ids($receiver, $val->childs, $parent_id, false);
			}
		}
	}
}
