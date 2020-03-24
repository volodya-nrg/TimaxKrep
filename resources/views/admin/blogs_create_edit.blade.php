@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
        	@include('modules.alert_errors')
            
            <form action="/admin/blogs{!! !empty($data->id)? '/'.$data->id: '' !!}" class="form-horizontal" method="post" >
                
                @if(!empty($data->id))
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <div class="form-group">
                        <label class="col-xs-2 control-label">Slug</label>
                        <div class="col-xs-10">
                            <input type="text" class="form-control input-sm" readonly name="slug"
                                   value="{{ !empty($data->slug)? $data->slug: old('slug') }}" />
                        </div>
                    </div>
                @endif
                
                <div class="form-group">
                    <label class="col-xs-2 control-label">Название (title) <span class="text-danger">*</span></label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control input-sm" name="title" 
                               value="{{ !empty($data->title)? $data->title: old('title') }}" />
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
                    <label class="col-xs-2 control-label">Описание</label>
                    <div class="col-xs-10">
                         <textarea id="editor1" class="form-control ckeditor" rows="3" name="description">{{ !empty($data->description)? $data->description: old('description') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-offset-2 col-xs-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" 
                                		name="is_hide"
                                       {{ !empty($data->is_hide)? 'checked': '' }} /> скрыть блог
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