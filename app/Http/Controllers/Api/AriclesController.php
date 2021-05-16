<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Validator;

class AriclesController extends Controller
{
    public function showArticles(){
        // return Article::all();
        $articles = Article::all();
        return response()->json($articles);
    }
    public function showArticle($id){
        // return $id;

        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                "status"=>false,
                "message"=>"Post Not Found!"

            ])->setStatusCode(404,'Post not found!');
        }
        return response()->json($article);
        // $articles = Article::all();
        // return response()->json($articles);
    }

    public function storeAricle(Request $request){
        
        $request_data = $request->only(['title','content']);
//создание влидации и обязательности заполнения полей
        $validator = Validator::make($request_data,[ 
            "title"=>['required','string'],
            "content"=>['required','string']
        ]);

        // dd($validator->fails()); проверка на ошибки
            if ($validator->fails()) {
                return response()->json([
                    "status"=>false,
                    "errors" => $validator->messages()
                ])->setStatusCode(422);
            }

          $article =  Article::create([
            "title"=>$request->title,
            "content"=>$request->content
        ]);
        return response()->json([
            "status"=>true,
            "article"=>$article
        ])->setStatusCode(201,'Created article');
    }

    public function deleteArticle($id){
        $article = Article::find($id);
        if (!$article) {
            return response()->json([
                "status"=>false,
                "message"=>"Post Not Found!"
            ])->setStatusCode(404,'Post not found!');
    }
    $article->delete();
    return response()->json([
        "status"=>true,
        "message"=>"Article is delete"
    ])->setStatusCode(200,"Article is delete");
}
}
