@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            @if($orders->count())
                <table class="table table-striped table-condensed">
                    @foreach($orders as $val)
                        <tr>
                           <td align="left" width="200">
                           		Заказ #{{ $val->id }}
                               @if(!empty($val->status_text))
                               		<small class="text-muted">({{ $val->status_text }})</small>
                               @endif
                           </td>
                           <td align="left">
                           		{{ $val->email }}
                           </td>
                           <td align="left" width="200">
                           		<font class="text-muted">тел.</font> {{ $val->tel }}
                           </td>
                           <td align="right" width="120">
                           		{!! html_price($val->total_sum) !!} руб.
                           </td>
                           <td align="right" width="80">
                           		{{ $val->total_products }} шт.
                           </td>
                           <td align="right" width="70">
                               <a href="/admin/orders/{{ $val->id }}/edit"><i class="fa fa-edit"></i></a>
                               &nbsp;&nbsp;&nbsp;&nbsp;
                               <a href="javascript: void(0)" 
                                  onClick="confirm('Точно удалить?')? $(this).find('form').submit(): void(0)"><i class="fa fa-remove"></i>
                               <form action="/admin/orders/{{ $val->id }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                               </form>
                           </a></td>
                        </tr>
                    @endforeach
                </table>
            @else
            	@include('modules.empty_content', ['text'=>'заказов нет'])
            @endif
        </div>
    </div>  
@stop