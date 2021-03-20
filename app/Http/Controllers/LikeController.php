<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        
    }

    public function index(){
        $user = Auth::user();
        $likes = Like::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(5);

        return view('like.index', [
            'likes' => $likes
        ]);
    }

    public function like($image_id){
        //Recoger datos del usuario y la imagen
        $user = Auth::user();

        //Condición para ver si ya existe el like y no duplicarlo
        $isset_like = Like::where('user_id', $user->id)
                            ->where('image_id', $image_id)
                            ->count();
        //var_dump($isset_like);
        if ($isset_like == 0) {
            $like = new Like();
            $like->user_id = $user->id;
            $like->image_id = (int)$image_id;

            //Guardar
            $like->save();

            return response()->json([
                'like' => $like
            ]);
        }else{
            return response()->json([
                'message' => 'El like ya existe'
            ]);
        }
        
        
        //var_dump($like);
    }
    
    public function dislike($image_id){
        //Recoger datos del usuario y la imagen
        $user = Auth::user();

        //Condición para ver si ya existe el like y no duplicarlo
        $like = Like::where('user_id', $user->id)
                            ->where('image_id', $image_id)
                            ->first();
        //var_dump($isset_like);
        if ($like) {
            //Delete like
            $like->delete();

            return response()->json([
                'like' => $like,
                'messaje' => 'Has dado dislike correctamente'
            ]);
        }else{
            return response()->json([
                'message' => 'El like no existe'
            ]);
        }
    }


}
