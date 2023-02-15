<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{

    /**
     * Inicio
     *
     * @return dashboard view
     */
    public function home() {

        $tipo = \Auth::user()->tipo_usuario;

        if($tipo == 1) {

            $params['items'] = [
                [
                    'titulo' => 'Eventos',
                    'icono'  => 'fa-calendar',
                    'url'    => 'admin/eventos'
                ],
                /*[
                    'titulo' => 'Blogs',
                    'icono'  => 'fa-commenting',
                    'url'    => 'admin/blogs'
                ],
                [
                    'titulo' => 'Usuarios',
                    'icono'  => 'fa-group',
                    'url'    => 'admin/usuarios'
                ],
                [
                    'titulo' => 'Academias',
                    'icono'  => 'fa-bank',
                    'url'    => 'admin/academias'
                ],
                [
                    'titulo' => 'Marcas',
                    'icono'  => 'fa-cubes',
                    'url'    => 'admin/marcas'
                ],
                [
                    'titulo' => 'Enlaces',
                    'icono'  => 'fa-link',
                    'url'    => 'admin/enlaces'
                ],*/
                [
                    'titulo' => 'Ayuda',
                    'icono'  => 'fa-question-circle',
                    'url'    => 'contacto'
                ]
            ];
        } else {

            $params['items'] = [
                [
                    'titulo' => 'Eventos',
                    'icono'  => 'fa-calendar',
                    'url'    => 'admin/eventos'
                ],
                [
                    'titulo' => 'Academias',
                    'icono'  => 'fa-bank',
                    'url'    => 'admin/academias'
                ],
                [
                    'titulo' => 'Ayuda',
                    'icono'  => 'fa-question-circle',
                    'url'    => 'contacto'
                ]
            ];
        }


        return view('admin.main')->with($params);
    }

}
