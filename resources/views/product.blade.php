@extends('layouts.main')

@section('content')
	<script>
    	var csrf_token = "{{ csrf_token() }}";
    </script>
	<h1>{{ $data->name }} {!! !empty($data->sku)? '<small>('.$data->sku.')</small>': '' !!}</h1>
    <div class="row">
    	<dic class="col-xs-12 col-sm-6 col-md-4">
        	<div id="product_images">
            	 <div class="product_images_main_wraper">
                    @if(!empty($data->discount))
                        @include('modules.discount_emblem', ['value' => $data->discount])
                    @endif
                    
                    @if(!empty($data->is_absent) || empty($data->price))
                        @include('modules.absent_emblem')
                    @endif
                	
                    @if($data->images->count())
                    	<a class="fancybox" rel="gallery1" href="{{ $data->images->first()->path }}">
                    		<img height="270" src="/imager{{ $data->images->first()->path }}/0/270" />
                       </a> 
                   @elseif(!empty($data->spare_photo))
            			<img height="270" src="/imager{{ $data->spare_photo }}/0/270" />
                   @else
                   		<img height="270" src="/img/no_image_300.jpg" />
                   @endif
                </div>
                
                @if($data->images->count() > 1)
                    @foreach($data->images as $key => $val)
                        @if($key)
                           <a class="fancybox product_images_thumbnail" rel="gallery1" href="{{ $val->path }}">
                                <img width="67" height="50" src="/imager{{ $val->path }}/67/50" /> 
                           </a>
                       @endif
                   @endforeach
                   @for($i = 0, $j = $data->images->count(); $i <= (5 - $j); $i++)
                        <img class="product_images_thumbnail" width="67" height="50" src="/img/no_image_100.jpg" />
                   @endfor
                @else
                    @for($i=0; $i<5; $i++)
                        <img class="product_images_thumbnail" width="67" height="50" src="/img/no_image_100.jpg" />
                   @endfor
                @endif
            </div>
        </dic>
        <dic class="col-xs-12 col-sm-6 col-md-4">
        	<p class="lead">Аттрибуты</p>
            <table class="table table-striped table-condensed">
            	@foreach($data->inc_attributes as $val)
            		<tr>
                    	<td>{{ $val->name }}</td>
                       <td>{{ $val->value }}</td>
                    </tr>
            	@endforeach
            </table>
            <br />
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr>
                   <td>
                        <select id="product_rating">
                            <option value="1"></option>
                            <option value="2"></option>
                            <option value="3"></option>
                            <option value="4"></option>
                            <option value="5"></option>
                        </select>
                   </td>
                   <td>
                        @include('modules.product_price', ['price' => $data->price, 'discount' => $data->discount])
                   </td>
                </tr>
            </table>
            <br />
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr>
                	<td valign="top">
                    	<button class="btn btn-block btn-success" data-loading-text="Загрузка..." 
                        		{{ (!empty($data->is_absent) || empty($data->price))? 'disabled=disabled': '' }}
            					onClick="Cart.add_via_card_product(this, {{ $data->id }})" >В корзину</button>
                    </td>
                    <td width="20"></td>
                    <td align="right" valign="top" width="70">
                    	<div class="input-plus-minus">
                            <i class="fa fa-plus-square-o" onClick="input_plus_minus(this, 1)"></i>
                            <i class="fa fa-minus-square-o" onClick="input_plus_minus(this, 0)"></i>
                            <input class="form-control" type="text" name="product_amount" value="1" maxlength="3" onChange="input_plus_minus(this, -1)" />
                        </div>
                    </td>
                </tr>
            </table> 
        </dic>
        @if(!empty($data->desc_full))
            <dic class="col-xs-12 col-sm-12 col-md-4">
                <p class="lead">Описание</p>
                {!! $data->desc_full !!}
            </dic>
        @endif
    </div>
    
    @if(!empty($aDopProducts) && $aDopProducts->count())
        <div class="row">
            <dic class="col-xs-12">
                <br />
                <p class="lead">Похожие товары:</p>
                @foreach($aDopProducts as $key => $val)
                    @include('modules.product_item_dop', ['data' => $val])
                @endforeach
            </dic>
        </div>
    @endif
    
    @if(!empty($data->already_installed_rating))
		<script>
            $(function(){
				$('#product_rating').barrating({
					theme: 'fontawesome-stars',
					readonly: true,
					initialRating: {{ empty($data->rating)? -1: $data->rating }}
				});
            });	
        </script>
    @else
    	<script>
			$(function(){
				var $obj = $('#product_rating');
				$obj.barrating({
					theme: 'fontawesome-stars',
					initialRating: {{ empty($data->rating)? -1: $data->rating }},
					onSelect: function(value, text, event){
						$obj.barrating('readonly', true);
						$.post('/set_rating/'+{{ $data->id }}+'/'+value, {_token: window.csrf_token}, function(response){
							if(!response.result){
								alert(response.msg);	
							}
						}, 'json');
					}
				});	
			});	
        </script>
    @endif
@stop