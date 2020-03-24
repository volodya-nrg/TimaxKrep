<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Blogs;

class AdminBlogsController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $output = [];
		$output['colBlogs'] = Blogs::orderBy('created_at', 'desc')->get();
		
		return view('admin.blogs', $output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $output = [];
		
		return view('admin.blogs_create_edit', $output);
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
		
		$Blogs = new Blogs;
		
		// проверим на дублирование slug
		if($Blogs->where('slug', $slug)->count()){
			$errors[] = 'такой блог в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
			$Blogs->slug = $slug;
			$Blogs->title = $request->title;
			$Blogs->meta_keywords = $request->meta_keywords;
			$Blogs->meta_desc = $request->meta_desc;
			$Blogs->description = $request->description;
			$Blogs->is_hide = !empty($request->is_hide)? 1: 0;
		$Blogs->save();
		
		return redirect('/admin/blogs');
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
		
		$data = Blogs::find($id);
		
		$output['data'] = $data;
		
		return view('admin.blogs_create_edit', $output);
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
		
		$Blogs = new Blogs;
		
		if($Blogs->where('slug', $slug)->where('id', '<>', $id)->count()){
			$errors[] = 'такой блог в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
		$oTmp = $Blogs->find($id);
			$oTmp->slug = $slug;
			$oTmp->title = $request->title;
			$oTmp->meta_keywords = $request->meta_keywords;
			$oTmp->meta_desc = $request->meta_desc;
			$oTmp->description = $request->description;
			$oTmp->is_hide = !empty($request->is_hide)? 1: 0;
		$oTmp->save();
		
		return redirect('/admin/blogs');
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
        $Blogs = Blogs::find($id);
		
		delete_images_from_description($Blogs->description);
		$Blogs->delete();
		
		return redirect('/admin/blogs');
    }
}
