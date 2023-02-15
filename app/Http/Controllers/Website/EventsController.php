<?php

namespace App\Http\Controllers\Website;

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
            ->select('ambiente', 'id')
            ->where('activo', 1)
            ->get();

        $params['weekdays'] = Weekdays::WEEKDAYS;

        $eventos = DB::table('eventos as e')
            ->join('cat_ambientes as c', 'e.tipo_ambiente_id', 'c.id')
            ->leftJoin('evento_regulares as er', 'e.id', 'er.evento_id')
            ->leftJoin('evento_irregulares as eir', 'e.id', 'eir.evento_id')
            ->select(
                'e.*',
                'er.dia',
                'er.hora_inicio',
                'er.hora_final',
                'er.periodicidad',
                'eir.fecha_inicio',
                'eir.fecha_final',
            )
            ->where('e.activo', 1)
            ->get();


        $params['eventos'] = $this->appendTags($eventos);

        dd($params);    
    }

    /**
     * Regresa los eventos con tags de musica
     *
     * @return collect eventos
     */
    public function appendTags($eventos) {

        $eventosIds = $eventos->pluck('id')->toArray();

        $generos = DB::table('evento_generos as e')
            ->join('cat_generos as c', 'e.genero_id', 'c.id')
            ->select('c.id', 'c.genero', 'e.evento_id')
            ->where('e.activo', 1)
            ->whereIn('e.evento_id', $eventosIds)
            ->get()
            ->groupBy('evento_id');

        foreach ($eventos as $key => $value) {

            $value->tags = $generos->get($value->id);
        }

        return $eventos;
    }
}
