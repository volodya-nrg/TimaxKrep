@extends('layouts.main')

@section('content')
	@if($is_searched)
    	<script>
        	var csrf_token = "{{ csrf_token() }}";
        </script>
        
        <div class="row">
        	<div class="col-xs-6">
                <p class="lead">
                    Найдено товаров:  
                    <strong><font size="+1">{{ sizeof($paginator)? $paginator->total(): 0 }}</font></strong> шт.
                </p>
            </div>
            <div class="col-xs-6 text-right">
            	@if(sizeof($paginator))
                	{{ $paginator->render() }}
               @endif
            </div>	
        </div>
        
        @if(sizeof($paginator))
            <div class="row">
                @foreach($paginator->items() as $val)
                    @include('modules.product_item', ['data' => $val])
                @endforeach
            </div>
            <div class="row">
                <div class="col-xs-12 text-right">
                    {{ $paginator->render() }}
                </div>
            </div>
        @else
        	<table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" valign="middle" height="400">
                        <font class="text-muted">ни чего не найдено</font>
                    </td>
                </tr>
            </table>
        @endif 
    @else
    	<table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" valign="middle" height="400">
                    <font class="text-muted">для поиска введите нужную фразу в текстовое поле выше</font>
                </td>
            </tr>
        </table>
    @endif
@stop