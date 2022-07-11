<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;

class StreamController extends Controller
{   

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {

        $streams = Stream::where('author_id', '=',auth()->user()->id )->latest()->paginate(5);
    
        return view('streams.index',compact('streams'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('streams.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'description' => 'required',
        ]);

        


        $jayParsedAry = [
            
            "status" => "finished", 
            "playListStatus" => "finished", 
            "type" => "liveStream", 
            "publishType" => "WebRTC", 
            "name" => $request->title, 
            "description" => $request->description, 
            "publish" => true, 
            "date" => 0, 
            "plannedStartDate" => 0, 
            "plannedEndDate" => 0, 
            "duration" => 0, 
            
            "playlistLoopEnabled" => true 
         ]; 


        if ( $request->stream_id != '' ){
            $jayParsedAry["streamId"]= $request->stream_id;
        }

        $response = Http::post('http://185.209.160.70:5080/LiveApp/rest/v2/broadcasts/create', $jayParsedAry);

        if ($response->failed()) {
            // return failure
            return redirect()->back()
                    ->withErrors("Ошибка");
        } else {
            // return success
            $result = $response->json();
            // print("<pre>".print_r($response->json()['streamId'],true)."</pre>");
            // $response->ok()

            

            $path = $request->file('image')->store('public/images','public');
            
            $stream = new Stream;
            $stream->title = $request->title;
            $stream->description = $request->description;
            $stream->image = $path;


            $author = auth()->user();
            

            $stream->author_id = $author->id;
            $stream->author_name = $author->name;

            
            $stream->stream_id = $result['streamId'] ;
            
            $stream->save();
        
            return redirect()->route('streams.index')->with('success','Stream has been created successfully.');
        }
        // $response->json();
        
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\Response
     */
    public function show(Stream $stream)
    {
        //
        
        $response = Http::get('http://185.209.160.70:5080/LiveApp/rest/v2/broadcasts/'.$stream->stream_id);

        if ($response->failed()) {
            // return failure
            return redirect()->back()
                    ->withErrors("Ошибка");
        }
        $ant_media_result = $response->json();
        
        return view('streams.show',['stream'=>$stream, 'ant_media_result'=>$ant_media_result]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\Response
     */
    public function edit(Stream $stream)
    {
        return view('streams.edit',compact('stream'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stream $stream)
    {
        //
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        
        $stream = Stream::find($id);
        if($request->hasFile('image')){
            $request->validate([
              'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $path = $request->file('image')->store('public/images','public');
            $stream->image = $path;
        }
        $stream->title = $request->title;
        $stream->description = $request->description;
        $stream->save();
    
        return redirect()->route('streams.index')->with('success','Stream updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stream  $stream
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stream $stream)
    {
        //
        $response = Http::delete('http://185.209.160.70:5080/LiveApp/rest/v2/broadcasts/'.$stream->stream_id);
        if ($response->failed()) {
            // return failure
            return redirect()->back()
                    ->withErrors("Ошибка");
        }
        var_dump ($response->json());
        die();
        $stream->delete();
    
        return redirect()->route('streams.index')->with('success','Stream has been deleted successfully');
    }
}
