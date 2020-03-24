<div class="thumbnail thumbnail_dop_product shadow_hover">
    <a class="thumbnail_cover" href="/{{ $data->slug }}" rel="nofollow">
        @if(!empty($data->discount))
            @include('modules.discount_emblem', ['value' => $data->discount])
        @endif
        
        @if(!empty($data->is_absent) || empty($data->price))
            @include('modules.absent_emblem', ['value' => $data->discount])
        @endif
        
        {{-- проследим за фотографией --}}
        @if($data->images->count())
            <img height="164" src="/imager{{ $data->images->first()->path }}/0/164" />
        @elseif(!empty($data->spare_photo))
            <img height="164" src="/imager{{ $data->spare_photo }}/0/164" />
        @else
            <img height="164" src="/img/no_image_300.jpg" />
        @endif   
    </a>
    <div class="caption">
        <small class="thumbnail_sku text_eclipse text-muted">{{ $data->sku }}</small>
        <a class="thumbnail_title" href="/{{ $data->slug }}" target="_blank"><strong>{{ $data->name }}</strong></a>
        
        @include('modules.product_price', ['price' => $data->price, 'discount' => $data->discount])
        
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="left" valign="top">
                    <button class="btn btn-success btn-sm" data-loading-text="Загрузка..."  
                    		{{ (!empty($data->is_absent) || empty($data->price))? 'disabled=disabled': '' }}
                            onClick="Cart.add_via_thumbnail(this, {{ $data->id }})" >В корзину</button>
                </td>
                <td width="10"></td>
                <td align="right" valign="top">
                    @include('modules.input_plus_minus', ['amount' => 1, 'is_small' => 1])
                </td>
            </tr>
        </table>
    </div>
</div>
