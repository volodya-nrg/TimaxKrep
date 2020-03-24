@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            @include('modules.alert_errors')
            
            <form  action="/admin/products{!! !empty($data->id)? '/'.$data->id: '' !!}" 
                   class="form-horizontal" 
                   method="post"
                   enctype="multipart/form-data">
                
                @if(!empty($data->id))
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                @endif
                
                <div class="form-group">
                    <label class="col-xs-2 control-label">Slug</label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" readonly 
                               value="{{ !empty($data->slug)? $data->slug: old('slug') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Фото</label>
                    <div class="col-xs-10">
                        <label class="btn btn-default btn-sm btn-file" title="выберите изображение">
                        	 <span>Выбрать файл(ы)</span>
                            <input type="file" name="images[]" onChange="file_input_onchange(this)" multiple />
                        </label>
                        
                        @if($data->images->count())
                            <table border="0" class="table table-striped table-condensed">
                                <thead>
                                    <th width="50">картинка</th>
                                    <th width="100">позиция</th>
                                    <th width="50">скрытый</th>
                                    <th></th> 
                               
                               @foreach($data->images as $key => $val)
                                    <tr>
                                        <input type="hidden" name="a_img_path[{{$key}}]" value="{{ $val->path }}" />
                                        <td>
                                              <img height="30" src="/imager{{ $val->path }}/0/30" />
                                        </td>
                                        <td align="left">
                                            <input type="text" class="form-control input-sm" 
                                                   name="a_img_position[{{ $key }}]" 
                                                   value="{{ !empty($val->position)? $val->position: old('position') }}" />
                                        </td> 
                                        <td align="center">
                                            <input type="checkbox" name="a_img_is_hide[{{ $key }}]" {{ !empty($val->is_hide)? 'checked': '' }} />
                                        </td>
                                        <td align="left">
                                            <a class="btn btn-default btn-sm" href="javascript: void(0)" 
                                               onClick="$(this).parent().parent().remove();">
                                                <i class='fa fa-times'></i>
                                             </a>
                                       </td>
                                   </tr>
                               @endforeach 
                            
                            </table>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Заголовок (title) <span class="text-danger">*</span></label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="title" 
                            value="{{ !empty($data->title)? $data->title: old('title') }}" />
                        <small class="text-muted">От данного заголовка происходит параметр slug. Старайтесь его не менять, т.к. это влияет поисковые запросы.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Ключевые слова (meta)</label>
                    <div class="col-xs-10">
                        <textarea class="form-control input-sm" rows="3" name="meta_keywords">{{ !empty($data->meta_keywords)? $data->meta_keywords: old('meta_keywords') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Описание (meta)</label>
                    <div class="col-xs-10">
                        <textarea class="form-control input-sm" rows="3" name="meta_desc">{{ !empty($data->meta_desc)? $data->meta_desc: old('meta_desc') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">SKU</label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="sku" 
                               value="{{ !empty($data->sku)? $data->sku: old('sku') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Название <span class="text-danger">*</span></label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control" name="name" 
                               value="{{ !empty($data->name)? $data->name: old('name') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Категория <span class="text-danger">*</span></label>
                    <div class="col-xs-10">
                        <select class="form-control input-sm" name="category_id">
                            {!! show_categories_as_options_simple(
                                    $aCategories, 
                                   !empty($data->category_id)? $data->category_id: 0 
                                ) !!}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Атрибуты</label>
                    <div class="col-xs-10">
                        <script>
                            var aAttributes = [];
                        </script>
                       
                       @if(!empty($aAttributes))
                           @foreach($aAttributes as $val)
                                <script>aAttributes.push([{{ $val->id }}, "{{ $val->name }}"]);</script>
                            @endforeach
                       @endif
                       
                       <button class="btn btn-primary btn-sm" onClick="add_empty_tr_attribute_for_product(event, $('#product_attributes'), window.aAttributes);" >
                       		<i class="fa fa-plus"></i>
                       </button>
                       <table id="product_attributes" class="table table-striped table-condensed">
                            @if(!empty($data->inc_attributes))
                                @foreach($data->inc_attributes as $key => $val)
                                    <tr>
                                        <td width="300">
                                            <select class="form-control input-sm" name="attribute_id[{{ $key }}]">
                                                @foreach($aAttributes as $val1)
                                                    <option value="{{ $val1->id }}" 
                                                    	{{ ($val->id == $val1->id)? 'selected="selected"': '' }}>
                                                        {{ $val1->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                       </td>
                                       <td>
                                            <input class="form-control input-sm" type="text" name="attribute_value[{{ $key }}]" value="{{ $val->value }}" />
                                       </td>
                                       <td width="50">
                                            <button class="btn btn-default btn-sm" 
                                            		 onClick="$(this).parent().parent().remove();" >
                                                <i class="fa fa-times"></i>
                                            </button>
                                       </td> 
                                   </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Описание короткое</label>
                    <div class="col-xs-10">
                         <textarea class="form-control input-sm" rows="3" name="desc_short">{{ !empty($data->desc_short)? $data->desc_short: old('desc_short') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Описание полное</label>
                    <div class="col-xs-10">
                         <textarea class="form-control ckeditor" rows="3" name="desc_full">{{ !empty($data->desc_full)? $data->desc_full: old('desc_full') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Цена <i class="fa fa-rub fa-fw"></i></label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="price" 
                            value="{{ !empty($data->price)? $data->price: old('price') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Скидка <i class="fa fa-percent fa-fw"></i></label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="discount" 
                            value="{{ !empty($data->discount)? $data->discount: old('discount') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Позиция</label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="position" 
                            value="{{ !empty($data->position)? $data->position: old('position') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Похожие товары</label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="similar" 
                            value="{{ !empty($data->similar)? implode(',', $data->similar): old('similar') }}" >
                        <font class="text-muted">id продуктов через запятую, например - 1,2,3</font>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-offset-2 col-xs-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_hide" {{ !empty($data->is_hide)? 'checked': '' }} /> скрыть
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_absent" {{ !empty($data->is_absent)? 'checked': '' }} /> отсутствует на складе
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-offset-2 col-xs-10">
                    	 {{ csrf_field() }}
                        <button type="submit" class="btn btn-success">Отправить</button>
                    </div>
                </div>
            </form>  
        </div>
    </div>
@stop