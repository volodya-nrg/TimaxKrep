@extends('layouts.main')

@section('content')
    @if($aProducts->count())
    	<script>
        	var csrf_token = "{{ csrf_token() }}";
        </script>
    	
        @if(!empty($description))
            <div class="row">
                <div class="col-xs-12">
                    {!! $description !!}
                </div>
            </div>
        @endif
        
        <div id="cart_products">
            <table class="table table-striped">
                @foreach($aProducts as $val)
                    <tr>
                        <td width="60">
                        	<a href="/{{ $val->slug }}" target="_blank" rel="nofollow">
                             	@if(!empty($val->img_path))
                                    <img height="40" src="/imager{{ $val->img_path }}/0/40" />
                               @elseif(!empty($val->spare_photo))
            						<img height="40" src="/imager{{ $val->spare_photo }}/0/40" />
                               @else
                                    <img height="40" src="/img/no_image_100.jpg" />
                               @endif
                           </a>
                        </td>
                        <td>
                        	<a href="/{{ $val->slug }}" target="_blank">{{ $val->name }}</a>
                        	<br />
                           <font class="text-muted">{{ implode(' / ', $val->category_names) }}</font> 	
                        </td>
                        <td align="right" valign="middle" width="190">
                            <form class="form-inline" role="form" onSubmit="return false">
                                <div class="form-group">
                                    <input class="form-control input-sm" type="text" name="amount" value="{{ $val->in_cart }}" maxlength="4" /> <font class="text-muted">шт.</font>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-default btn-sm" data-loading-text="..." onClick="Cart.update(this, {{ $val->id }})"><i class="fa fa-refresh"></i></button>
                                </div>
                                <div class="form-group">
                                	 <button class="btn btn-default btn-sm" data-loading-text="..." onClick="Cart.destroy(this, {{ $val->id }})"><i class="fa fa-times"></i></button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
            <hr />
            <div class="row">
            	<div class="col-xs-12">
                	 <div class="pull-right">
                    	Кол-во товаров: <strong><font id="page_total_products" size="+1">{{ $total_products }}</font></strong> шт.<br>
                    	Общая цена: <strong><font id="page_total_sum" size="+1">{{ $total_sum }}</font></strong> <i class="fa fa-rub"></i>
                    </div>
                </div>
            </div>
            <br />
            @if(!empty(Session::get('aSettings.cart_min_treshhold')) && 
            	$total_sum_src < Session::get('aSettings.cart_min_treshhold') )
                 <div class="row">
                    <div class="col-xs-12 col-md-6 col-md-offset-6">
                        <p class="alert alert-warning"><strong>Внимание:</strong> минимальная цена оформления заказа составляет {{ Session::get('aSettings.cart_min_treshhold') }} <i class="fa fa-rub"></i>.<br />Вам необходимо увеличить кол-во товаров.</p>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-xs-6">
                    <a href="javascript: history.back()" class="btn btn-default">Назад</a>
                </div>
                <div align="right" class="col-xs-6">
                	@if(!empty(Session::get('aSettings.cart_min_treshhold')) && 
            			$total_sum_src < Session::get('aSettings.cart_min_treshhold') )
                		<a href="/order" class="btn btn-primary disabled">Оформить</a>
                	@else
                    	<a href="/order" class="btn btn-primary">Оформить</a>
                	@endif
                </div>
            </div>
        </div>
    @endif
    
    <!-- блок вкл/выкл. так же и через яваскрипт -->
    <table id="cart_empty_msg" class="{{ $aProducts->count()? 'hide': '' }}" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td align="center" valign="middle" height="400">
            	<font class="text-muted">корзина пуста</font>
            </td>
        </tr>
    </table>
@stop