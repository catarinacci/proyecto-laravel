<?php

namespace App\Http\Controllers;

use App\Image;
use App\Comment;
use App\Http\Requests\SaveImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Like;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(){
        return view('image.create');
    }

    public function save(SaveImageRequest $request){
        
        // Validación
        // $validate = $this->validate($request, [
        //     'image_path' => ['required', 'image'],
        //     'description' => ['required'],
            
        // ]);
        
        // Recoger datos
        $image_path = $request->file('image_path');
        $description = $request->input('description');
        
        // Asignar valores nuevos al objeto
        $user = Auth::user();
        $image = new Image();
        $image->user_id = $user->id;
        
        $image->description = $description;
        // var_dump($image_path);
        // die();

        // Subir fichero
        if($image_path){
            $image_path_name = time().$image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            $image->image_path = $image_path_name;
        }

        $image->save();
        return redirect()->route('home')->with(['message' => 'La foto ha sido subida correctamente']);
    }

    public function getImage($filename){
        $file = Storage::disk('images')->get($filename);
        return new Response($file, 200);
    }

    public function detail($id){
        $image = Image::find($id);
        $user = Auth::user();
        // var_dump($image);
        // die();
        return view('image.detail',[
            'image' => $image,
            'user'  => $user
        ]);
    }

    public function delete($id){
        $user = Auth::user();
        $image = Image::find($id);
        $comments = Comment::where('image_id', $id)->get();
        $likes = Like::where('image_id', $id)->get();
        // var_dump($user);
        // var_dump($image);
        
        if($user && $image ){
            if($image->user->id == $user->id || $user->role_id == 1){
                //Eliminar comentarios
                if($comments && count($comments) >= 1){
                    foreach($comments as $comment ){
                        $comment->delete();
                    }
                }

                //Eliminar los likes
                if($likes && count($likes) >= 1){
                    foreach($likes as $like ){
                        $like->delete();
                    }
                }

                //Eliminar fichero de imagen
                Storage::disk('images')->delete($image->image_path);

                //Eliminar registro imagen
                 $image->delete();
                // var_dump($id);
                // die();
                // DB::table('images')->delete((int)$id);
                $message = array('message' => 'La imagen se ha borrado correctamente');
            }
        }else{
            $message = array('message' => 'La imagen no se ha borrado');
        }
        return redirect()->route('home')->with($message);
    }

    public function edit($id){
        $user = Auth::user();
        $image = Image::find($id);

        if($user && $image && $image->user->id == $user->id){
            return view('image.edit',[
                'image' => $image
            ]);
        }else{
            return redirect()->route('home');
        }
    }

    public function update(UpdateImageRequest $request){

        // Validación
        $validate = $this->validate($request, [
            'image_path' => ['image'],
            'description' => ['required'],
            
        ]);
        //Recoger datos
        $image_id = $request->input('image_id');
        $description = $request->input('description');
        $image_path = $request->file('image_path');

        //Conseguir el objeto image y setearlo
        $image = Image::find($image_id);
        $image->description = $description;

        // Subir fichero
        if($image_path){
            $image_path_name = time().$image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            $image->image_path = $image_path_name;
        }

        //Actualizar registro
        $image->update();

        return redirect()->route('image.detail', ['id' => $image_id])
                        ->with(['message' => 'Imagen actualizada con éxito']);

        // var_dump($image_id);
        // var_dump($description);
        // die();
    }
}
