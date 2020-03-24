@extends('layouts.main')

@section('content')
    @if(!empty($description))
        <div class="row">
            <div class="col-xs-12">
                {!! $description !!}
            </div>
        </div>
    @endif
    
    @if(!empty($data_is_sent))
    	<table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" valign="middle" height="400">
                	<table cellpadding="0" cellspacing="0" width="500">
                    	<tr>
                       		<td align="left" valign="top">
                            	<h3><i class="fa fa-check-circle text-success"></i> Заказ оформлен</h3>
                               <p>На вашу почту (<em>{{ $aUserData['email'] }}</em>) отправеленно письмо с данными о вашем заказе.<br>В ближайшее время наш менеджер с вами свяжется, ожидайте.
                               </p>
                               <p class="lead">Спасибо что Вы с нами.</p>
                           </td>
                       </tr>
                    </table>
                </td>
            </tr>
        </table>
    @else
    	<div class="row">
        	<div class="col-xs-offset-3 col-xs-6">
                <p class="lead">Оформление заказа</p>
                
                @include('modules.alert_errors')
                
                <form action="" method="post" >
                    <div class="form-group">
                        <label>Ваш е-мэйл <span class="text-danger">*</span></label>
                        <input type="text" name="email" class="form-control" value="{{ !empty($aUserData['email'])? $aUserData['email']: old('email') }}" placeholder="почтовый электронный адрес">
                    </div>
                    <div class="form-group">
                        <label>Ваш номер телефона <span class="text-danger">*</span></label>
                        <input type="text" name="tel" class="form-control" value="{{ !empty($aUserData['tel'])? $aUserData['tel']: old('tel') }}" placeholder="куда позвонить">
                    </div>
                    <div class="form-group">
                        <label>Ваше имя</label>
                        <input type="text" name="name" class="form-control" value="{{ !empty($aUserData['name'])? $aUserData['name']: old('name') }}" placeholder="как к Вам обращаться">
                    </div>
                    <div class="form-group">
                        <label>Ваш комментарий к заказу</label>
                        <textarea class="form-control" name="comment" placeholder="дополнительные сведения">{{ !empty($aUserData['comment'])? $aUserData['comment']: old('comment') }}</textarea>
                    </div>
                    <div class="form-group">
                        <span class="text-danger">*</span> <font class="text-muted">поля обязательны для заполнения</font>
                    </div>
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-success">Отправить</button>
                </form>
            </div>
        </div>
    @endif
@stop