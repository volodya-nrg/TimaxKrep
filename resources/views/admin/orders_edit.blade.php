@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
        	@include('modules.alert_errors')
            
            <form action="/admin/orders/{{ $data->id }}" class="form-horizontal" method="post" >
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" value="{{ $data->id }}" />
                
                <div class="form-group">
                    <div class="col-xs-offset-2 col-xs-10">
                        <h4>Заказ #{{ $data->id }} <small class="pull-right">дата создания {{ $data->created_at }}</small></h4>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Е-мэйл <span class="text-danger">*</span></label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="email"
                               value="{{ !empty($data->email)? $data->email: old('email') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Телефон <span class="text-danger">*</span></label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="tel" 
                               value="{{ !empty($data->tel)? $data->tel: old('tel') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Имя</label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="name" 
                               value="{{ !empty($data->name)? $data->name: old('name') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Комментарий</label>
                    <div class="col-xs-10">
                        <textarea class="form-control input-sm" rows="3" name="comment">{{ !empty($data->comment)? $data->comment: old('comment') }}</textarea>
                    </div>
                </div>
                
                @if(isset($colOrderStatuses) && $colOrderStatuses->count())
                    <div class="form-group">
                        <label class="col-xs-2 control-label">Статус</label>
                        <div class="col-xs-10">
                            <select class="form-control input-sm" name="status_id">
                             	   <option value="0"></option>
                                  @foreach($colOrderStatuses as $val)
                                  		<option value="{{ $val->id }}" 
                                        	{{ $data->status_id == $val->id? 'selected': '' }} >{{ $val->name }}</option>  
                                  @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                
                <div class="form-group">
                    <div class="col-xs-offset-2 col-xs-10">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success">Отправить</button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Продукты</label>
                    <div class="col-xs-10">
                    	 <table class="table table-striped table-condensed">
                            @foreach(unserialize($data->products) as $val)
                                <tr>
                                    <td align="center" width="65">
                                    	{{-- тут не нужно проверять на $val->spare_photo --}}
                                    	@if($val->images->count() && is_file(public_path().$val->images->first()))
                                        	<img height="40" src="{{ $val->images->first() }}/0/40" />
                                       @else
                                       	<img height="40" src="/img/no_image_100.jpg" />
                                       @endif
                                   </td>
                                   <td>
                                        <a href="/{{ $val->slug }}" target="_blank">{{ $val->name }}</a> 
                                        <small class="text-muted">{{ $val->sku }}</small>
                                        <br />
                                        <font class="text-muted">{{ implode(" / ", $val->category_names) }}</font>
                                   </td>
                                   <td align="right" width="70">
                                        {{ $val->in_cart }} шт.
                                   </td>
                                   <td align="right" width="100">
                                        {!! html_price($val->price) !!} <i class="fa fa-rub"></i> 
                                        <br />
                                        за единицу
                                   </td>
                               </tr>
                            @endforeach
                            <tfoot>
                            	<tr>
                                	<td align="right" colspan="4">
                                    	<br />
                                    	Общая цена: <strong>{!! html_price($data->total_sum) !!}</strong> <i class="fa fa-rub"></i>
                                       <br />
                               			Кол-во продуктов: <strong>{{ $data->total_products }}</strong> шт.
                                   </td> 
                               </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </form>  
        </div>
    </div>
@stop