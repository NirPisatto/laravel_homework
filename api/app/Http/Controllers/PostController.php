<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::with('category')->paginate(15))->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required|max:255',
            'content'=>'required|max:1000',
            'image'=>'required|image|mimes:jpg,png,jpeg,gif,svg|max:20480',
            'category_id'=>'max:500']);

        $file = $request->file('image');

        $imageName = time().'.'.$file->extension();

        $imagePath = public_path(). '/files';

        $post_model = Post::create($request_post);

        $file->move($imagePath, $imageName);

        $request_post = $request->all();

        $request_post['image'] = '/files/'.$imageName ;

        

        return response(new PostResource($post_model), 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post_model = Post::with('category')->find($id);

        if ($post_model === null) {
            return response(["status"=>404, "message"=>"Failed to get post id : "."$id"] ,404);
        }

        return response(new PostResource($post_model), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'=>'max:255',
            'content'=>'max:1000',
            'image'=>'image|mimes:jpg,png,jpeg,gif,svg|max:20480',
            'category_id'=>'max:500']);

        $post_model = Post::find($id);

        if ($post_model === null) {
            return response(["status"=>404, "message"=>"Failed to get post id : "."$id"] ,404);
        }

        $request_post = $request->all();

        $file = $request->file('image');

        $old_iamge_path = public_path().$post_model->image;


        if ($file){   
            $imageName = time().'.'.$file->extension();

            $imagePath = public_path(). '/files';

            $file->move($imagePath, $imageName);

            $request_post['image'] = '/files/'.$imageName ;

            if (file_exists($old_iamge_path)){
                unlink($old_iamge_path);
            }
        }
        
        $post_model->update($request_post);
        
        return response(new PostResource($post_model), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post_model = Post::find($id);

        if ($post_model === null) {
            return response(["status"=>404, "message"=>"Failed to get post id : $id"] ,404);
        }

        $iamge_path = public_path().$post_model->image;

        if (file_exists($iamge_path)){
            unlink($iamge_path);
        } else {
            return response(["status"=>404, "message"=>"Failed to find post's image"] ,404);
        }


        $post_model->delete();

        return response(["status"=>200, "message"=>"Post $id has been deleted"] ,200);
    }
}
