<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" 	content="IE=edge">
    <meta name="viewport" 				content="width=device-width, initial-scale=1">
    <meta name="keywords" 				content="{{ $meta_keywords or '' }}">
    <meta name="description" 			content="{{ $meta_desc or '' }}">
    <meta name="yandex-verification" 	content="e730ea18b0589bbc" />
    
    <link type="image/x-icon" 	rel="icon" 			href="/img/favicon.png" />
    
    <link type="text/css"		rel="stylesheet" 	href="/vendor/bootstrap/css/bootstrap.min.css" />
    <link type="text/css"		rel="stylesheet" 	href="/vendor/font-awesome-4.6.3/css/font-awesome.min.css" />
    <link type="text/css"		rel="stylesheet" 	href="/vendor/slick-1.6.0/slick/slick.css" />
    <!--<link type="text/css"		rel="stylesheet" 	href="/vendor/slick-1.6.0/slick/slick-theme.css" />-->
    <link type="text/css"		rel="stylesheet" 	href="/vendor/jquery-bar-rating/dist/themes/fontawesome-stars.css" />
    <link type="text/css"		rel="stylesheet" 	href="/vendor/jquery-bar-rating/dist/themes/fontawesome-stars-o.css" />
    <link type="text/css"		rel="stylesheet" 	href="/vendor/fancyBox/source/jquery.fancybox.css" />
    <link type="text/css"		rel="stylesheet" 	href="/css/main.css" />
    
    <script type="text/javascript" src="/vendor/jquery/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/vendor/holder/holder.min.js"></script>
    <script type="text/javascript" src="/vendor/slick-1.6.0/slick/slick.min.js"></script>
    <script type="text/javascript" src="/vendor/jquery-bar-rating/dist/jquery.barrating.min.js"></script>
    
	<script type="text/javascript" src="/vendor/fancyBox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
    <script type="text/javascript" src="/vendor/fancyBox/source/jquery.fancybox.pack.js"></script>
    
    <script type="text/javascript" src="/js/main.js"></script>
    
    <title>{{ $title or '' }}</title>
</head>
<body>
	<div id="header_topmenu_content_and_etc">
        <!-- шапка -->
        <div id="header" class="container">
        	<div class="row">
            	<div class="col-xs-12 col-md-6">
                	<div class="row">
                        <div class="col-xs-4">	
                            @if(!empty(Session::get('aSettings.logo')))
                                <a id="logo" href="/"><img src="{{ Session::get('aSettings.logo') }}" /></a>
                            @endif
                        </div>
                        <div class="col-xs-8">	
                            @if(!empty(Session::get('aSettings.phones')))
                            	<i class="fa fa-phone-square fa-2x text-orange"></i>&nbsp;
                            	<strong><font size="+1">{{ implode(', ', Session::get('aSettings.phones')) }}</font></strong>                            @endif
                            <div class="text-muted small">
                               Работаем с 8:00 по 19:00 по Мск, без выходных.
                               @if(!empty(Session::get('aSettings.email')))
                               		<br />
                               		<a href="mailto:{{ Session::get('aSettings.email') }}">{{ Session::get('aSettings.email') }}</a>
                               @endif
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-6">	
                            <form class="form" action="/search" method="get">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="q" 
                                           value="{{ !empty($search_query)? $search_query: '' }}" placeholder="быстрый поиск" >
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <div class="col-xs-6">	
                        	 <table align="right" border="0" cellpadding="0" cellspacing="0">
                            	<tr>
                               		<td>
                                    	@if( Request::url() != "cart" && 
                                        	 Request::url() != "order" &&
                                            Session::has('cart') && 
                                            is_array(Session::get('cart')) &&
                                            sizeof(Session::get('cart')))
                                            <a class="btn btn-success pull-left" href="/order">Оформить</a>
                                       @endif
                                       <a id="cart" href="/cart"><i class="fa fa-shopping-cart fa-2x"></i><strong><font size="+1"> Корзина</font></strong></a>
                                   </td> 
                               </tr>
                               <tr>
                               		<td align="right">
                                    	<strong id="cart_total_products">{{ $total_products or 0 }}</strong> 
                                       
                                       @if(!empty($total_products))
                                       	@if($total_products == 1)
                                        		товар
                                        	@elseif($total_products > 1 && $total_products < 5)
                                            	товара
                                           @else
                                           	товаров
                                        	@endif
                                       @else
                                       	товаров
                                       @endif
                                      	| 
                            			<strong id="cart_total_sum">{{ $total_sum or 0 }}</strong> <i class="fa fa-rub"></i>
                                   </td> 
                               </tr>
                            </table>
                        </div>
                    </div>
                </div>         
            </div>
            @if(isset($aTopMenuPages) && $aTopMenuPages->count())
                <div class="row">
                    <div class="col-xs-9 text-right">
                        <ul class="list-inline">
                        	@foreach($aTopMenuPages as $val)
                            	 <li {!! !empty($val->active)? 'class="active"': '' !!} >
                                    <a href="/{{ $val->slug }}">
                                        @if(!empty($val->fa))
                                            <i class="fa fa-{{ $val->fa }}"></i>
                                        @endif
                                        
                                        {{ $val->title }}
                                    </a>
                                </li>
                           @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div><!-- \шапка -->
        
        @if(!empty($aCategoryTopMenu))
            <div id="top_menu">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                	 <?php
											$len = $aCategoryTopMenu->count();
									 ?>
                                     @foreach($aCategoryTopMenu as $key => $val)
                                        <td>
                                            <div class="top_menu_level_1">
                                                <!-- class="btn btn-block" -->
                                                <a href="/catalog/{{ $val->slug }}" >{{ $val->name }}</a>
                                                
                                                @if(!empty($val->childs))
                                                    <div class="top_menu_level_2 {{ $key == ($len-1)? 'to-right': '' }}">
                                                        <div>
                                                           <table border="0" cellpadding="0" cellspacing="5">
                                                                <tr>
                                                                   @if(!empty($val->img_path))
                                                                       <td align="left" valign="middle">
                                                                            <img width="100" class="top_menu_cover" 
                                                                            	   src="/imager{{$val->img_path}}/100/0" />
                                                                       </td>
                                                                   @endif
                                                                   <td align="left" valign="top">
                                                                    	<ul class="list-unstyled">
                                                                          @foreach($val->childs as $val2)
                                                                            <li><a class="text_eclipse" href="/catalog/{{ $val->slug }}/{{ $val2->slug }}" title="{{ $val2->name }}">{{ $val2->name }}</a></li>
                                                                          @endforeach
                                                                       </ul>
                                                                   </td> 
                                                                </tr> 
                                                           </table>
                                                       </div>
                                                   </div>
                                                @endif
                                           </div>
                                        </td> 
                                    @endforeach
                               </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <div id="content">
            <div class="container">
                <div class="row">
                    @if(!empty($aCategories))
                        <div class="col-xs-3">
                            @if(!empty($aCategorySelectedIds))
                                @include('modules.sidebar', ['data' => $aCategories, 'selected_ids' => $aCategorySelectedIds])
                            @else
                                @include('modules.sidebar', ['data' => $aCategories, 'selected_ids' => []])
                            @endif
                        </div>
                        <div class="col-xs-9">
                            @if(!empty($aBreadcrumbs))
                                @include('modules.breadcrumbs', ['data' => $aBreadcrumbs])
                            @endif
                           
                            @yield('content')
                        </div>
                    @else
                        <div class="col-xs-12">
                            @if(!empty($aBreadcrumbs))
                            	 @include('modules.breadcrumbs', ['data' => $aBreadcrumbs])
                            @endif
                            
                            @yield('content')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                	@if(!empty(Session::get('aSettings.dop_info_for_footer')))
                        <div class="footer_cell">
                            <div class="footer_title">Доп-ая информация:</div>
                           {!! Session::get('aSettings.dop_info_for_footer') !!}
                        </div>
                   @endif
                </div>
                <div class="col-sm-3">
                    @if($aFooterLinks->count())
                    	 <div class="footer_cell">
                            <div class="footer_title">Полезные ссылки:</div>
                            <ul class="list-unstyled">
                                @foreach($aFooterLinks as $val)
                                    <li><a class="a_gray" href="/{{ $val->slug }}">{{ $val->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="col-sm-3">
                    <div class="footer_cell">
                        <div class="footer_title">Наши партнеры:</div>
                        <div id="ours_partners">
                        	 <div><img height="70" src="/{{ DIR_IMAGES }}/partner_1.jpg" /></div>
                            <div><img height="70" src="/{{ DIR_IMAGES }}/partner_2.jpg" /></div>
                            <div><img height="70" src="/{{ DIR_IMAGES }}/partner_3.jpg" /></div>
                            <div><img height="70" src="/{{ DIR_IMAGES }}/partner_4.jpg" /></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                	<div id="subscribe" class="input-group">
                        <input class="form-control" disabled type="text" value="" placeholder="подписаться на новости" >
                        <span class="input-group-btn">
                            <button class="btn btn-default disabled"><i class="fa fa-envelope-o"></i></button>
                        </span>
                    </div>
                    <br />
                    @if(!empty(Session::get('aSettings.soc_link')) && !empty(Session::get('aSettings.soc_html')))
                        Мы в соц. сетях:
                        <ul class="list-inline">
                        	@foreach(Session::get('aSettings.soc_link') as $key => $val)
                            	@if(!empty(Session::get('aSettings.soc_link.'.$key)) &&
                                   !empty(Session::get('aSettings.soc_html.'.$key)))
                                	<li><a class="a_soc" 
                                    	   href="{{ Session::get('aSettings.soc_link.'.$key) }}" 
                                          target="_blank" 
                                          rel="nofollow">{!! Session::get('aSettings.soc_html.'.$key) !!}</a></li>
                               @endif
                           @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12 text-center">
                	<font class="text-muted small">Все права защищены. {{ parse_url(Config::get("app.url"))['host'] }} @ {{ date('Y') }}</font>
                </div>
            </div>
        </div>
    </div>
    {!! Session::get('aSettings.counters') !!}
</body>
</html>