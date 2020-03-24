<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\News;

class AdminNewsController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $output = [];
		$output['colNews'] = News::orderBy('created_at', 'desc')->get();
		
		return view('admin.news', $output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $output = [];
		
		return view('admin.news_create_edit', $output);
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
		
		if(sizeof($errors)){
			return redirect()->back()->withInput()->withErrors($errors);	
		}
		
		$News = new News;
		
		// проверим на дублирование slug
		if($News->where('slug', $slug)->count()){
			$errors[] = 'такая новость в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
			$News->slug = $slug;
			$News->title = $request->title;
			$News->meta_keywords = $request->meta_keywords;
			$News->meta_desc = $request->meta_desc;
			$News->description = $request->description;
			$News->is_hide = !empty($request->is_hide)? 1: 0;
		$News->save();
		
		return redirect('/admin/news');
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
		
		$data = News::find($id);
		
		$output['data'] = $data;
		
		return view('admin.news_create_edit', $output);
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
		
		$slug = str_slug($request->title, '-');
		
		if(empty($slug)){
			$errors[] = 'впишите название (title)';
		}
		
		if(sizeof($errors)){
			return redirect()->back()->withInput()->withErrors($errors);	
		}
		
		$News = new News;
		
		if($News->where('slug', $slug)->where('id', '<>', $id)->count()){
			$errors[] = 'такая новость в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
		$oTmp = $News->find($id);
			$oTmp->slug = $slug;
			$oTmp->title = $request->title;
			$oTmp->meta_keywords = $request->meta_keywords;
			$oTmp->meta_desc = $request->meta_desc;
			$oTmp->description = $request->description;
			$oTmp->is_hide = !empty($request->is_hide)? 1: 0;
		$oTmp->save();
		
		return redirect('/admin/news');
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
        $News = News::find($id);
		
		delete_images_from_description($News->description);
		$News->delete();
		
		return redirect('/admin/news');
    }
}
