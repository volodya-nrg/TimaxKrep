<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Categories;
use App\Models\Blogs;
use App\Models\Pages;
use App\Models\Cart;
use Carbon;

class BlogsController extends Controller
{
   public function index(Request $request, $slug = ""){
		$output = [];
		
		$Categories = new Categories;
		$Carbon = new Carbon;
		$Pages = new Pages;
		$Blogs = new Blogs;
		$Cart = new Cart;
		
		// нужно проверить: можно ли вообще сюда заходить
		$tmp = $Pages->select('is_hide')->where('slug', 'blogs')->first();
		if(!empty($tmp->is_hide)){
			abort(404);
		}
		
		$colBreadcrumbs = collect();
		$colBreadcrumbs->push(['slug'=>'/', 'name'=>'<i class="fa fa-home"></i>', 	'active'=> 1]);
		
		if($slug != ""){
			$data = $Blogs->get_via_slug($slug);
			
			if($data === FALSE){
				abort(404);
			}
			
			$output['title'] = $data->title;
			$output['meta_keywords'] = $data->meta_keywords;
			$output['meta_desc'] = $data->meta_desc;
			$output['data'] = $data;
			
			$dt = $Carbon->parse($data->created_at);
			$output['created'] = $dt->day.".".$dt->month.".".$dt->year." г.";
			
			$colBreadcrumbs->push(['slug'=>'blogs', 'name'=>'блоги', 'active'=> 1]);
			$colBreadcrumbs->push(['slug'=>$data->slug, 'name'=>$data->title, 'active'=> 0]);
		}
		else{
			// данные категории (Блоги)
			$tmp = $Pages->where('slug', 'blogs')->first();
			
			$output['title'] = $tmp->title;
			$output['meta_keywords'] = $tmp->meta_keywords;
			$output['meta_desc'] = $tmp->meta_desc;
			$output['description'] = $tmp->description;
			
			$page = (!empty($request->page) && is_numeric($request->page))? abs(intval($request->page)): 1;
			$output['page'] = $page;
			$output['paginator'] = $Blogs->get_list($page);
			
			$colBreadcrumbs->push(['slug'=>$tmp->slug, 'name'=>mb_strtolower($tmp->title), 'active'=> 0]);
		}
		
		$aSumm = $Cart->get_total_sum();
		$total_sum = !empty($aSumm['total_sum_with_discount'])? $aSumm['total_sum_with_discount']: $aSumm['total_sum'];
		
		/* стандартные данные */
		$output['total_sum'] = html_price($total_sum);
		$output['total_products'] = $Cart->get_total_products();
		$output['aTopMenuPages'] = $Pages->get_top_menu();
		$output['aCategoryTopMenu'] = $Categories->get_top_menu();
		$output['aBreadcrumbs'] = $colBreadcrumbs;
		$output['aFooterLinks'] = $Pages->get_links_for_footer();
		
		/* данные для персонального шаблона */
		
		return view('blogs', $output);
	}
}
