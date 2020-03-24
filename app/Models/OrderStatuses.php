<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatuses extends Model
{
    protected $table = 'order_statuses';
	protected $primaryKey = 'id';
	public $timestamps = false;
}
