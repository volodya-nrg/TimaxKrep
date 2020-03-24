<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Pages;

class AdminPagesController extends Controller
{
	public $disallow_slugs = ['index', 'cart', 'order', 'catalog', 'admin', 'login', 'logout',
							  'sitemap', 'sitemap_xml', 'news', 'blogs', 'search', 'imager'];
	private $aCanHide = ['news', 'blogs'];
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $output = [];
		$output['aPages'] = Pages::orderBy('position', 'asc')->get();
		$output['aDisallowSlugs'] = $this->disallow_slugs;
		
		return view('admin.pages', $output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $output = [];
		
		return view('admin.pages_create_edit', $output);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $errors = [];
		
		$this->validate($request, array(
			'title' => 'required|min:2|max:255',
		));
		
		$slug = str_slug($request->title, '-');
		
		if(empty($slug)){
			$errors[] = 'впишите название (title)';
			
		}
		elseif(in_array($slug, $this->disallow_slugs)){
			$errors[] = 'вы используете в названии зарезирвированное слово';
		}
		
		if(sizeof($errors)){
			return redirect()->back()->withInput()->withErrors($errors);	
		}
		
		$Pages = new Pages;
		
		// проверим на дублирование slug
		if($Pages->where('slug', $slug)->count()){
			$errors[] = 'такая страница в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
			$Pages->slug = $slug;
			$Pages->title = $request->title;
			$Pages->meta_keywords = $request->meta_keywords;
			$Pages->meta_desc = $request->meta_desc;
			$Pages->description = $request->description;
			$Pages->position = (int)$request->position;
			$Pages->is_hide = !empty($request->is_hide)? 1: 0;
			$Pages->is_in_top_menu = !empty($request->is_in_top_menu)? 1: 0;
			$Pages->is_in_footer = !empty($request->is_in_footer)? 1: 0;
		$Pages->save();
		
		return redirect('/admin/pages');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $output = [];
		
		$data = Pages::find($id);
		
		$output['data'] = $data;
		$output['is_reserved'] = in_array($data->slug, $this->disallow_slugs)? 1: 0;
		$output['aCanHide'] = $this->aCanHide;
		
		return view('admin.pages_create_edit', $output);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $errors = [];
		
		$this->validate($request, array(
			'slug' => 'required|min:2|max:255',
			'title' => 'required|min:2|max:255',
		));
		
		if(in_array($request->slug, $this->disallow_slugs)){
			$slug = $request->slug;
			//$request->is_hide = 0;
			//$request->is_in_top_menu = $request->is_in_footer = ;
		}
		else{
			$slug = str_slug($request->title, '-');
			
			if(empty($slug)){
				$errors[] = 'впишите название (title)';
				
			}
			elseif(in_array($slug, $this->disallow_slugs)){
				$errors[] = 'вы используете в названии зарезирвированное слово';
			}
			
			if(sizeof($errors)){
				return redirect()->back()->withInput()->withErrors($errors);	
			}
		}
		
		$Pages = new Pages;
		
		if($Pages->where('slug', $slug)->where('id', '<>', $id)->count()){
			$errors[] = 'такая страница в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
		$oTmp = $Pages->find($id);
			$oTmp->slug = $slug;
			$oTmp->title = $request->title;
			$oTmp->meta_keywords = $request->meta_keywords;
			$oTmp->meta_desc = $request->meta_desc;
			$oTmp->description = $request->description;
			$oTmp->position = (int)$request->position;
			$oTmp->is_hide = !empty($request->is_hide)? 1: 0;
			$oTmp->is_in_top_menu = !empty($request->is_in_top_menu)? 1: 0;
			$oTmp->is_in_footer = !empty($request->is_in_footer)? 1: 0;
		$oTmp->save();
		
		return redirect('/admin/pages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$errors = [];	
        $Pages = Pages::find($id);
		
		// предохранимся от удаления зарезирвированной страницы
		if(in_array($Pages->slug, $this->disallow_slugs)){
			$errors[] = 'нельзя удалить зарезирвированную страницу';
			return redirect()->back()->withError($errors);	
		}
		
		delete_images_from_description($Pages->description);
		$Pages->delete();
		
		return redirect('/admin/pages');
    }
}
