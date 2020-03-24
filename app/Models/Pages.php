<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $table = 'pages';
	
	public function get_top_menu($show_all = 0){
		$output = collect();
		
		if($show_all){
			$rows = $this->where('is_in_top_menu', 1)->get();
		}
		else{
			$rows = $this->where('is_in_top_menu', 1)->where('is_hide', 0)->get();	
		}
		
		foreach($rows as $val){
			$output->push((object)['slug'=>$val->slug, 'title'=>$val->title, 'fa'=>'', 'active'=>0]);
		}
		
		return $output;
	}
	public function get_links_for_footer($show_all = 0){
		if($show_all){
			return $this->where('is_in_footer', 1)->orderBy('position', 'asc')->get();
		}
		else{
			return $this->where('is_in_footer', 1)->where('is_hide', 0)->orderBy('position', 'asc')->get();
		}
	}
}
