@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            @include('modules.alert_errors')
            
            <form  action="/admin/categories{!! !empty($data->id)? '/'.$data->id: '' !!}" 
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
                    <label class="col-xs-2 control-label">Картинка</label>
                    <div class="col-xs-10">
                        <label class="btn btn-default btn-sm btn-file" title="выберите изображение">
                        	 <span>Выбрать файл</span>
                            <input type="file" name="img_new" onChange="file_input_onchange(this)">
                        </label>
                        @if(!empty($data->img_path))
                        	 <div>
                            	 <br />
                                <input type="hidden" name="img_old" value="{{ $data->img_path }}" />
                                <span class="img-thumbnail img-thumbnail-wrap">
                                    <i class="fa fa-times interactive" title="удалить" 
                                    	onClick="confirm('Точно удалить?')? $(this).parent().parent().remove(): ''"></i>
                                    <img height="100" src="/imager{{ $data->img_path }}/0/100" />
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Заголовок (title)</label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="title" 
                            value="{{ !empty($data->title)? $data->title: old('title') }}" >
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
                    <label class="col-xs-2 control-label">Название <span class="text-danger">*</span></label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="name" 
                               value="{{ !empty($data->name)? $data->name: old('name') }}" />
                        <small class="text-muted">slug происходит от названия</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Родитель</label>
                    <div class="col-xs-10">
                        <select class="form-control input-sm" name="parents">
                            <option value=""></option>
                            {!! show_categories_as_options(
                                    $aCategories, 
                                   !empty($data->id)? $data->id: 0, 
                                   !empty($data->parent)? $data->parent: 0
                                ) !!}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Описание</label>
                    <div class="col-xs-10">
                         <textarea class="form-control ckeditor" rows="3" name="description">{{ !empty($data->description)? $data->description: old('description') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Позиция</label>
                    <div class="col-xs-10">
                         <input class="form-control input-sm" type="text" name="position" value="{{ !empty($data->position)? $data->position: 0 }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-offset-2 col-xs-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_hide" {{ !empty($data->is_hide)? 'checked': '' }} /> скрыть категорию
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                 <input type="checkbox" name="show_as_product" {{ !empty($data->show_as_product)? 'checked': '' }} /> отображать как продукт
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                 <input type="checkbox" name="show_in_top_menu" {{ !empty($data->show_in_top_menu)? 'checked': '' }} /> показывать в верхнем меню
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