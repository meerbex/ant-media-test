@extends('layouts.app')
   
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show Stream</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('streams.index') }}"> Back</a>
            </div>
        </div>
    </div>
     
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Title:</strong>
                {{ $stream->title }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Author:</strong>
                {{ $stream->author_name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Description:</strong>
                {{ $stream->description }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            
            @if ($ant_media_result['status'] == "created")
                <div class="form-group">
                    <img width="100%" height="500" src="{{asset('storage/'.$stream->image )}}" width="500px">
                </div>
                <h1> Stream has not been starte yet</h1>
            @else
            <iframe style="width: 100%" height="500" src="http://185.209.160.70:5080/LiveApp/play.html?name={{ $stream->stream_id }}" frameborder="0" allowfullscreen></iframe>
            @endif
        </div>
    </div>
@endsection