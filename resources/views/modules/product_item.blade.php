<div class="col-xs-12 col-sm-6 col-md-4 {{ !empty($aCategories)? 'col-lg-4': 'col-lg-3' }}">
    <div class="thumbnail thumbnail_product shadow_hover">
        <a class="thumbnail_cover" href="/{{ $data->slug }}" rel="nofollow">
            @if(!empty($data->discount))
                @include('modules.discount_emblem', ['value' => $data->discount])
            @endif
            
            @if(!empty($data->is_absent) || empty($data->price))
                @include('modules.absent_emblem')
            @endif	
            
            {{-- проследим за фотографией --}}
            @if($data->images->count())
            	<img class="img-responsive" height="190" src="/imager{{ $data->images->first()->path }}/0/190" />
            @elseif(!empty($data->spare_photo))
            	<img class="img-responsive" height="190" src="/imager{{ $data->spare_photo }}/0/190" />
            @else
            	<img class="img-responsive" height="190" src="/img/no_image_300.jpg" />
            @endif
        </a>
        <div class="caption">
            <div class="thumbnail_sku text_eclipse text-muted">{{ $data->sku }}</div>
            <a class="thumbnail_title" href="/{{ $data->slug }}"><strong>{{ $data->name }}</strong></a>
            <p>
                <select class="rating" data-rating="{{ $data->rating }}" >
                    <option value="1"></option>
                    <option value="2"></option>
                    <option value="3"></option>
                    <option value="4"></option>
                    <option value="5"></option>
                </select>
            </p>
            
            @if(!empty($data->desc_short0))
                <p>{{ $data->desc_short }}</p>
            @endif
            
            @include('modules.product_price', ['price' => $data->price, 'discount' => $data->discount])
            
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="left" valign="top">
                        <button class="btn btn-success" 
                                 {{ (!empty($data->is_absent) || empty($data->price))? 'disabled=disabled': '' }} 
                                 data-loading-text="Загрузка..." 
                                onClick="Cart.add_via_thumbnail(this, {{ $data->id }})" >В корзину</button>
                    </td>
                    <td width="10"></td>
                    <td align="right" valign="top">
                        @include('modules.input_plus_minus', ['amount' => 1])
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>