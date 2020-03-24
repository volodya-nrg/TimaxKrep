<div class="col-xs-12 col-sm-6 col-md-4">
	<a class="category_item shadow_hover" href="{{ URL::current() }}/{{ $data->slug }}">
    	<div class="category_item_title text_eclipse">
        	{{ $data->name }}
        </div>
        
        <center>
            <div class="category_item_img">
                @if(!empty($data->img_path))
                    <img src="/imager{{ $data->img_path }}/200/200" />
                @else
                    <img class="img-responsive" src="/img/no_image_300.jpg" />
                @endif
            </div>
        </center>
    </a>
</div>