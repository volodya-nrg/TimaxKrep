@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
        	@if(!empty(Session::get('is_saved')))
            	@include('modules.alert_success', ['text'=>'информация обновлена'])
            @endif
            
            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" role="form">
                <div class="form-group">
                	<label class="col-xs-2 control-label">Режим работы</label>
                	<div class="col-xs-10">
                        <label class="radio-inline">
                        	<input type="radio" name="mode" 
                            	   {{ !empty(Session::get('aSettings.mode'))? 'checked': '' }} value="1" /> боевой
                        </label>
                        <label class="radio-inline">
                        	<input type="radio" name="mode" 
                            	   {{ !empty(Session::get('aSettings.mode'))? '': 'checked' }} value="0" /> тестовый
                        </label>
                        <button disabled class="btn btn-default btn-sm pull-right">Очистить кеш</button>
                    </div>
                </div>
                <div class="form-group">
                	<label class="col-xs-2 control-label">Название сайта</label>
                	<div class="col-xs-10">
                		<input type="text" name="name_sait" maxlength="255" class="form-control input-sm" placeholder="будет использоваться в ключевых моментах" value="{{ Session::has('aSettings.name_sait')? Session::get('aSettings.name_sait'): '' }}" />
                	</div>
                </div>
                <div class="form-group">
                	<label class="col-xs-2 control-label">Логотип</label>
                	<div class="col-xs-10">
                    	<label class="btn btn-default btn-sm btn-file" title="выберите изображение">
                        	 <span>Выбрать логотип</span>
                            <input type="file" name="logo_new" onChange="file_input_onchange(this)">
                        </label>
                        @if(!empty(Session::get('aSettings.logo')))
                        	 <div>
                                <br />
                                <input type="hidden" name="logo_old" value="{{ Session::get('aSettings.logo') }}" />
                                <span class="img-thumbnail img-thumbnail-wrap">
                                    <i class="fa fa-times interactive" title="удалить" 
                                    	onClick="confirm('Точно удалить?')? $(this).parent().parent().remove(): ''"></i>
                                    <img height="100" src="{{ Session::get('aSettings.logo') }}" />
                                </span>
                           </div>
                        @endif
                   </div>
                </div>
                <div class="form-group">
                	<label class="col-xs-2 control-label">Телефон(ы)</label>
                	<div class="col-xs-10">
                    	<div class="margin-bottom-small">
                    		<button class="btn btn-primary btn-sm" onClick="add_empty_tr_with_one_val(event, $(this).parent().parent(), 'phones', 'впишите номер телефона')" title="добавить номер телефона"><i class="fa fa-plus"></i></button>
                        </div>
                        @if(!empty(Session::get('aSettings.phones')) && is_array(Session::get('aSettings.phones')))
                        	@foreach(Session::get('aSettings.phones') as $key => $val)
                            	<div class="margin-bottom-small">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td><input type="text" name="phones[{{ $key }}]" maxlength="255" class="form-control input-sm" placeholder="впишите номер телефона" value="{{ $val }}" /></td>
                                           <td width="5"></td>
                                           <td><button class="btn btn-default btn-sm" onClick="$(this).parent().parent().parent().parent().remove();"><i class="fa fa-times"></i></button></td> 
                                       </tr>
                                   </table>
                                </div>
                           @endforeach
                        @endif
                   </div>
                </div>
                <div class="form-group">
                	<label class="col-xs-2 control-label">Счетчики</label>
                	<div class="col-xs-10">
                    	<textarea class="form-control input-sm" rows="10" name="counters" maxlength="5000" placeholder="вписывыйте сюда все счетчики или др. яваскрпт код">{!! !empty(Session::get('aSettings.counters'))? Session::get('aSettings.counters'): '' !!}</textarea>
                		<small class="text-muted">все счетчики (Google Analitics, Yandex Metrica и т.д.) в одно поле</small>
                	</div>
                </div>
                <div class="form-group">
                	<label class="col-xs-2 control-label">Рабочий е-мэйл</label>
                	<div class="col-xs-10">
                		<input type="text" name="email" maxlength="255" class="form-control input-sm" placeholder="эл. адрес куда люди могут писать письма" value="{{ !empty(Session::get('aSettings.email'))? Session::get('aSettings.email'): '' }}" />
                	</div>
                </div>
                <div class="form-group">
                	<label class="col-xs-2 control-label">Почтовые параметры</label>
                	<div class="col-xs-10">
                    	<div class="margin-bottom-small">
                            <em><small>SMTP хост</small></em>
                            <input disabled type="text" name="smtp_host" maxlength="255" class="form-control input-sm" placeholder="обычно mail.domain.ru или localhost" value="{{ !empty(Session::get('aSettings.smtp_host'))? Session::get('aSettings.smtp_host'): '' }}" />
                        </div>
                        <div class="margin-bottom-small">
                            <em><small>SMTP порт</small></em>
                            <input disabled type="text" name="smtp_port" maxlength="255" class="form-control input-sm" placeholder="обычно 25, 2525, 587" value="{{ !empty(Session::get('aSettings.smtp_port'))? Session::get('aSettings.smtp_port'): '' }}" />
                        </div>
                        <div class="margin-bottom-small">
                            <em><small>SMTP логин</small></em>
                            <input type="text" name="smtp_login" maxlength="255" class="form-control input-sm" placeholder="обычно формата name@domain.ru" value="{{ !empty(Session::get('aSettings.smtp_login'))? Session::get('aSettings.smtp_login'): '' }}" />
                        </div>
                        <div class="margin-bottom-small">
                            <em><small>SMTP пароль</small></em>
                            <input disabled type="text" name="smtp_pass" maxlength="255" class="form-control input-sm" placeholder="не менее 8 символов" value="{{ !empty(Session::get('aSettings.smtp_pass'))? Session::get('aSettings.smtp_pass'): '' }}" />
                        </div>
                   </div>
                </div>
                <div class="form-group">
                	<label class="col-xs-2 control-label">Соц. сети</label>
                	<div class="col-xs-10">
                    	<div class="margin-bottom-small">
                    		<button class="btn btn-primary btn-sm" onClick="add_empty_tr_soc(event, $(this).parent().parent())" title="добавить соц. сеть"><i class="fa fa-plus"></i></button>
                        </div>
                        @if(!empty(Session::get('aSettings.soc_link')) && !empty(Session::get('aSettings.soc_html')))
                        	@foreach(Session::get('aSettings.soc_link') as $key => $val)
                            	@if(!empty($val) && Session::has('aSettings.soc_html.'.$key))
                                     <div class="margin-bottom-small">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td><input type="text" name="soc_link[{{ $key }}]" maxlength="255" class="form-control input-sm" placeholder="прямая ссылка" value="{{ Session::get('aSettings.soc_link.'.$key) }}" /></td>
                                               <td width="5"></td>
                                               <td><input type="text" name="soc_html[{{ $key }}]" maxlength="255" class="form-control input-sm" placeholder="код-html или текст" value="{{ Session::get('aSettings.soc_html.'.$key) }}" /></td>
                                               <td width="5"></td>
                                               <td><button class="btn btn-default btn-sm" onClick="$(this).parent().parent().parent().parent().remove();"><i class="fa fa-times"></i></button></td> 
                                           </tr>
                                       </table>
                                    </div>
                               @endif
                           @endforeach
                        @endif
                   </div>
                </div>
                <div class="form-group">
                	<label class="col-xs-2 control-label">Дополнительная информация в футер</label>
                	<div class="col-xs-10">
                    	<textarea class="form-control input-sm" rows="3" name="dop_info_for_footer" maxlength="255" placeholder="небольшой расширяющий информацию текст">{!! !empty(Session::get('aSettings.dop_info_for_footer'))? Session::get('aSettings.dop_info_for_footer'): '' !!}</textarea>
                   </div>
                </div>
                <div class="form-group">
                	<label class="col-xs-2 control-label">Мин-ый порог суммы для оформления</label>
                	<div class="col-xs-10">
                		<input type="text" name="cart_min_treshhold" maxlength="11" class="form-control input-sm" placeholder="минимальная сумма денег при которой возможно оформление заказа" value="{{ !empty(Session::get('aSettings.cart_min_treshhold'))? Session::get('aSettings.cart_min_treshhold'): 0 }}" />
                	</div>
                </div>
                <div class="form-group">
                	<div class="col-xs-offset-2 col-xs-10">
                    	<table width="100%">
                        	<tr>
                           		<td align="left" valign="top" width="33%">
                                	<div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="use_as_shop" {{ !empty(Session::get('aSettings.use_as_shop'))? 'checked': '' }} /> вкл. интернет-магазин
                                        </label>
                                   </div>
                               </td>
                               <td align="left" valign="top" width="33%">
                                	<div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="set_photo_cat_to_photo_product" {{ !empty(Session::get('aSettings.set_photo_cat_to_photo_product'))? 'checked': '' }} /> ставить фото категории, если у продукта нет своего фото
                                        </label>
                                   </div>
                               </td>
                           </tr>
                        </table>
                	</div>
                </div>
                <div class="form-group">
                    <div class="col-xs-offset-2 col-xs-10">
                    	{{ csrf_field() }}
                    	<button type="submit" class="btn btn-success">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection