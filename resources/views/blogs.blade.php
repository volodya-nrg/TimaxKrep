@extends('layouts.main')

@section('content')
	{{-- если это у нас данные о блоге --}}
	@if(!empty($data->id))
    	<h1>{{ $data->title }}</h1>
        <div align="right">
        	<font class="text-muted">создан: {{ $created }}</font>
        </div>
        <br />
    	{!! $data->description !!}
    @else
		@if(sizeof($paginator))
        	@if(!empty($description) && $page == 1)
            	<div class="row">
                    <div class="col-xs-12">
                       {!! $description !!}
                    </div>	
                </div>
            @endif
            
            <div class="row">
                <div class="col-xs-12 text-right">
                   {{ $paginator->render() }}
                </div>	
            </div>
            
            <br />
            @foreach($paginator->items() as $val)
            	@include('modules.blogs_item', ['data' => $val])
            @endforeach
            <br />
            
            <div class="row">
                <div class="col-xs-12 text-right">
                    {{ $paginator->render() }}
                </div>
            </div>
        @else
        	@include('modules.empty_content', ['text'=>'блогов нет'])
 		@endif
	@endif
@stop