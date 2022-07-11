@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Online Streams</h1>
    <div class="row ">
        @foreach ($streams as $stream)
        <div class="col-md-3">
            <div class="card">
                <img class="card-img-top" src="{{asset('storage/'.$stream->image )}}" alt="Card image cap">

                <div class="card-body">
                    <h5 class="card-title">{{ $stream->title }}</h5>
                    <a href="/streams/{{ $stream->id }}" class="btn btn-primary">Open stream</a>
                </div>
            </div>
        </div>

        @endforeach

        
    </div>
</div>
@endsection
