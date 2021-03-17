<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function save(Request $request){

        // Validacion de datos
        $validate = $this->validate($request,[
            'image_id' => 'integer|required',
            'content' => 'string|required'
        ]);

        //Recoger datos formulario
        $user = Auth::user();
        $image_id = $request->input('image_id');
        $content = $request->input('content');

        //Asigno los valores a mi nuevo objeto a guardar
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->image_id = $image_id;
        $comment->content = $content;

        //Guardar en la base de datos
        $comment->save();

        //Redirecciòn
        return redirect()->route('image.detail',['id' => $image_id])->with(['message' => 'Has publicado tu comentario correctamente!!!!']);

    }

    public function delete($id){
        //Conseguir datos del usuario logueado
        $user = Auth::user();

        //Conseguir objeto del comentario
        $comment = Comment::find($id);
        // var_dump($comment);
        // die();

        //Comprobar si soy el dueño del comentario o de la publicación
        if($user && ($comment->user_id == $user->id || $comment->image->user_id == $user->id)){
            $comment->delete();

            return redirect()->route('image.detail', ['id' => $comment->image_id])->with(['message' => 'Comentario eliminado correctamente']);
        }else{
            return redirect()->route('image.detail', ['id' => $comment->image->user_id])->with(['message' => 'El Comentario no se ha eliminado']);
        }
    }
}
