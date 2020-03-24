@extends('layouts.admin')

@section('content')
	<div class="row">
        <div class="col-xs-12">
            <a class="btn btn-success input-sm" 
               href="/admin/products/create" 
               title="добавить новый товар"><i class="fa fa-plus"></i></a>
            
            <br /><br />
            
            @if(!empty($aProducts))
                <table class="table table-striped table-condensed">
                    @foreach($aProducts as $val)
                        <tr class="{{ !$colCategoryIds->contains($val->category_id)? 'text-danger': '' }} 
                        			{{ $val->is_hide? 'opacity-half': '' }}">
                           <td width="60">Id {{ $val->id }}</td>
                           <td>{{ $val->name }} <font class="text-muted">{{ $val->sku }}</font></td>
                           <td width="70">
                               <a href="/admin/products/{{ $val->id }}/edit"><i class="fa fa-edit"></i></a>
                               &nbsp;&nbsp;&nbsp;&nbsp;
                               <a  href="javascript: void(0)" 
                                    onClick="confirm('Точно удалить?')? $(this).find('form').submit(): ''"><i class="fa fa-remove"></i>
                                    <form action="/admin/products/{{ $val->id }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                           		</a>
                           </td>
                        </tr>
                    @endforeach
                </table>
                <ul class="text-muted">
                    <li>продукты помеченные красным цветом, это те продукты, каторые не определены ни в какой категории</li>
                    <li>на половину прозначные продукты - скрытые продукты</li>
                </ul>
            @else
            	@include('modules.empty_content', ['text'=>'продуктов нет'])
            @endif
        </div>
    </div>
@stop