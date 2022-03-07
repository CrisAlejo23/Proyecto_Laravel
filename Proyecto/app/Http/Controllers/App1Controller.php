<?php

namespace App\Http\Controllers;

use App\Models\App1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
//use Intervention\Image\Facades\Image;

class App1Controller extends Controller
{
    //CONTRUCTOR
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
        $userApp1 = Auth::user()->userApp1;
        return view('proyecto.index')->with('userApp1',$userApp1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //CONSULTAR LOS DEPARTAMENTOS
        $departamentos =DB::table('app2s')->get()->pluck('nombre','id');//->dd();
        return view('proyecto.create')->with('departamentos',$departamentos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //VARDUMP
        //dd($request->all());
        //para validar
        $data=request()->validate([
            'nombre' => 'required|min:6',
            'direccion' => 'required',
            'telefono' => 'digits:10|required|min:9',
            'correo' => 'required',
            'departamentos' => 'required',
            'descripcion' => 'required'

        ]);
        dd($request['imagen']->store('upload-proyecto','public'));
        $ruta_imagen = $request['imagen']->store('upload-app1', 'public');

           //======================================
        //COMENTAR
        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000, 500);
        //Guardar la imagen
        $img->save();
        //======================================

        //AGREGAR a la base
        DB::table('app1s')->insert([
            //NOMBRE CAMPOS BBDD --> NOMBRE CAMPO VISTA
            'nombre'=>$data['nombre'],
            'direccion'=>$data['direccion'],
            'telefono'=>$data['telefono'] ,
            'correo'=>$data['correo'] ,
            'descripcion'=>$data['descripcion'],
            'user_id'=> Auth::user()->id,
            'departamentos_id'=>$data['departamentos'],
            'imagen'=>$ruta_imagen
            
        ]);

        

        //REDIRECCIONA
        return redirect()->action([App1Controller::class,'index']);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\App1  $app1
     * @return \Illuminate\Http\Response
     */
    public function show(App1 $app1)
    {
        return view('proyecto.show')->with('app1', $app1);
        //return $app1;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\App1  $app1
     * @return \Illuminate\Http\Response
     */
    public function edit(App1 $app1)
    {
        return $app1;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\App1  $app1
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, App1 $app1)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\App1  $app1
     * @return \Illuminate\Http\Response
     */
    public function destroy(App1 $app1)
    {
        //
    }
}
