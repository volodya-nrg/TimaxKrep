<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class News extends Model
{
    protected $table = 'news';
	
	public function get_via_slug($slug){
		$output = collect();
		
		$output = $this->where('slug', $slug)->where('is_hide', 0)->first();
		
		if(empty($output->id)){
			return FALSE;	
		}
		
		return $output;
	}
	public function get_list($curPage = 1, $perPage = 20){
		$output = collect();
		
		$rows = $this->where('is_hide', 0)->orderBy('created_at', 'desc')->get();
		$col = $rows->forPage($curPage, $perPage);
		
		foreach($col as &$val){
			$output->push($val);
		}
		
		$paginator = new LengthAwarePaginator($output, $rows->count(), $perPage);
		$paginator->setPath(request()->getPathInfo());
		
		return $paginator;	
	}
}
