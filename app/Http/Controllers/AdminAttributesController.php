<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Attributes;

class AdminAttributesController extends Controller
{
	private $pattern_name = 'required|min:2|max:50';
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $output = [];
		
		$output['aAttributes'] = Attributes::orderBy('id', 'desc')->get();
		
		return view('admin.attributes', $output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $output = [];
		
		return view('admin.attributes_create_edit', $output);
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
			'name' => $this->pattern_name,
		));
		
		$Attributes = new Attributes;
		$request->name = trim($request->name);
		
		if($Attributes->where('name', $request->name)->count()){
			$errors[] = 'такой атрибут в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
			$Attributes->name = $request->name;
		$Attributes->save();
		
		return redirect('/admin/attributes');
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
		$output['data'] = Attributes::find($id);
		
		return view('admin.attributes_create_edit', $output);
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
			'name' => $this->pattern_name,
		));
		
		$Attributes = new Attributes;
		$request->name = trim($request->name);
		
		if($Attributes->where('name', $request->name)->where('id', '<>', $id)->count()){
			$errors[] = 'такой атрибут в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
		$oTmp = $Attributes->find($id);
			$oTmp->name = $request->name;
		$oTmp->save();
		
		return redirect('/admin/attributes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Attributes = Attributes::find($id);
		$Attributes->delete();
		
		return redirect('/admin/attributes');
    }
}
