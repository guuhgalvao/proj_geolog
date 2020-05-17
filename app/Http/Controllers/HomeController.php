<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Research;
use App\Models\ResearchesProgress;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $progress = ResearchesProgress::firstOrNew(['user_id' => Auth::id()]);
        // if($progress->save()){
        //     if($progress->status == 1){
        //         if($progress->answered < Question::count()){
        //             $question = Question::where('sequence', $progress->answered+1)->first();
        //         }
        //     }
        // }
        // if(isset($question)){

        // }else{
        //     return view('finished');
        // }
        return view('home', ['researches' => Research::all()]);
    }

    public function actions(Request $request){
        switch($request->actionType){
            case 'next':
                $answer = new Answer();
                $answer->user_id = Auth::id();
                $answer->question_id = $request->question_id;
                $answer->value = $request->answer;
                if($answer->save()){
                    $progress = ResearchesProgress::where('user_id', Auth::id())->first();
                    $progress->answered = $request->question_id;
                    if($progress->answered == Question::count()){
                        $progress->status = 2;
                    }
                    if($progress->save()){
                        return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Salvo']];
                    }else{
                        return ['error' => true, 'alerts' => ['type' => 'success', 'text' => 'Falha ao salvar']];
                    }
                }else{
                    return ['error' => true, 'alerts' => ['type' => 'success', 'text' => 'Falha ao salvar']];
                }
                break;

            case 'finish':
                if(isset($request->answer) && !empty($request->answer)){
                    $answer = new Answer();
                    $answer->user_id = Auth::id();
                    $answer->question_id = $request->question_id;
                    $answer->value = $request->answer;
                    $answer->save();
                }
                $progress = ResearchesProgress::where('user_id', Auth::id())->first();
                $progress->answered = $request->question_id;
                $progress->status = 2;
                if($progress->save()){
                    return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Finalizado']];
                }else{
                    return ['error' => true, 'alerts' => ['type' => 'success', 'text' => 'Falha ao salvar']];
                }
                break;

            default:
                return ['error' => true, 'alerts' => ['type' => 'danger', 'text' => 'Tipo não encontrado']];
                break;
        }
    }
}
