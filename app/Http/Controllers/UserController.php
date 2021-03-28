<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($search = null){
        if(!empty($search)){
            $users = User::where('nick', 'LIKE', '%'.$search.'%')
                            ->orwhere('name', 'LIKE', '%'.$search.'%')
                            ->orwhere('surname', 'LIKE', '%'.$search.'%')
                            ->orderBy('id','desc')
                            ->paginate(5);
            // var_dump($users);
            // die();
        }else{
            $users = User::orderBy('id', 'desc')->paginate(5);
        }
        
        return view('user.index', [
            'users' => $users
        ]);
    }

    public function config(){
        return view('user.config');
    }

    public function update(UpdateUserRequest $request){

        // Conseguir el usuario identificado
         $user = Auth::user();
        // $id = $user->id;

        // Validar los datos del formulario
        // $validate = $this->validate($request, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'role_id' => ['required', 'string', 'max:255'],
        //     'surname' => ['required', 'string', 'max:255'],
        //     'nick' => ['required', 'string', 'max:255', 'unique:users,nick,'.$id],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
        // ]);

        // Recoger los datos del formulario
        $name = $request->input('name');
        $surname = $request->input('surname');
        $nick = $request->input('nick');
        $email = $request->input('email');
        $role_id=$request->input('role_id');

        // Asignar nuevos valores al objeto del usuario (setear los nuevos datos al objeto)
        $user->name = $name;
        $user->surname = $surname;
        $user->nick = $nick;
        $user->email = $email;
        $user->role_id = (int)$role_id;

        // Subir la imagen
        $image_path = $request->file('image_path');

        if($image_path){

            // Poner nombre único a la imagen
            $image_path_name = time().$image_path->getClientOriginalName();

            // Guardar en la carpeta storage (app/users)
            Storage::disk('users')->put($image_path_name, File::get($image_path));
           
            // Seteo el nombre de la imagen en el objeto
            $user->image = $image_path_name;
        }
        

        // Ejecutar consulta y cambios en la base de datos
        $user->update();

        return redirect()->route('config')->with(['message'=>'Usuario actualizado correctamente']);
        
    }

    public function getImage($filename){

        $file = Storage::disk('users')->get($filename);
        return new Response($file, 200);

    }

    public function profile($id){
        $user = User::findOrFail($id);

        return view('user.profile',[
            'user' => $user
        ]);
    }
}
