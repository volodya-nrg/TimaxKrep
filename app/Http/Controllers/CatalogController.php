<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Pages;
use App\Models\Cart;

class CatalogController extends Controller
{
    public function index(Request $request, $url = NULL){
		$output = $aCategoryIds = $data = [];
	
		$Categories = new Categories;
		$Products = new Products;
		$Pages = new Pages;
		$Cart = new Cart;
		
		// если есть подразделение, т.е. строки после /catalog/
		if(!is_null($url)){
			$aSlugs = explode("/", $url);
			
			if(!sizeof($aSlugs)){
				abort(404);	
			}
			
			$aCategoryIds = $Categories->convert_slug_to_id($aSlugs);
			
			if(empty($aCategoryIds)){
				abort(404);
			}
			
			// получим данные о категории
			$data = $Categories->find(end($aCategoryIds));
			$category_id = end($aCategoryIds);
		}
		else{
			$category_id = 0;
			$data = $Pages->where('slug', 'catalog')->first();
		}
		
		$aSumm = $Cart->get_total_sum();
		$total_sum = !empty($aSumm['total_sum_with_discount'])? $aSumm['total_sum_with_discount']: $aSumm['total_sum'];
		$paginate_page = (!empty($request->page) && is_numeric($request->page))? abs(intval($request->page)): 1;
		
		/* стандартные данные */
		$output['title'] = !empty($data->title)? $data->title: '';
		$output['meta_keywords'] = !empty($data->meta_keywords)? $data->meta_keywords: '';
		$output['meta_desc'] = !empty($data->meta_desc)? $data->meta_desc: '';
		$output['description'] = !empty($data->description)? $data->description: '';
		$output['total_sum'] = html_price($total_sum);
		$output['total_products'] = $Cart->get_total_products();
		$output['aTopMenuPages'] = $Pages->get_top_menu();
		$output['aCategories'] = $Categories->get_as_tree();
		$output['aCategoryTopMenu'] = $Categories->get_top_menu();
		$output['aCategorySelectedIds'] = $aCategoryIds;
		$output['aBreadcrumbs'] = $Categories->get_breadcrumbs($aCategoryIds);
		$output['aFooterLinks'] = $Pages->get_links_for_footer();
		
		/* данные для персонального шаблона */
		$output['show_as_product'] = !empty($data->show_as_product)? 1: 0;
		$output['aCategoryItems'] = $Categories->get_childs($category_id);
		$output['img_path'] = !empty($data->img_path)? $data->img_path: '';
		$output['paginator'] = $Products->get_via_category_id($category_id, $paginate_page, $output['show_as_product']? 30: 9);
		$output['aAttributes'] = [];
		
		// если это отображение списком, то нужно за ранее подхватить необходимые атрибуты продуктов
		if($output['show_as_product']){
			foreach($output['paginator']->items() as $val1){
				if(!empty($val1->inc_attributes)){
					foreach($val1->inc_attributes as $val2){
						if(!in_array($val2->name, $output['aAttributes'])){
							$output['aAttributes'][] = $val2->name;
						}
					}	
				}
			}
		}
		
		return view('catalog', $output);
	}
}
