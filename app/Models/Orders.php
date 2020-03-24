<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';
	protected $primaryKey = 'id';
	
	public function send_to_email($order_id){
		$data = $this->select('orders.*', 'order_statuses.name as status_text')
					 ->leftJoin('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
					 ->where('orders.id', $order_id)
					 ->first();
						
		$aUrl = parse_url(\Config::get("app.url"));
		$protocol = $aUrl['scheme'];
		$domain_name = $aUrl['host'];
		$smtp_login = !empty(\Session::get('aSettings.smtp_login'))? \Session::get('aSettings.smtp_login'): 'support@timax-krep.ru';
		$aEmails = !empty(\Session::get('aSettings.email'))? [$data->email, \Session::get('aSettings.email')]: [$data->email];
		
		$aData = [];
		$aData['domain'] = $protocol.'://'.$domain_name;
		$aData['colProducts'] = unserialize($data['products']);
		$aData['total_sum'] = $data['total_sum'];
		$aData['total_products'] = $data['total_products'];
		$aData['created_at'] = $data['created_at'];
		$aData['updated_at'] = $data['updated_at'];
		$aData['status_text'] = $data['status_text'];
		
		\Mail::send('emails.order', $aData, function($message) use ($order_id, $aEmails, $domain_name, $smtp_login){
			$message->from($smtp_login, $domain_name);
			$message->to($aEmails, $domain_name)->subject('Заказ #'.$order_id);
		});
	}
}
