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
    <script type="text/javascript" src="/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/main.js"></script>
    
    <title>Вход</title>
</head>

<body>
	<div class="container">
    	<div class="row">
        	<div class="col-xs-offset-4 col-xs-4">
            	<br><br><br><br>
                <div class="box-white">
                    @include('modules.alert_errors')
                    
                    <form action="" class="form-horizontal" method="post">
                        <div class="form-group">
                            <label class="col-xs-2 control-label">Логин</label>
                            <div class="col-xs-10">
                                <input type="text" name="login" class="form-control" value="{{ old('login') }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-2 control-label">Пароль</label>
                            <div class="col-xs-10">
                                <input type="password" name="pass" class="form-control" value="{{ old('pass') }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-offset-2 col-xs-10">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary">Войти</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>