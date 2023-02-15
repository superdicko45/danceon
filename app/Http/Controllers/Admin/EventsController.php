<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use Validator;

class EventsController extends Controller
{

    /**
     * Regresa los eventos
     *
     * @return Collect eventos
     */
    public function index() {

        $params['eventos'] = DB::table('eventos as e')
            ->join('cat_ambientes as c', 'e.tipo_ambiente_id', 'c.id')
            ->join('organizadores as o', 'e.organizador_id', 'o.id')
            ->leftJoin('evento_regulares as er', 'e.id', 'er.evento_id')
            ->leftJoin('evento_irregulares as eir', 'e.id', 'eir.evento_id')
            ->select(
                'e.*',
                'c.ambiente',
                'o.organizador',
                'er.dia',
                'er.hora_inicio',
                'er.hora_final',
                'er.periodicidad',
                'eir.fecha_inicio',
                'eir.fecha_final',
            )
            ->get();

        return view('admin.modulos.eventos.index')->with($params);
    }

    /**
     * Regresa la vista para crear un nuevo evento
     *
     * @return View
     */
    public function create() {

        $params = $this->getData();
        $usuario = Auth::user();
        $params['organizador'] = DB::table('usuarios')
            ->select('usuario_id', 'username', 'nombre')
            ->where('usuario_id', $usuario->usuario_id)
            ->first();

        return view('admin.modulos.eventos.create')->with($params);
    }

    /**
     * Almacena un nuevo evento
     *
     * @return Bool
     */
    public function store(Request $request) {

        $falla = $this->validaDatos( $request->all() );
        // Si falla alguna validacion regresa error para mostrar en pantalla
        if($falla) return ['error' => true, 'msg' => 'Ocurrio un error'];

        //valida membresia
        $validaMembresia = $this->validaMembresia($request->fecha_inicio);
        if($validaMembresia['error']) return $validaMembresia;

        // nombre del flayer
        $newUrl = null;

        //si existe una imagen actual (desde copiar evento)
        if($request->has('imagen_actual')) $newUrl = $request->imagen_actual;

        //si existe una imagen adjunta
        if($request->hasFile('foto')) {

            $file      = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $newName   = uniqid().'.'.$extension;
            $newUrl    = url('public/images/eventos/' . $newName);

            \Storage::disk('images')->putFileAs('eventos/', $file, $newName);
        }

        $evento_id = DB::table('eventos')->insertGetId([
            'titulo'         => $request->titulo,
            'promocional'    => $newUrl,
            'tipo_evento_id' => $request->tipo_evento,
            'cover'          => $request->cover,
            'preventa'       => $request->preventa,
            'contacto'       => $request->contacto,
            'fecha_inicio'   => $request->fecha_inicio,
            'fecha_final'    => $request->fecha_final,
            'ciudad_id'      => $request->ciudad,
            'colonia'        => $request->colonia,
            'direccion'      => $request->direccion,
            'latitud'        => $request->latitud,
            'longitud'       => $request->longitud,
            'descripcion'    => $request->descripcion,
            'recomendado'    => 1,
            'activo'         => 1,   
            'created_at'     => date("Y-m-d H:i:s"),
            'updated_at'     => date("Y-m-d H:i:s")
        ]);

        //visitas
        DB::table('evento_visitas')->insert(['evento_id' => $evento_id]);

        //generos
        $dataG = [];
        if($request->has('generos')) {
         
            foreach ($request->generos as $key => $value) {

                $dataG[$key] = ['evento_id' => $evento_id, 'genero_id' => $value];
            }
        }    

        if(count($dataG) > 0) DB::table('evento_genero')->insert($dataG);

        //invitados
        $dataI = [];

        if($request->has('invitados')) {

            foreach ($request->invitados as $key => $value) {

              $dataI[$key] = [
                'evento_id'  => $evento_id,
                'usuario_id' => $value,
                'invitado'   => 1
              ];
            }
        }

        if(count($dataI) > 0) DB::table('usuario_eventos')->insert($dataI);

        //organizadores
        $dataO = [];

        if($request->has('organizadores')){

            foreach ($request->organizadores as $key => $value) {

              $dataO[$key] = ['evento_id'  => $evento_id, 'usuario_id' => $value, 'activo' => 1];
            }
        }

        if(count($dataO) > 0) DB::table('evento_organizadores')->insert($dataO);

        return ['error' => false];
    }

    /**
     * Regresa la vista e info para editar un evento
     *
     * @return View
     */
    public function edit($id) {

        $user = Auth::user();

        $evento = DB::table('eventos as e')
            ->select('e.*')
            ->leftJoin('evento_organizadores as o', 'e.evento_id', 'o.evento_id')
            ->where('e.evento_id', $id);

        if($user->tipo_usuario == 2) {
            $evento->where(['o.usuario_id' => $user->usuario_id, 'o.activo' => 1]); 
        }
         
        $evento = $evento->first();

        if($evento == null) abort('404');

        $params = $this->getData();

        $params['evento'] = $evento;

        $params['red'] = DB::table('evento_redes')
            ->select(
                'red_social',
                'red_social_id'
            )
            ->where(['activo'    => 1, 'evento_id' => $id])
            ->first();

        $params['invitados'] = DB::table('usuario_eventos as i')
            ->join('usuarios as u', 'i.usuario_id', 'u.usuario_id')
            ->select(
                'i.usuario_id as id',
                DB::raw("CONCAT(u.nombre, ' - ', u.username) as text")
            )
            ->where([
                'i.evento_id' => $id,
                'i.activo' => 1,
                'i.invitado' => 1
            ])
            ->get();

        $params['egeneros'] = DB::table('evento_genero')
            ->where('evento_id', $id)
            ->where('activo', 1)
            ->pluck('genero_id')
            ->toArray();

        $params['organizadores'] = DB::table('evento_organizadores as o')
            ->join('usuarios as u', 'o.usuario_id', 'u.usuario_id')
            ->select(
                'o.usuario_id as id',
                DB::raw("CONCAT(u.nombre, ' - ', u.username) as text")
            )
            ->where('o.evento_id', $id)
            ->where('o.activo', 1)
            ->get();

        return view('admin.modulos.eventos.edit')->with($params);
    }

    /**
     * Actualiza un evento
     *
     * @return Bool
     */
    public function update(Request $request) {
        
        //valida si el usuario tiene privilegios sobre el evento
        $user = Auth::user();
        $evento = DB::table('eventos as e')
            ->leftJoin('evento_organizadores as o', 'e.evento_id', 'o.evento_id')
            ->where('e.evento_id', $request->evento_id);

        if($user->tipo_usuario == 2) {
            $evento->where(['o.usuario_id' => $user->usuario_id, 'o.activo' => 1]); 
        }
        
        $evento = $evento->first();
        if($evento == null) return ['error' => true, 'msg' => 'No tienes privilegios sobre este evento'];

        $falla = $this->validaDatos( $request->all() );
        // Si falla alguna validacion regresa error para mostrar en pantalla
        if($falla) return ['error' => true, 'msg' => 'Ocurrio un error'];

        //valida membresia
        $validaMembresia = $this->validaMembresia($request->fecha_inicio);
        if($validaMembresia['error']) return $validaMembresia;

        // nombre del flayer
        $newUrl = null;

        //si existe una imagen adjunta
        if($request->hasFile('foto')) {

            $file      = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $newName   = uniqid().'.'.$extension;
            $newUrl    = url('public/images/eventos/' . $newName);

            \Storage::disk('images')->putFileAs('eventos/', $file, $newName);
        }

        $newData = [
            'titulo'         => $request->titulo,
            'tipo_evento_id' => $request->tipo_evento,
            'cover'          => $request->cover,
            'preventa'       => $request->preventa,
            'contacto'       => $request->contacto,
            'fecha_inicio'   => $request->fecha_inicio,
            'fecha_final'    => $request->fecha_final,
            'ciudad_id'      => $request->ciudad,
            'colonia'        => $request->colonia,
            'direccion'      => $request->direccion,
            'latitud'        => $request->latitud,
            'longitud'       => $request->longitud,
            'descripcion'    => $request->descripcion,
            'activo'         => $request->status,
            'updated_at'     => date("Y-m-d H:i:s")
        ];

        if($newUrl != null) $newData['promocional'] = $newUrl;

        DB::table('eventos')
            ->where('evento_id', $request->evento_id)
            ->update($newData);

        ///////////// inicia generos /////////////
        $dataG    = [];
        $dataUpdG = [];

        $cGeneros = DB::table('evento_genero')
            ->where([
                'evento_id' => $request->evento_id,
                'activo'    => 1
            ])
            ->select('evento_genero_id', 'genero_id')
            ->get()
            ->keyBy('genero_id');

        $cGenerosIds = $cGeneros->keys()->toArray();

        //inactiva los generos y despues los vuelve a activar
        DB::table('evento_genero')
            ->where('evento_id', $request->evento_id)
            ->update(['activo' => 0]);
            
        if($request->has('generos')) {

            foreach ($request->generos as $key => $value) {
    
                if(in_array($value, $cGenerosIds))
                    $dataUpdG[] = $cGeneros[$value]->evento_genero_id;
                else
                    $dataG[] = [
                        'evento_id' => $request->evento_id,
                        'genero_id' => $value
                    ];
            }
        }    

        //inserta las nuevas
        if(count($dataG) > 0) DB::table('evento_genero')->insert($dataG);

        //activa las que ya estaban
        if(count($dataUpdG) > 0)
            DB::table('evento_genero')
                ->whereIn('evento_genero_id', $dataUpdG)
                ->update(['activo' => 1]);

        ///////////// termina generos /////////////

        ///////////// inicia invitados /////////////
        $dataI    = [];
        $dataUpdI = [];

        $cInvitados = DB::table('usuario_eventos')
            ->where([
                'evento_id' => $request->evento_id,
                'invitado'  => 1,
                'activo'    => 1
            ])
            ->select('usuario_evento_id', 'usuario_id')
            ->get()
            ->keyBy('usuario_id');

        $cInvitadosIds = $cInvitados->keys()->toArray();

        //inactiva los invitaods y despues los vuelve a activar
        DB::table('usuario_eventos')
            ->where(['evento_id' => $request->evento_id, 'invitado' => 1])
            ->update(['activo' => 0]);

        if($request->has('invitados')) {
            foreach ($request->invitados as $key => $value) {

                if(in_array($value, $cInvitadosIds))
                    $dataUpdI[] = $cInvitados[$value]->usuario_evento_id;
                else
                    $dataI[] = [
                        'evento_id' => $request->evento_id,
                        'usuario_id' => $value,
                        'invitado'   => 1
                    ];
            }
        }

        //inserta las nuevas
        if(count($dataI) > 0) DB::table('usuario_eventos')->insert($dataI);

        //activa las que ya estaban
        if(count($dataUpdI) > 0)
            DB::table('usuario_eventos')
                ->whereIn('usuario_evento_id', $dataUpdI)
                ->update(['activo' => 1]);

        ///////////// termina invitados /////////////

        ///////////// inicia organizadores /////////////
        $dataO    = [];
        $dataUpdO = [];

        $cOrganizadores = DB::table('evento_organizadores')
            ->where(['evento_id' => $request->evento_id, 'activo' => 1])
            ->select('organizador_id', 'usuario_id')
            ->get()
            ->keyBy('usuario_id');

        $cOrganizadoresIds = $cOrganizadores->keys()->toArray();

        //inactiva los organizadores y despues los vuelve a activar
        DB::table('evento_organizadores')
            ->where('evento_id', $request->evento_id)
            ->update(['activo' => 0]);

        if($request->has('organizadores')) {
            foreach ($request->organizadores as $key => $value) {

                if(in_array($value, $cOrganizadoresIds))
                    $dataUpdO[] = $cOrganizadores[$value]->organizador_id;
                else
                    $dataO[] = ['evento_id' => $request->evento_id, 'usuario_id' => $value];
            }
        }

        //inserta las nuevas
        if(count($dataO) > 0) DB::table('evento_organizadores')->insert($dataO);

        //activa las que ya estaban
        if(count($dataUpdO) > 0)
            DB::table('evento_organizadores')
                ->whereIn('organizador_id', $dataUpdO)
                ->update(['activo' => 1]);

        return ['error' => false];
    }

    /**
     * Regresa la vista e info para reciclar un evento
     *
     * @return View
     */
    public function copy($id) {

        $user = Auth::user();

        $evento = DB::table('eventos as e')
            ->leftJoin('evento_organizadores as o', 'e.evento_id', 'o.evento_id')
            ->where('e.evento_id', $id);

        if($user->tipo_usuario == 2) {
            $evento->where(['o.usuario_id' => $user->usuario_id, 'o.activo' => 1]); 
        }
         
        $evento = $evento->first();

        if($evento == null) abort('404');

        $params = $this->getData();

        $params['evento'] = $evento;

        $params['red'] = DB::table('evento_redes')
            ->select(
                'red_social',
                'red_social_id'
            )
            ->where(['activo'    => 1, 'evento_id' => $id])
            ->first();

        $params['invitados'] = DB::table('usuario_eventos as i')
            ->join('usuarios as u', 'i.usuario_id', 'u.usuario_id')
            ->select(
                'i.usuario_id as id',
                DB::raw("CONCAT(u.nombre, ' - ', u.username) as text")
            )
            ->where([
                'i.evento_id' => $id,
                'i.activo' => 1,
                'i.invitado' => 1
            ])
            ->get();

        $params['egeneros'] = DB::table('evento_genero')
            ->where('evento_id', $id)
            ->where('activo', 1)
            ->pluck('genero_id')
            ->toArray();

        $params['organizadores'] = DB::table('evento_organizadores as o')
            ->join('usuarios as u', 'o.usuario_id', 'u.usuario_id')
            ->select(
                'o.usuario_id as id',
                DB::raw("CONCAT(u.nombre, ' - ', u.username) as text")
            )
            ->where('o.evento_id', $id)
            ->where('o.activo', 1)
            ->get();

        return view('admin.modulos.eventos.copy')->with($params);
    }

    /////////// Funciones ///////////

    /**
    * Valida el formulario y regresa un bool para confirmar
    *
    */
    public function validaDatos($input){

        $falla = false;
        $validator = Validator::make($input, [
          'titulo' => 'required',
          'tipo_evento' => 'required',
          'fecha_inicio' => 'required',
          'colonia' => 'required',
          'ciudad' => 'required',
          'direccion' => 'required',
          'longitud' => 'required',
          'latitud' => 'required'
        ]);

        if ($validator->fails()) $falla = true;

        return $falla;
    }

    /**
    * Regresa la info general para crear/editar un evento
    *
    */
    public function getData(){

        $params['ciudades'] = DB::table('cat_ciudades')
            ->select('ciudad_id as id', 'ciudad as text')
            ->where('activo', 1)
            ->orderBy('ciudad')
            ->get();

        $params['generos'] = DB::table('cat_generos')
            ->select('genero_id as id', 'genero as text')
            ->where('activo', 1)
            ->get();

        $params['tipos_eventos'] = DB::table('cat_tipo_evento')
            ->select('tipo_evento_id as id', 'tipo_evento as text')
            ->where('activo', 1)
            ->get();

        $params['redes'] = DB::table('cat_redes_sociales')
            ->select('red_social_id as id', 'red_social as text')
            ->where('activo', 1)
            ->get();

        return $params;
    }
    
    /**
     * Valida limite de membresia
     * @return Array
     */
    public function validaMembresia($fecha_inicio) {

        $user = Auth::user();
        $response = ['error' => false];

        // Si es organizador valida la membresia
        if($user->tipo_usuario == 2){

            $fecha_limite = DB::table('membresias')
                ->where([
                    'activo' => 1,
                    'usuario_id' => $user->usuario_id
                ])
                ->orderBy('fecha_final', 'desc')
                ->pluck('fecha_final')
                ->first();
            
            if($fecha_limite == null) return ['error' => true, 'msg' => 'No tienes una membresia activa, comunicate con el Admin!'];

            if($fecha_inicio > $fecha_limite) return ['error' => true, 'msg' => 'Tu membresia caduca el ' . $fecha_limite . ', tu evento debería ser menor a la fecha'];
        }

        return ['error' => false];
    }
}
