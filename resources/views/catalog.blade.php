@extends('layouts.main')

@section('content')
	@if(!empty($description) || !empty($img_path))
        <div class="row">
            <div class="col-xs-12">
                @if(!empty($img_path) && is_file(public_path().$img_path) && !empty($description))
                    <table width="100%">
                        <tr>
                            <td align="left" valign="top" width="100">
                                <img height="100" src="/imager{{ $img_path }}/0/100" />
                           </td>
                           <td width="20"></td>
                           <td align="left" valign="top">
                                {!! $description !!}
                           </td>  
                       </tr>
                   </table>
                @elseif(!empty($img_path) && is_file(public_path().$img_path))
                    <img height="100" src="/imager{{ $img_path }}/0/100" /> 
                @elseif(!empty($description))
                    {!! $description !!}
                @endif
            </div>
        </div>
        <br />
    @endif
    
    {{-- тут покажем категории --}}
    @if($aCategoryItems->count())
        <div class="row">
        	<div class="col-xs-12">
                @if(!empty($paginator) && sizeof($paginator) && $paginator->total())
                    <p class="lead">Категории:</p>
                @endif
                
                @foreach($aCategoryItems as $val)
                    @include('modules.catalog_item', ['data' => $val])
                @endforeach
            </div>
        </div>
    @endif
    
    {{-- тут покажем продукты --}}
    @if(sizeof($paginator))
    	<script>
			var csrf_token = "{{ csrf_token() }}";
		</script>
        
        @if(!empty($show_as_product))
            <table border="0" cellpadding="0" cellspacing="0" class="table-cat-as-product" width="100%">
            	<thead>
                	<tr>
                        <th rowspan="2">Артикул</th>
                        <th colspan="{{ sizeof($aAttributes) }}">Характеристики</th>
                        <th rowspan="2" width="120">Цена</th>
                        <th rowspan="2" width="90"></th>
                        <th rowspan="2" width="100"></th>
                    </tr>
                    <tr>
                        @foreach($aAttributes as $val)
                            <th align="center" valign="middle">{{ $val }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                	@foreach($paginator->items() as $val1)
                        <tr>
                        	 <td align="center" align="left" valign="middle">
                             	<a href="/{{ $val1->slug }}" target="_blank">{{ $val1->sku }}</a>
                            </td>
                            
                            @foreach($aAttributes as $val2)
                            	<?php $is_founded = false; ?>
                               
                               @foreach($val1->inc_attributes as $val3)
                                    @if($val3->name == $val2)
                                    	<td>{{ $val3->value }}</td>
                                       <?php
									   	$is_founded = true;
										   break;
									   ?> 
                                    @endif
                               @endforeach
                               
                               @if(!$is_founded)
                               		<td></td>
                               @endif
                            @endforeach
                            
                            <td>
                            	@if(!empty($val1->discount))
                                    <font class="text-muted price_old">&nbsp;{{ html_price($val1->price) }}&nbsp;</font>
                                    <strong class="text-danger price_new">{{ calc_price_with_discount($val1->price, $val1->discount) }}</strong> 
                                @else
                                    <strong>{!! html_price($val1->price) !!}</strong>  
                                @endif 
    
    							<i class="fa fa-rub"></i> 
                            </td>
                            <td>
                            	@include('modules.input_plus_minus', ['amount' => 1, 'is_small' => 1])
                            </td> 
                            <td>
                               <button class="btn btn-success btn-sm" 
                                     {{ !empty($val1->is_absent)? 'disabled=disabled': '' }} 
                                     data-loading-text="Загрузка..." 
                                     onClick="Cart.add_via_thumbnail(this, {{ $val1->id }})" >В корзину</button> 
                            </td> 
                        </tr>
                   @endforeach
                   @if($paginator->lastPage() > 1)
                       <tfoot>
                            <tr>
                                <td colspan="{{ sizeof($aAttributes)+4 }}">
                                    <center>
                                        {{ $paginator->render() }} 
                                   </center>
                               </td> 
                           </tr>
                       </tfoot>
                   @endif
                </tbody>
            </table>
        @else
            <div class="row">
                <div class="col-xs-12">
                    @if($aCategoryItems->count())
                        <br />
                        <p class="lead">Товары:</p>
                    @endif
                    
                    @foreach($paginator->items() as $val)
                        @include('modules.product_item', ['data' => $val])
                    @endforeach
                </div>    
            </div>
            <div class="row">
                <div class="col-xs-12 text-right">
                    {{ $paginator->render() }}
                </div>
            </div>
        @endif
    @endif    
    
    @if(!$aCategoryItems->count() && !sizeof($paginator))
    	@include('modules.empty_content')
    @endif
@stop