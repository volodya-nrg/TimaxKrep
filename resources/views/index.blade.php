@extends('layouts.main')

@section('content')
	<script>
    	var csrf_token = "{{ csrf_token() }}";
    </script>
    
    <div class="row">
    	<div class="col-md-3 hidden-sm">
            <div style="position:relative">
            	<div id="info_left_on_banner">
                	К Вашему вниманию представлен большой выбор уголков, опор и др. крепежей. Желаем приятных покупок.
                </div>
            	<img height="300" src="/img/construction-worker.jpg" />	
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9">
            <div id="banners_on_index">
                <div class="banner_item" style="background-image:url(/images/banner_1.jpg);"></div>
                <div class="banner_item" style="background-image:url(/images/banner_2.jpg);"></div>
                <div class="banner_item" style="background-image:url(/images/banner_3.jpg);"></div>
                <div class="banner_item" style="background-image:url(/images/banner_4.jpg);"></div>
            </div>
        </div>
    </div>
	
     @if(!empty($description))
        <div class="row">
            <div class="col-xs-12">
            	{!! $description !!}
            </div>
        </div>
    @endif
    
    @if(!empty($aProductBestsellers))
        <p class="lead">Хиты продаж</p>
        <div class="row">
            @foreach($aProductBestsellers as $val)
                @include('modules.product_item', ['data' => $val])
            @endforeach
        </div>
    @endif
    
    @if(!empty($aProductLasted))
        <p class="lead">Новинки</p>
        <div class="row">
            @foreach($aProductLasted as $val)
                @include('modules.product_item', ['data' => $val])
            @endforeach
        </div>
    @endif
@stop