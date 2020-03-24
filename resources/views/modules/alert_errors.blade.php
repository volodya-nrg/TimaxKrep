@if(!empty($errors) && $errors->count())
    <div class="alert alert-danger flash-result-block">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif