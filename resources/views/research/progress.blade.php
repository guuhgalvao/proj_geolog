@extends('layouts.fullscreen')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">@lang('Question Form')</div>
                <div class="card-body">
                    <div class="col-md-12" id="alerts"></div>
                    <form class="col-12" method="POST" action="{{ route('research') }}" id="form_Questions">
                        @csrf
                        <input type="hidden" name="research_id" id="research_id" value="{{ $research->id }}">
                        <input type="hidden" name="progress_id" id="progress_id" value="{{ $progress->id }}">
                        <input type="hidden" name="question_id" id="question_id" value="{{ $question->id }}">
                        <p>{{ $question->text }}</p>
                        <div class="form-group row">
                            <div class="col-md-12">
                                @foreach ($question->options as $option)
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="answer{{ $option->id }}" name="answer" class="custom-control-input" value="{{ $option->value }}">
                                        <label class="custom-control-label" for="answer{{ $option->id }}">{{ $option->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" name="btn_Action" value="finish">@lang('Finish')</button>
                        <button type="button" class="btn btn-success" name="btn_Action" value="next">@lang('Next')</button>
                    </form>
                </div>
                {{-- <div class="card-footer text-right"></div> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function($){
            function submitForm(actionType){
                showLoader(function(){
                    $('#form_Questions').ajaxSubmit({
                        data: {actionType: actionType},
                        dataType: 'json',
                        success: function(response){
                            if(!Boolean(response.error)){
                                // $('#alerts').append('<div class="alert alert-'+response.alerts['type']+' alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+response.alerts['text']+'</div>');
                                if(!$.isEmptyObject(response.redirect)){
                                    window.location.href = response.redirect;
                                }else{
                                    window.location.reload();
                                }
                            }else{
                                $('#alerts').append('<div class="alert alert-'+response.alerts['type']+' alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+response.alerts['text']+'</div>');
                            }
                            hideLoader();
                        }
                    });
                });
            }

            $('button[name=btn_Action]').on('click', function(){
                $('#alerts div.alert').remove();
                submitForm($(this).val());
            });
        });
    </script>
@endsection
