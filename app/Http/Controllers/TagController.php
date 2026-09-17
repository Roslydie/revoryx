<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(){
        $data = Tag::orderBy('id','desc')->get();
        return response()->json([
            "data"=>$data
        ]);
    }

    public function show($id){
        $data = Tag::find($id);
        return response()->json([
            "data"=>$data
        ]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name'=>"required"
        ]);
        $data = Tag::create($validated);
        return response()->json([
            "data"=>$data
        ]);
    }

    public function edit(Request $request,$id){
        $news = Tag::find($id);
        $data = $news->update($request->all());
        return response()->json([
            "data"=>$data
        ]);
    }

    public function destroy($id){
        $data = Tag::find($id);
        $data->delete();
        return response()->json([
            'data'=>$data
        ]);
    }
}