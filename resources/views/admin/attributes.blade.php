@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <a class="btn btn-success btn-sm" 
               href="/admin/attributes/create" 
               title="добавить новый атрибут"><i class="fa fa-plus"></i></a>
            
            <br /><br />
            
            @if(!empty($aAttributes))
                <table class="table table-striped table-condensed">
                    @foreach($aAttributes as $val)
                        <tr>
                           <td class="hide" width="50">Id {{ $val->id }}</td>
                           <td>{{ $val->name }}</td>
                           <td width="70">
                                <a href="/admin/attributes/{{ $val->id }}/edit"><i class="fa fa-edit"></i></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="javascript: void(0)" 
                                    onClick="confirm('Точно удалить?')? $(this).find('form').submit(): void(0)"><i class="fa fa-remove"></i>
                                <form action="/admin/attributes/{{ $val->id }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>
                           </a></td>
                        </tr>
                    @endforeach
                </table>
            @else
                @include('modules.empty_content', ['text' => 'атрибутов нет'])
            @endif
        </div>
    </div>
@stop