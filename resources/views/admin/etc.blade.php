@extends('layouts.admin')

@section('content')
	<script>
		var csrf_token = "{{ csrf_token() }}";
	</script>
    
	<div class="row">
    	<div class="col-xs-12">
        	<div id="block_update_price_for_category">
                <select class="form-control input-sm input-inline" name="method">
                    <option value="1">увеличить</option>
                    <option value="0">уменьшить</option>
                </select>
                цену на <input class="form-control input-sm input-inline" type="text" name="percent" maxlength="2" value="0" /> %
                в категории(ях)
                <select class="form-control input-sm input-inline" name="categories">
                    <option value="0">все категории</option>
                    {!! show_categories_as_options_simple($aCategories) !!}
                </select>
                <button class="btn btn-success btn-sm" 
                		 data-loading-text="Загрузка..." 
                        onClick="update_price_for_category(this)">приминить</button>
                <div class="text-muted small">P.S. после применения цена изменяется, будте внимательны при последующих обновлениях цен,<br /> т.к. последующее обновление будет происходить из нового значения.</div>
            </div>
        </div>
    </div>
@endsection