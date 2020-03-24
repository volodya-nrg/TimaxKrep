<div class="input-plus-minus">
    <i class="fa fa-plus-square-o" onClick="input_plus_minus(this, 1)"></i>
    <i class="fa fa-minus-square-o" onClick="input_plus_minus(this, 0)"></i>
    <input class="form-control {{ !empty($is_small)? 'input-sm': '' }}" 
    		type="text" 
            name="product_amount" 
            value="{{ $amount or 1 }}" 
            maxlength="3" 
            onChange="input_plus_minus(this, -1)" />
</div>