@if(isset($urls))
    <h1>Unavailable sites</h1>
    @foreach($urls as $url)
        <p>{{$url->url}}</p>
    @endforeach

@endif