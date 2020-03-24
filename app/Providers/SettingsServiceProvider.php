<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Это основные переменные из админки с БД
     *
     * @return void
     */
    public function boot()
    {
		define("DIR_IMAGES", "images");
		
		if(!\Session::has('cart'))	{
			\Session::put('cart');
		}
		
		if(\Session::has('aSettings'))	{
			return;
		}
		
        $output = [];
		$rows = \DB::table('basic')->get();
		foreach($rows as $val){
			$data = @unserialize($val->value);
			$output[$val->name] = ($data !== FALSE)? unserialize($val->value): $val->value;
		}
		
		\Session::put('aSettings', $output);
		
		// в зависимости от режима, ставим соответствующее значение
		\Config::set('app.debug', !empty($output['mode'])? false: true);
		
		// настроим под себя почту	
		if(!empty($output['smtp_host']) && !empty($output['smtp_login']) && !empty($output['smtp_pass']) && 
		   !empty($output['smtp_port']) && is_numeric($output['smtp_port']) ){
			\Config::set('mail.driver', 'smtp');
			\Config::set('mail.host', $output['smtp_host']);
			\Config::set('mail.port', $output['smtp_port']);
			\Config::set('mail.from', $output['smtp_login']);
			\Config::set('mail.username', $output['smtp_login']);
			\Config::set('mail.password', $output['smtp_pass']);	   
		}
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
