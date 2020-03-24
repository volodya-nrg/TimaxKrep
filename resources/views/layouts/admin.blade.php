<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
    
    <link type="image/x-icon" 	rel="icon" 			href="/img/favicon.png" />
    <link type="text/css"		rel="stylesheet" 	href="/vendor/bootstrap/css/bootstrap.min.css" />
    <link type="text/css"		rel="stylesheet" 	href="/vendor/font-awesome-4.6.3/css/font-awesome.min.css" />
    <link type="text/css"		rel="stylesheet" 	href="/css/main.css" />
    <link type="text/css"		rel="stylesheet" 	href="/css/admin.css" />
    
    <script type="text/javascript" src="/vendor/jquery/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="/vendor/backbone.marionette/underscore.js"></script>
    <script type="text/javascript" src="/vendor/backbone.marionette/backbone.js"></script>
    <script type="text/javascript" src="/vendor/backbone.marionette/backbone.marionette.min.js"></script>
    <script type="text/javascript" src="/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/vendor/holder/holder.min.js"></script>
    <script type="text/javascript" src="/vendor/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/js/main.js"></script>
    
    <title>Администрирование</title>
</head>

<body>
	<div class="container-fluid">
    	<div class="row">
            <div class="col-xs-3">
            	<h4 class="text-center text-white">
                	<span class="visible-xs">Админ-ие</span> 
                   <span class="hidden-xs">Администрирование</span>  
                   <small>v1.3</small>
                </h4>
                <br />
                <div class="list-group list-group-darkness">
                	 <a class="list-group-item" href="/">
                     	<span class="visible-xs text-center"><i class="fa fa-home fa-lg fa-fw"></i></span>
                     	<span class="hidden-xs">
                        	<i class="fa fa-home fa-lg fa-fw"></i> Главная страница
                        </span>
                    </a>
                     <a class="list-group-item {{ Request::is('admin')? 'active': '' }}" href="/admin">
                     	<span class="visible-xs text-center"><i class="fa fa-tachometer fa-lg fa-fw"></i></span>
                     	<span class="hidden-xs">
                        	<i class="fa fa-tachometer fa-lg fa-fw"></i> Основное
               			</span>
                    </a>
                    <a class="list-group-item {{ Request::is('admin/pages*')? 'active': '' }}" href="/admin/pages">
                    	<span class="visible-xs text-center"><i class="fa fa-file-text fa-lg fa-fw"></i></span>
                     	<span class="hidden-xs">
                    		<i class="fa fa-file-text fa-lg fa-fw"></i> Страницы
                       </span>
                    </a>
                    <a class="list-group-item {{ Request::is('admin/products*')? 'active': '' }}" href="/admin/products">
                    	<span class="visible-xs text-center"><i class="fa fa-th fa-lg fa-fw"></i></span>
                     	<span class="hidden-xs">
                    		<i class="fa fa-th fa-lg fa-fw"></i> Продукты
                       </span>
                    </a>
                    <a class="list-group-item {{ Request::is('admin/categories*')? 'active': '' }}" href="/admin/categories">
                    	<span class="visible-xs text-center"><i class="fa fa-indent fa-lg fa-fw"></i></span>
                     	<span class="hidden-xs">
                    		<i class="fa fa-indent fa-lg fa-fw"></i> Категории продуктов
                       </span>
                    </a>
                    <a class="list-group-item {{ Request::is('admin/attributes*')? 'active': '' }}" href="/admin/attributes">
                    	<span class="visible-xs text-center"><i class="fa fa-code fa-lg fa-fw"></i></span>
                     	<span class="hidden-xs">
                    		<i class="fa fa-code fa-lg fa-fw"></i> Атрибуты продуктов
                       </span>
                    </a>
                    <a class="list-group-item {{ Request::is('admin/orders*')? 'active': '' }}" href="/admin/orders">
                    	<span class="visible-xs text-center"><i class="fa fa-home fa-list-alt fa-fw"></i></span>
                     	<span class="hidden-xs">
                    		<i class="fa fa-home fa-list-alt fa-lg fa-fw"></i> Заказы
                       </span>
                    </a>
                    <a class="list-group-item {{ Request::is('admin/order_statuses*')? 'active': '' }}" href="/admin/order_statuses">
                    	<span class="visible-xs text-center"><i class="fa fa-home fa-tag fa-fw"></i></span>
                     	<span class="hidden-xs">
                    		<i class="fa fa-home fa-tag fa-lg fa-fw"></i> Статусы заказов
                       </span>
                    </a>
                    <a class="list-group-item {{ Request::is('admin/news*')? 'active': '' }}" href="/admin/news">
                    	<span class="visible-xs text-center"><i class="fa fa-newspaper-o fa-lg fa-fw"></i></span>
                     	<span class="hidden-xs">
                    		<i class="fa fa-newspaper-o fa-lg fa-fw"></i> Новости
                       </span>
                    </a>
                    <a class="list-group-item {{ Request::is('admin/blogs*')? 'active': '' }}" href="/admin/blogs">
                    	<span class="visible-xs text-center"><i class="fa fa-pencil-square fa-lg fa-fw"></i></span>
                     	<span class="hidden-xs">
                    		<i class="fa fa-pencil-square fa-lg fa-fw"></i> Блоги
                       </span>
                    </a>
                    <a class="hide list-group-item {{ Request::is('admin/modules*')? 'active': '' }}" href="/admin/modules">
                    	<span class="visible-xs text-center"><i class="fa fa-home fa-braille fa-fw"></i></span>
                     	<span class="hidden-xs">
                    		<i class="fa fa-home fa-braille fa-lg fa-fw"></i> Модули
                       </span>
                    </a>
                    <a class="list-group-item {{ Request::is('admin/etc')? 'active': '' }}" href="/admin/etc">
                    	<span class="visible-xs text-center"><i class="fa fa-ellipsis-h fa-lg fa-fw"></i></span>
                     	<span class="hidden-xs text-center">
                    		<i class="fa fa-ellipsis-h fa-lg fa-fw"></i> Разное
                       </span>
                    </a>
                    <a class="list-group-item" href="/logout">
                     	<span class="visible-xs text-center"><i class="fa fa-sign-out fa-lg fa-fw"></i></span>
                     	<span class="hidden-xs">
                        	<i class="fa fa-sign-out fa-lg fa-fw"></i> Выход
                        </span>
                    </a>
                </div>
            </div>
            <div id="admin_content_main" class="col-xs-9">
            	<br />
                @yield('content')
                <br />
            </div>
        </div>
    </div>
</body>
</html>