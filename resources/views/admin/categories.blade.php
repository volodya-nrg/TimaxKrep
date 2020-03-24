@extends('layouts.admin')

@section('content')
	<div class="row">
        <div class="col-xs-12">
            <a class="btn btn-success btn-sm" 
               href="/admin/categories/create" 
               title="добавить новую категорию"><i class="fa fa-plus"></i></a>
            
            <br /><br />
            
            @if(!empty($aCategories))
                {!! show_categories_as_tree($aCategories, "list_tree", 1, [], 1) !!} 
                <ul class="text-muted">
                    <li>на половину прозначные категории - скрытые категории</li>
                </ul>              
            @else
            	@include('modules.empty_content', ['text' => 'нет категорий'])
            @endif
        </div>
    </div>
@stop