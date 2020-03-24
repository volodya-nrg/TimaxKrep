// JavaScript Document
$(function(){
	$('.list_tree').find('.fa-caret-down').bind('click', function(){
		$(this).parent().parent().addClass('active');
	});
	$('.list_tree').find('.fa-caret-up').bind('click', function(){
		$(this).parent().parent().removeClass('active');
	});
	
	if($('.slick').size()){
		$('.slick').slick({
			dots: true,
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true,
		});
	}
	if($('#banners_on_index').size()){
		$('#banners_on_index').slick({
			dots: true,
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true,
			autoplay: true,
  			autoplaySpeed: 5000,
		});
	}
	if($('#ours_partners').size()){
		$('#ours_partners').slick({
			dots: false,
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			autoplay: true,
  			autoplaySpeed: 5000,
		});
	}
	
	if($('.rating').size() >= 1){
		$('.rating').barrating({
			theme: 'fontawesome-stars',
			readonly: true,
			initialRating: -1, // поставим по умолчанию
		});
		
		$('.rating').each(function(){
			var cur_rating = parseInt($(this).data('rating'));
			
			if(cur_rating){
				$(this).barrating('set', cur_rating);
			}	
		});
	}
	
	// если есть оповещающие блоки об ошибках или успешном сохранении и т.д., то эти блоки скроем через не которое время
	if($('.flash-result-block').size()){
		hide_block($('.flash-result-block'), 3000, 'slideUp');
	}
	
	$('.top_menu_level_1').hover(
		function(){
			var $el = $(this).find('.top_menu_level_2');
			$el.show();
		},
		function(){
			var $el = $(this).find('.top_menu_level_2');
			$el.hide();
		}
	);
	
	if($(".fancybox").size()){
		$(".fancybox").fancybox({
			padding: 20,
		});
	}
	
	Cart.init();
});

var Cart = {
	selector_product_amount: 'input.form-control[name=product_amount]',
	$header_total_sum: 0,
	$header_total_products: 0,
	$page_total_sum: 0,
	$page_total_products: 0,
	
	init: function(){
		this.$header_total_sum = $('#cart_total_sum');
		this.$header_total_products = $('#cart_total_products');
		this.$page_total_sum = $('#page_total_sum');
		this.$page_total_products = $('#page_total_products');
	},
	// token передается в параметре - отдельный случай
	store: function(obj, product_id, amount, token){
		if(isNaN(amount) || amount === 0){
			return;
		}
		if(token == ""){
			alert("Ошибка: не указан token!");
			return;	
		}
		
		var request = {
			product_id: product_id,
			amount: amount,
			_token: token,
		};
		
		$(obj).button('loading');
		$.post('/cart/store', request, function(data){
			if(data.result){
				show_alert_msg('success', 'Товар добавлен');
				Cart.update_text(data.total_sum, data.total_products);
			}
			else{
				show_alert_msg('danger', 'Ошибка в запросе');
			}
			
			$(obj).button('reset');
		}, "json");
	},
	add_via_card_product: function(obj, product_id){
		var amount = $(this.selector_product_amount).size()? parseInt($(this.selector_product_amount).val()): 1;
		this.store(obj, product_id, amount, window.csrf_token);
	},
	add_via_thumbnail: function(obj, product_id){
		var $tmp = $(obj).parent().parent().find(this.selector_product_amount);
		var amount = $tmp.size()? parseInt($tmp.val()): 1;
		var token = (window.csrf_token != undefined)? window.csrf_token: "";
		
		this.store(obj, product_id, amount, token);
	},
	update: function(obj, id){
		var token = window.csrf_token;
		var amount = parseInt($(obj).parent().find('input[name=amount]').val());
		
		if(isNaN(amount) || amount === 0){
			return;
		}
		if(token == ""){
			alert("Ошибка: не указан token!");
			return;	
		}
		
		var request = {
			product_id: id,
			amount: amount,
			_method: 'PUT',
			_token: token,
		};
		
		$(obj).button('loading');
		$.post('/cart/update', request, function(data){
			if(data.result){
				show_alert_msg('success', 'Корзина обновлена');
				Cart.update_text(data.total_sum, data.total_products);
			}
			else{
				show_alert_msg('danger', 'Ошибка в запросе');
			}
			
			$(obj).button('reset');
		}, "json");
	},
	destroy: function(obj, id){
		var token = window.csrf_token;
		
		if(token == ""){
			alert("Ошибка: не указан token!");
			return;
		}
		
		var request = { 
			product_id: id,
			_method: 'DELETE',
			_token: token, 
		};
		
		$(obj).addClass('disabled');
		$.post('/cart/destroy', request, function(data){
			if(data.result){
				$(obj).parent().parent().parent().parent().fadeOut('normal', function(){ 
					$(this).remove();
					
					if($('#cart_products').find('tr').size() == 0){
						$('#cart_products').empty();
						$('#cart_empty_msg').removeClass('hide');
					}
					
					Cart.update_text(data.total_sum, data.total_products);
				});
			}
			else{
				$(obj).removeClass('disabled');	
			}
		}, "json");
	},
	update_text: function(total_sum, total_products){
		this.$header_total_sum.text(total_sum);
		this.$header_total_products.text(total_products);
		this.$page_total_sum.text(total_sum);
		this.$page_total_products.text(total_products);
	}
};

function show_alert_msg(bootstrap_class, msg){
	var $obj = $('<div class="alert alert-'+bootstrap_class+' alerts_fixed">'+msg+'</div>');
	$obj.css({
		'z-index': 100 + $('.alerts_fixed').size(),
	});
	
	$('body').append($obj);
	$obj.fadeIn('fast');
	
	setTimeout(function(){ $obj.fadeOut('fast', function(){ $(this).remove(); }); }, 4000);
}

function add_empty_tr_attribute_for_product(event, $target, array){
	if(!array.length){
		return;	
	}
	
	var key = $('select[name^=attribute_id]').size();
	var options = '';
	
	for(var i=0; i < array.length; i++){
		options += '<option value="'+ array[i][0] +'">'+ array[i][1] +'</option>';
	}
	
	var str = new Array(
		'<tr>',
			'<td width=300>',
				'<select class="form-control input-sm" name="attribute_id['+key+']">',
					options,
				'</select>',
		    '</td>',
			'<td>',
				'<input class="form-control input-sm" type="text" name="attribute_value['+key+']" value="" />',
		    '</td>',
		    '<td width=50>',
				'<button class="btn btn-default btn-sm" onClick="$(this).parent().parent().remove();">',
					'<i class="fa fa-times"></i>',
				'</button>',
			'</td>',
	   '</tr>'
	);
	
	$target.append(str.join(''));
	event.preventDefault();
}
function add_empty_tr_soc(event, $target){
	var key = $target.find('input[name^=soc_link]').size();
	var str = new Array(
		'<div class="margin-bottom-small">',
			'<table border="0" cellpadding="0" cellspacing="0" width="100%">',
				'<tr>',
					'<td><input type="text" name="soc_link['+key+']" class="form-control input-sm" placeholder="прямая ссылка на соц. сеть" /></td>',
				   	 '<td width="5"></td>',
				    '<td><input type="text" name="soc_html['+key+']" class="form-control input-sm" placeholder="html-код для отображения" /></td>',
				    '<td width="5"></td>',
				    '<td><button class="btn btn-default btn-sm" onClick="$(this).parent().parent().parent().parent().remove();" title="удалить"><i class="fa fa-times"></i></button></td>',
			   '</tr>',
		   '</table>',
		'</div>'
	);
	
	$target.append(str.join(''));
	event.preventDefault();
}
function add_empty_tr_with_one_val(event, $target, input_name, placeholder){
	var placeholder = (placeholder != undefined && placeholder != "")? placeholder: "впишите значение";
	var key = $target.find('input[name^='+input_name+']').size();
	var str = new Array(
		'<div class="margin-bottom-small">',
			'<table border="0" cellpadding="0" cellspacing="0" width="100%">',
				'<tr>',
					'<td><input type="text" name="'+input_name+'['+key+']" class="form-control input-sm" placeholder="'+placeholder+'" /></td>',
				   	 '<td width="5"></td>',
				    '<td><button class="btn btn-default btn-sm" onClick="$(this).parent().parent().parent().parent().remove();" title="удалить"><i class="fa fa-times"></i></button></td>',
			   '</tr>',
		   '</table>',
		'</div>'
	);
	
	$target.append(str.join(''));
	event.preventDefault();
}

function input_plus_minus(obj, opt){
	/*
		-1 - если это сам input
		1 - кнопка +
		0 - кнопка -
	*/
	var $input = {};
	
	if(opt == -1){
		$input = $(obj);	
	}
	else{
		$input = $(obj).parent().find('input');
	}
	
	var cur_val = parseInt($input.val());
	
	if(opt == 1){
		cur_val++;
	}
	else if(opt == 0){
		cur_val--;
	}
	
	if(isNaN(cur_val) || cur_val == 0){
		cur_val = 1;
	}
	else if(cur_val < 0){
		cur_val = Math.abs(cur_val);
	}
	
	$input.val(cur_val);
}
function file_input_onchange(obj){
	var $obj = $(obj);
	var img_name = $obj.val();
	
	if(img_name != ""){
		$obj.parent().removeClass('btn-default').addClass('bg-info');
		$obj.parent().find('span').text(img_name);
	}
}
function hide_block($target, input_time, effect){
	var time = 3000;
	
	if(input_time != undefined){
		input_time = parseInt(input_time);
		
		if(isNaN(input_time) || input_time <= 0){
			input_time = 0;
		}
		
		if(input_time > 0){
			time = input_time;
		}
	}
	setTimeout(function(){
		if(effect == 'slideUp'){
			$target.slideUp();
		}
		else if(effect == 'fadeOut'){
			$target.fadeOut();
		}
		else{
			$target.hide();
		}
	}, time);
}
function update_price_for_category(obj){
	var aErr = new Array();
	var $btn = $(obj);
	var token = window.csrf_token;
	var method = parseInt($btn.parent().find('select[name=method]').val());
	var percent = parseInt($btn.parent().find('input[name=percent]').val());
	var category_id = parseInt($btn.parent().find('select[name=categories]').val());
	
	if(isNaN(percent) || percent == 0){
		aErr.push('не указан процент');
	}
	if(token == undefined || token == ""){
		aErr.push('не указан token');
	}
	
	if(aErr.length){
		alert(aErr.join('\n'));
		return;
	}
	
	var request = {
		percent: percent,
		category_id: category_id,
		method: method,
		_method: 'PUT',
		_token: token,
	};
	
	$btn.button('loading');
	$.post('/admin/update_price_for_category', request, function(data){
		if(!data.result){
			alert(data.msg);
		}
		
		$btn.button('reset');
	}, "json");
}