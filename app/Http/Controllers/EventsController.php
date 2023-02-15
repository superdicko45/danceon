<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Enums\Weekdays;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventsController extends Controller
{

    /**
     * Inicio 
     *
     */
    public function home() {

        $params['genres'] = DB::table('cat_generos')
            ->select('genero', 'id')
            ->where('activo', 1)
            ->get();

        $params['vibes'] = DB::table('cat_ambientes')
            ->select('ambientes', 'id')
            ->where('activo', 1)
            ->get();

        $params['weekdays'] = Weekdays::WEEKDAYS;

        dd($params);

        $eventos = DB::table('eventos as e')
            ->join('cat_tipo_evento as c', 'e.tipo_evento_id', 'c.tipo_evento_id')
            ->join('cat_ciudades as cc', 'e.ciudad_id', 'cc.ciudad_id')
            ->select(
                'evento_id',
                'promocional',
                'tipo_evento',
                'cover',
                'titulo',
                'cc.ciudad',
                'colonia',
                'fecha_inicio'
            )
            ->where([
                'e.recomendado' => 1,
                'e.activo' => 1
            ])
            ->where('e.fecha_inicio', '>=' ,$hoy)
            ->orderBy('fecha_inicio')
            ->paginate(10);

        $params['eventos'] = $this->parseDate($eventos);
        $params['search'] = false;

        return view('pages.eventos')->with($params);
    }

    /**
     * Regresa los resultados de la busqueda
     *
     */
    public function search(Request $request) {

        $hoy = date('Y-m-d');

        $params['ciudades'] = DB::table('cat_ciudades')
            ->select(
                'ciudad as item',
                'ciudad_id as item_id'
            )
            ->where('activo', 1)
            ->get();

        $params['generos'] = DB::table('cat_generos')
            ->select(
                'genero as item',
                'genero_id as item_id'
            )
            ->where('activo', 1)
            ->get();

        $query = DB::table('eventos as e')
            ->join('cat_tipo_evento as c', 'e.tipo_evento_id', 'c.tipo_evento_id')
            ->join('cat_ciudades as cc', 'e.ciudad_id', 'cc.ciudad_id')
            ->leftJoin('evento_genero as cg', 'e.evento_id', 'cg.evento_id')
            ->where('e.activo', 1)
            ->where('e.fecha_inicio', '>=' ,$hoy)
            ->groupBy('evento_id');

        if($request->q != null && $request->q != '') {

            $q = '';
            $list = explode(" ", $request->q);

            foreach($list as $value){

                $value = preg_replace('/[+\-><\(\)~*\"@]+/', '* ', $value);
                if(strlen($value) > 3) $q .= $value . "* ";
            }

            $q = str_replace(" *", "", $q);
            $q = substr($q, 0, -1);

            //$query->where('titulo', 'like', '%'.$request->q.'%');
            $raw = "MATCH (titulo, colonia, descripcion) AGAINST ('" . $q . "' IN BOOLEAN MODE)";

            $query->select(
                'e.evento_id',
                'promocional',
                'tipo_evento',
                'cover',
                'titulo',
                'cc.ciudad',
                'colonia',
                'fecha_inicio',
                DB::raw($raw . ' as score')
            )
            ->whereRaw($raw)
            ->orderBy('score', 'desc');
        } else {

            $query->select(
                'e.evento_id',
                'promocional',
                'tipo_evento',
                'cover',
                'titulo',
                'cc.ciudad',
                'colonia',
                'fecha_inicio'
            )
            ->orderBy('fecha_inicio');
        }

        if($request->ciudad != null && $request->ciudad != '')
            $query->where('e.ciudad_id', $request->ciudad);

        if($request->genero != null && $request->genero != '')
            $query->where([
                'cg.genero_id' => $request->genero,
                'cg.activo' => 1
            ]);

        $eventos = $query->paginate(10);
        $params['eventos'] = $this->parseDate($eventos);

        $params['search'] = true;
        $params['q'] = $request->q;

        $params['ciudad'] = $params['ciudades']
            ->firstWhere('item_id', $request->ciudad);

        $params['genero'] = $params['generos']
            ->firstWhere('item_id', $request->genero);

        return view('pages.eventos')->with($params);
    }

    /**
     * Regresa la info del evento
     *
     * @return evento
     */
    public function show($id) {

        $hoy = date('Y-m-d');

        $params['evento'] = DB::table('eventos as e')
            ->join('cat_tipo_evento as c', 'e.tipo_evento_id', 'c.tipo_evento_id')
            ->join('cat_ciudades as cc', 'e.ciudad_id', 'cc.ciudad_id')
            ->select(
                'e.*',
                'tipo_evento',
                'cc.ciudad',
                'fecha_inicio'
            )
            ->where([
                'evento_id' => $id,
                'e.activo' => 1
            ])
            ->first();

        if($params['evento'] != null){

            //actualiza las viistas del evento
            DB::table('evento_visitas')
                ->where('evento_id', $id)
                ->update(['visitas' => DB::raw('visitas + 1')]);

            $params['evento']->fecha = Carbon::parse($params['evento']->fecha_inicio, 'UTC')
                ->isoFormat('dddd Do [de] MMMM YYYY');

            $params['redes'] = DB::table('evento_redes as e')
                ->join('cat_redes_sociales as c', 'e.red_social_id', 'c.red_social_id')
                ->select(
                      'c.red_social',
                      DB::raw('CONCAT(c.url, e.red_social) as url')
                )
                ->where([
                    'e.activo'    => 1,
                    'c.activo'    => 1,
                    'e.evento_id' => $id
                ])
                ->get();

            $params['invitados'] = DB::table('usuario_eventos as i')
                ->join('usuarios as u', 'i.usuario_id', 'u.usuario_id')
                ->join('cat_tipo_usuario as c', 'u.tipo_id', 'c.tipo_usuario_id')
                ->select(
                    'i.usuario_id',
                    'u.nombre',
                    'u.foto_perfil',
                    'c.tipo_usuario'
                )
                ->where([
                    'i.evento_id' => $id,
                    'i.activo' => 1,
                    'i.invitado' => 1
                ])
                ->get();

            $params['generos'] = DB::table('cat_generos as g')
                ->join('evento_genero as e', 'g.genero_id', 'e.genero_id')
                ->select(
                    'g.genero',
                    'g.genero_id'
                )
                ->where('e.evento_id', $id)
                ->where('e.activo', 1)
                ->get();

            $params['organizadores'] = DB::table('evento_organizadores as o')
                ->join('usuarios as u', 'o.usuario_id', 'u.usuario_id')
                ->join('cat_tipo_usuario as c', 'u.tipo_id', 'c.tipo_usuario_id')
                ->select(
                    'o.usuario_id',
                    'u.nombre',
                    'u.foto_perfil',
                    'c.tipo_usuario'
                )
                ->where('o.evento_id', $id)
                ->where('o.activo', 1)
                ->get();

            $params['galeria_evento'] = DB::table('evento_galerias as g')
                ->join('eventos as e', 'g.evento_id', 'e.evento_id')
                ->join('cat_galeria as c', 'g.galeria_id', 'c.galeria_id')
                ->select(
                    'e.evento_id',
                    'e.titulo',
                    'c.archivo_foto'
                )
                ->where('g.evento_id', $id)
                ->where('g.activo', 1)
                ->get();
        }

        $eventos = DB::table('eventos')
            ->select(
                'evento_id',
                'promocional',
                'titulo',
                'fecha_inicio'
            )
            ->where([
                'recomendado' => 1,
                'activo' => 1
            ])
            ->where('fecha_inicio', '>=' ,$hoy)
            ->limit(4)
            ->get();

        $params['eventos'] = $this->parseDate($eventos);

        return view('pages.showEvento')->with($params);
    }
    //////// funciones ////////
    /**
    * Regresa la fecha en español de cada evento
    *
    * @param Collect eventos
    * @return Collect eventos
    */
    public function parseDate($eventos){

        foreach ($eventos as $key => $value) {

            $value->fecha = Carbon::parse($value->fecha_inicio, 'UTC')
                ->isoFormat('Do MMMM YYYY');
        }

        return $eventos;
    }
}
