<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\OrderStatuses;

class AdminOrderStatusesController extends Controller
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
		$output['colOrderStatuses'] = OrderStatuses::orderBy('id', 'asc')->get();
		
		return view('admin.order_statuses', $output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $output = [];
		
		return view('admin.order_statuses_create_edit', $output);
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
		
		$OrderStatuses = new OrderStatuses;
		$request->name = trim($request->name);
		
		if($OrderStatuses->where('name', $request->name)->count()){
			$errors[] = 'такой статус в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
			$OrderStatuses->name = $request->name;
			$OrderStatuses->is_default = !empty($request->is_default)? 1: 0;
		$OrderStatuses->save();
		
		return redirect('/admin/order_statuses');
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
		$output['data'] = OrderStatuses::find($id);
		
		return view('admin.order_statuses_create_edit', $output);
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
		
		$OrderStatuses = new OrderStatuses;
		$request->name = trim($request->name);
		
		if($OrderStatuses->where('name', $request->name)->where('id', '<>', $id)->count()){
			$errors[] = 'такой статус в базе уже есть';
			return redirect()->back()->withInput()->withErrors($errors);
		}
	
		$oTmp = $OrderStatuses->find($id);
			$oTmp->name = $request->name;
			$oTmp->is_default = !empty($request->is_default)? 1: 0;
		$oTmp->save();
		
		return redirect('/admin/order_statuses');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $OrderStatuses = OrderStatuses::find($id);
		$OrderStatuses->delete();
		
		return redirect('/admin/order_statuses');
    }
}
