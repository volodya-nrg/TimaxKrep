<p class="price {{ !empty($text_to_left)? 'text-left': 'text-right' }}">
    <span class="text-muted">цена</span>
    
    @if(!empty($discount))
        <b class="text-muted">&nbsp;{{ html_price($price) }}&nbsp;</b>
        <strong class="text-danger">{{ calc_price_with_discount($price, $discount) }}</strong> 
    @else
        <strong>{!! html_price($price) !!}</strong>  
    @endif 
    
    <i class="fa fa-rub"></i> 
</p>