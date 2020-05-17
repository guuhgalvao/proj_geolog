<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sector;

class SectorsController extends Controller
{
    public function index(){
        return view('management.sectors.index');
    }

    public function actions(Request $request){
        switch($request->actionType){
            case 'add':
                if(Sector::create($request->all())){
                    return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Adicionado']];
                }else{
                    return ['error' => true, 'alerts' => ['type' => 'danger', 'text' => 'Não adicionado']];
                }
                break;

            case 'consult':
                return view('management.sectors.list', ['sectors' => Sector::paginate(5)]);
                break;

            case 'show':
                if($sector = Sector::find($request->id)){
                    return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Recuperado'], 'sector' => $sector];
                }else{
                    return ['error' => true, 'alerts' => ['type' => 'success', 'text' => 'Não encontrado']];
                }
                break;

            case 'update':
                if(Sector::find($request->id)->update($request->all())){
                    return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Atualizado']];
                }else{
                    return ['error' => true, 'alerts' => ['type' => 'danger', 'text' => 'Não atualizado']];
                }
                break;

            case 'remove':
                if(Sector::destroy($request->id)){
                    return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Removido']];
                }else{
                    return ['error' => true, 'alerts' => ['type' => 'danger', 'text' => 'Não removido']];
                }
                break;

            default:
                return ['error' => true, 'alerts' => ['type' => 'danger', 'text' => 'Tipo não encontrado']];
                break;
        }
    }
}
