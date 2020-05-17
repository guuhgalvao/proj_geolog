<?php

namespace App\Http\Controllers\Researches;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Research;
use App\Models\ResearchesProgress;
use App\Models\Question;
use App\Models\Answer;
use Auth;
use Storage;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        if($research = Research::find($request->research_id)){
            $progress = ResearchesProgress::firstOrNew(['user_id' => Auth::id(), 'research_id' => $request->research_id, 'status' => 1]);
            if(empty($progress->sequence)){
                $progress->sequence = 1;
            }
            if($progress->save()){
                if($progress->sequence <= Question::where('research_id', $request->research_id)->count()){
                    $question = Question::where('research_id', $research->id)->where('sequence', $progress->sequence)->first();
                    return view('research.progress', ['research' => $research, 'progress' => $progress, 'question' => $question]);
                }else{
                    return redirect()->route('research_result', ['research_id' => $request->research_id]);
                }
            }else{
                return redirect()->route('home');
            }
        }else{
            return redirect()->route('home');
        }
    }

    public function result(Request $request)
    {
        if($research = Research::find($request->research_id)){
            $progress = ResearchesProgress::where('user_id', Auth::id())->where('research_id', $request->research_id)->where('status', 2)->orderBy('id', 'desc')->first();
            $questions = Question::where('research_id', $research->id)->count();
            $all_result = 0;
            $progresses_count = 0;
            foreach(ResearchesProgress::where('user_id', Auth::id())->where('research_id', $request->research_id)->whereIn('status', [2, 3])->orderBy('id', 'desc')->get() as $research_progress){
                $result_per_progress = 0;
                foreach(Answer::where('progress_id', $research_progress->id)->get() as $answer){
                    $result_per_progress += $answer->value/10;
                }
                $all_result += $result_per_progress/$questions;
                $progresses_count++;
            }
            $all_result = $all_result/$progresses_count;
            $this_result = 0;
            foreach(Answer::where('progress_id', $progress->id)->get() as $answer){
                $this_result += $answer->value/10;
            }
            return view('research.result', ['research' => $research, 'progress' => $progress, 'this_result' => $this_result/$questions, 'all_result' => $all_result]);
        }else{
            return redirect()->route('home');
        }
    }

    public function pdf(Request $request){
        if($research = Research::find($request->research_id)){
            $progress = ResearchesProgress::where('user_id', Auth::id())->where('research_id', $request->research_id)->where('status', 2)->orderBy('id', 'desc')->first();
            $filename = date('Ymd').$progress->id;
            //Storage::put("public/researches/$research->id/".Auth::id()."/$filename.html", view('research.pdf', ['img_path' => $progress->img_path])->render());
            //return \PDF::loadFile(public_path().Storage::url("public/researches/$research->id/".Auth::id()."/$filename.html"))->setOptions(['isHtml5ParserEnabled' => true])->stream();
            return \PDF::loadView('research.pdf', ['img_path' => $progress->img_path])->setOptions(['isPhpEnabled' => true, 'isHtml5ParserEnabled' => true, 'isRemoteEnable' => true])->stream();
        }else{
            return redirect()->route('home');
        }
    }

    public function actions(Request $request){
        switch($request->actionType){
            case 'next':
                $progress = ResearchesProgress::find($request->progress_id);
                $answer = new Answer();
                $answer->progress_id = $progress->id;
                $answer->question_id = $request->question_id;
                $answer->value = $request->answer;
                if($answer->save()){
                    if($progress->sequence == Question::where('research_id', $request->research_id)->count()){
                        $progress->status = 2;
                    }else{
                        $progress->sequence++;
                    }
                    if($progress->save()){
                        if($progress->status == 2){
                            return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Salvo'], 'redirect' => url()->route('research_result', ['research_id' => $request->research_id])];
                        }else{
                            return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Salvo']];
                        }
                    }else{
                        return ['error' => true, 'alerts' => ['type' => 'success', 'text' => 'Falha ao salvar']];
                    }
                }else{
                    return ['error' => true, 'alerts' => ['type' => 'success', 'text' => 'Falha ao salvar']];
                }
                break;

            case 'finish':
                $progress = ResearchesProgress::find($request->progress_id);
                $progress->status = 2;
                if(isset($request->answer) && !empty($request->answer)){
                    $answer = new Answer();
                    $answer->progress_id = $progress->id;
                    $answer->question_id = $request->question_id;
                    $answer->value = $request->answer;
                    $answer->save();
                    if($progress->sequence < Question::where('research_id', $request->research_id)->count()){
                        $progress->sequence++;
                    }
                }
                if($progress->save()){
                    return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Finalizado'], 'redirect' => url()->route('research_result', ['research_id' => $request->research_id])];
                }else{
                    return ['error' => true, 'alerts' => ['type' => 'success', 'text' => 'Falha ao salvar']];
                }
                break;

            case 'conclude':
                $progress = ResearchesProgress::find($request->progress_id);
                $progress->status = 3;
                if($progress->save()){
                    if(isset($request->again) && $request->again == 'true'){
                        return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Finalizado (R)'], 'redirect' => url()->route('research_progress', ['research_id' => $request->research_id])];
                    }else{
                        return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Finalizado (F)'], 'redirect' => url()->route('home')];
                    }
                }else{
                    return ['error' => true, 'alerts' => ['type' => 'success', 'text' => 'Falha ao salvar']];
                }
                break;

            case 'save_graphic':
                $research = Research::find($request->research_id);
                $progress = ResearchesProgress::where('user_id', Auth::id())->where('research_id', $request->research_id)->where('status', 2)->orderBy('id', 'desc')->first();
                $img_data = $request->img_data;
                $progress->img_path = empty($progress->img_path) ? "public/researches/$research->id/".Auth::id()."/image_".time().".png" : $progress->img_path; //generating unique file name;
                @list($type, $img_data) = explode(';', $img_data);
                @list(, $img_data) = explode(',', $img_data);
                if($img_data != ""){
                    if(Storage::put($progress->img_path, base64_decode($img_data))){
                        if($progress->save()){
                            return ['error' => false, 'alerts' => ['type' => 'success', 'text' => 'Imagem salva']];
                        }else{
                            return ['error' => true, 'alerts' => ['type' => 'success', 'text' => 'Falha ao salvar']];
                        }
                    }else{
                        return ['error' => true, 'alerts' => ['type' => 'success', 'text' => 'Falha ao salvar imagem']];
                    }
                }else{
                    return ['error' => true, 'alerts' => ['type' => 'success', 'text' => 'Dados não enviados']];
                }
                break;

            default:
                return ['error' => true, 'alerts' => ['type' => 'danger', 'text' => 'Tipo não encontrado']];
                break;
        }
    }
}
