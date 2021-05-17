<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articleies = article::orderBy('created_at','desc')->paginate(5);
        return response()->json($articleies, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'image' => 'required|image|mimes:jpeg,png,jpg'
        ]);
        
            $articles = new Article();
            $articles->username = $request->username;
            $articles->title = $request->title;

            $path = $request->file('image')->store('articleies_images');

            $articles->image = $path;

            if($articles->save()) {
                return response()->json($articles, 200);
            } else {
                return response()->json([
                    'message'=> '오류가 발생했습니다. 다시 시도하십시오.',
                    'status_code' => 500
                ], 500);
            }
        }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $article = Article::whereId($id)->first();

        $request->validate([
            'title' => 'required|min:3',
        ]);

        $article->username = $request->title;
        $article->title = $request->title;
        $oldPath = $article->image;
        if($request->hasFile('image')) {
            $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg',
            ]);
            $path = $request->file('image')->store('articleies_images');
            $article->image = $path;

            Storage::delete($oldPath);
        }
        else{
            $article->image = $oldPath;
        }

        // $request->validate([
        //     'image' => 'image|mimes:jpeg,png,jpg',
        // ]);
        // $path = $request->file('image')->store('articleies_images');
        // $article->image = $path;

        // Storage::delete($oldPath);
        
        $update = Article::whereId($id)->update([
            'title'=>$article->title,
            'image'=>$article->image,
            ]);
            // if($up) {
        return response()->json($update, 200);
        // } else {
        //     Storage::delete($path);
        //     return response()->json([
        //         'message' => '오류가 발생했습니다. 다시 시도해 보십시오!',
        //         'status_code' => 500
        //     ], 500);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($article);
        Article::whereId($id)->delete();
        // $art = $article->get();
        // if($article->delete()) {
        //     Storage::delete($article->name);

        //     return response()->json([
        //         'message' => 'Article deleted successfully!',
        //         'status_code' => 200
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'message' => 'Some error occurred, please try again',
        //         'status_code' => 500
        //     ], 500);
        // }
    }
}
