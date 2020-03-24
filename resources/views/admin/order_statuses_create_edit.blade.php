@extends('layouts.admin')

@section('content')
	<div class="row">
        <div class="col-xs-12">
            @include('modules.alert_errors')
            
            <form 	action="/admin/order_statuses{!! !empty($data->id)? '/'.$data->id: '' !!}" 
                   class="form-horizontal" 
                   method="post" >
                
                @if(!empty($data->id))
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                @endif
                
                <div class="form-group">
                    <label class="col-xs-2 control-label">Название</label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="name" maxlength="50"
                               value="{{ !empty($data->name)? $data->name: old('name') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-offset-2 col-xs-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" 
                                		name="is_default"
                                       {{ !empty($data->is_default)? 'checked': '' }} /> использовать по умолчанию
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-offset-2 col-xs-10">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success">Отправить</button>
                    </div>
                </div>
            </form>  
        </div>
    </div>
@stop