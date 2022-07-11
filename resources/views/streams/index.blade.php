@extends('layouts.app')
     
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('streams.create') }}"> Create New Stream</a>
            </div>
        </div>
    </div>
    
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
     
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Image</th>
            <th>Name</th>
            <th>Details</th>
            <th>Key</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($streams as $stream)
        <tr>
            <td>{{ ++$i }}</td>
            <td><img src="{{asset('storage/'.$stream->image )}}" width="100px"></td>
            <td>{{ $stream->title }}</td>
            <td>{{ $stream->description }}</td>
            <td>{{ $stream->stream_id }}</td>
            <td>
                <form action="{{ route('streams.destroy',$stream->id) }}" method="POST">
     
                    <a class="btn btn-info" href="{{ route('streams.show',$stream->id) }}">Show</a>
      
                    <a class="btn btn-primary" href="{{ route('streams.edit',$stream->id) }}">Edit</a>
     
                    @csrf
                    @method('DELETE')
        
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    
    {!! $streams->links() !!}
        
@endsection