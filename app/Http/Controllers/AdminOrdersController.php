<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\OrderStatuses;
use App\Models\Orders;

class AdminOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $output = [];
		
		$Orders = new Orders;
		
		$output['orders'] =  $Orders->select('orders.*', 'order_statuses.name as status_text')
									->leftJoin('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
									->orderBy('orders.created_at', 'desc')
									->get();
		
		return view('admin.orders', $output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
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
		
		$OrderStatuses = new OrderStatuses;
		$Orders = new Orders;
		
		$output['data'] = $Orders->find($id);
		$output['colOrderStatuses'] = $OrderStatuses->all();
		
		return view('admin.orders_edit', $output);
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
			'email' => 'required|email',
			'tel' => 'required|min:5|max:20',
			'status_id' => 'sometimes|required|int',
			'name' => 'sometimes|string|min:2|max:50',
		));
		
		$Orders = new Orders;
		
		$oTmp = $Orders->find($id);
		
		// сохраним данные для дальнейшего использования
		$status_id = $oTmp->status_id;
		
			$oTmp->email = $request->email;
			$oTmp->tel = $request->tel;
			$oTmp->name = $request->name;
			$oTmp->comment = $request->comment;
			$oTmp->status_id = !empty($request->status_id)? (int)$request->status_id: 0;
			
		$oTmp->save();
		
		// если статус изменился, то нужно отослать уведомление заказчику
		if(!empty($request->status_id) && $status_id != $request->status_id){
			$Orders->send_to_email($id);
		}
		
		return redirect('/admin/orders');
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
		
        $Orders = Orders::find($id);
		$Orders->delete();
		
		return redirect('/admin/orders');
    }
}
