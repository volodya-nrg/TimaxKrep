@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <a  class="btn btn-success btn-sm" 
            	href="/admin/news/create" 
                title="добавить новость"><i class="fa fa-plus"></i></a>
            
            <br /><br />
            
            @if(isset($colNews) && $colNews->count())
                <table class="table table-striped table-condensed">
                    @foreach($colNews as $val)
                        <tr class="{{ $val->is_hide? 'opacity-half': '' }}" >
                           <td class="hide" width="50">Id {{ $val->id }}</td>
                           <td>
                           		{{ $val->title }}
                           </td>
                           <td width="70">
                               <a href="/admin/news/{{ $val->id }}/edit"><i class="fa fa-edit"></i></a>
                               &nbsp;&nbsp;&nbsp;&nbsp;
                               <a  href="javascript: void(0)" 
                                    onClick="confirm('Точно удалить?')? $(this).find('form').submit(): ''"><i class="fa fa-remove"></i>
                                    <form action="/admin/news/{{ $val->id }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                               </a>
                           </td>
                        </tr>
                    @endforeach
                </table>
                <ul class="text-muted">
                    <li>на половину прозначные новости - скрытые новости</li>
                </ul>
            @else
            	@include('modules.empty_content', ['text'=>'новостей нет'])
            @endif
        </div>
    </div>  
@stop