<ol class="breadcrumb">
	@if($data->count())
    	<?php $save = ""; ?>
        @foreach($data as $val)
           @if(empty($val['active']))
            	<li class="active">{{ $val['name'] }}</li>
		   @else
           		<?php $save .= $val['slug']; ?>
                <li><a class="a_darkgray" href="{{ $save }}">{!! $val['name'] !!}</a></li>
           @endif
       @endforeach
    @endif
</ol>