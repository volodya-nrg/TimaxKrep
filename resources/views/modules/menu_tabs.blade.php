<ul class="nav nav-tabs">
    @foreach($aTabs as $item)
    	<li {{ $item['active'] === 1? 'class=active': '' }} ><a href="{{ $item['url'] }}">{{ $item['name'] }}</a></li>
    @endforeach
</ul>