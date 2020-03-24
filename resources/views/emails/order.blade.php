<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
    	<td align="left" valign="bottom">
        	<a href="{{ $domain }}">
                @if(!empty(Session::get('aSettings.logo')))
                    <img height="40" src="{{ $domain . Session::get('aSettings.logo') }}" />
                @else
                    {{ $domain }}
                @endif
            </a>
        </td>
        @if(!empty($status_text))
            <td width="10"></td>
            <td align="right" valign="bottom">
                Статус заказа - <font color="green" size="+1" style="text-transform:uppercase">{{ $status_text }}</font>
            </td>
        @endif
    </tr>
</table>
<br />
<table border="0" width="95%" cellpadding="5" cellspacing="0">
	@foreach($colProducts as $val)
    	<tr>
            <td align="left" valign="middle" width="40" style="border-bottom: whitesmoke solid 1px">
            	@if(!empty($val->img_path))
                	<img height="30" src="{{ $domain . $val->img_path}}" />
               @elseif(!empty($val->spare_photo))
            		<img height="30" src="{{ $domain . $val->spare_photo }}" />     
               @else
               		<img height="30" src="{{ $domain }}/img/no_image_100.jpg" />
               @endif
            </td>
            <td align="left" valign="middle" style="border-bottom: whitesmoke solid 1px">
                <a href="{{ $domain }}/{{ $val->slug }}" target="_blank">{{ $val->name }}</a>
                <br>
                <font color="lightgray" size="-1">{{ implode(' / ', $val->category_names) }}</font> 
            </td>
            <td align="right" width="70" style="border-bottom: whitesmoke solid 1px">
                {{ $val->in_cart }} шт.
            </td>
            <td align="right" width="100" style="border-bottom: whitesmoke solid 1px">
                {!! html_price($val->price) !!} руб.<br /><small>за единицу</small>
            </td>
        </tr>
    @endforeach
</table>
<br />
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
    	<td align="left" valign="bottom" width="200">
            <font color="gray">Дата создания:</font>
            <br />
            {{ $created_at }}
        </td>
        <td align="left" valign="bottom" width="200">
            <font color="gray">Дата обновления:</font>
            <br />
            {{ $updated_at }}
        </td>
        <td align="right" valign="bottom">
            <div style="display:table; text-align:left">
                Кол-во товаров: <strong><font size="+1">{{ $total_products }}</font></strong> шт.
                <br>
                Общая сумма: <strong><font size="+1">{!! html_price($total_sum) !!}</font></strong> руб. 
            </div>
        </td>
    </tr>
</table>