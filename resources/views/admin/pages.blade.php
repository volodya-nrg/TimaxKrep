@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <a  class="btn btn-success btn-sm" 
            	href="/admin/pages/create" 
                title="добавить новую страницу"><i class="fa fa-plus"></i></a>
            
            <br /><br />
            
            @if($aPages->count())
                <table class="table table-striped table-condensed">
                    @foreach($aPages as $val)
                        <tr class="{{ $val->is_hide? 'opacity-half': '' }}" >
                           <td class="hide" width="50">Id {{ $val->id }}</td>
                           <td>
                           		{{ $val->title }}
                               @if(in_array($val->slug, $aDisallowSlugs))
                                	<font class="text-muted">({{ $val->slug }})</font>
                               @endif
                           </td>
                           <td width="70">
                               <a href="/admin/pages/{{ $val->id }}/edit"><i class="fa fa-edit"></i></a>
                               @if(!in_array($val->slug, $aDisallowSlugs))
                                   &nbsp;&nbsp;&nbsp;&nbsp;
                                   <a  href="javascript: void(0)" 
                                        onClick="confirm('Точно удалить?')? $(this).find('form').submit(): ''"><i class="fa fa-remove"></i>
                                        <form action="/admin/pages/{{ $val->id }}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                        </form>
                                    </a>
                               @endif
                           </td>
                        </tr>
                    @endforeach
                </table>
                <ul class="text-muted">
                    <li>на половину прозначные страницы - скрытые страницы</li>
                </ul>
            @else
            	@include('modules.empty_content', ['text'=>'страниц нет'])
            @endif
        </div>
    </div>  
@stop