@extends('layouts.fullscreen')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- <a class="btn btn-success" href="{{ route('home') }}" role="button"><i class="fas fa-home"></i></a> --}}
            <div class="card border-success mt-2">
                <div class="card-header bg-success text-white">
                    @lang('Results')
                    <a class="btn btn-danger btn-sm float-right" href="{{ route('research_pdf', ['research_id' => $research->id]) }}" target="_blank" role="button">PDF</a>
                </div>
                <div class="card-body">
                    <form class="col-12" method="POST" action="{{ route('research') }}" id="form_Questions">
                        @csrf
                        <input type="hidden" name="research_id" id="research_id" value="{{ $research->id }}">
                        <input type="hidden" name="progress_id" id="progress_id" value="{{ $progress->id }}">
                    </form>
                    <div class="col-md-12" id="alerts"></div>
                    <canvas id="myChart" width="400" height="300"></canvas>
                    <hr class="px-0 mx-0">
                    <button type="button" class="btn btn-success" name="btn_Action" value="conclude">@lang('Conclude')</button>
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
        showLoader();
        var URI = "";
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Média"],
                datasets: [{
                    label: ['Teste 1'],
                    data: [{{ $all_result }}],
                    backgroundColor: [
                        'rgb(108, 178, 235)',
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                    ],
                    borderWidth: 1
                },{
                    label: ['Teste 2'],
                    data: [{{ $this_result }}],
                    backgroundColor: [
                        'rgb(214, 79, 76)',
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                animation : {
                    onComplete : function(){
                        if(URI == ""){
                            URI = myChart.toBase64Image();
                            var data = URI.toString();
                            $('#form_Questions').ajaxSubmit({
                                data: {actionType: 'save_graphic', img_data: data},
                                dataType: 'json',
                                success: function(response){
                                    console.log(response.alerts['type']+': '+response.alerts['text']);
                                    hideLoader();
                                }
                            });
                            //document.getElementById('imgChart').src = text;
                            //alert(text);
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });

        function submitForm(actionType, research_again = false){
            showLoader(function(){
                $('#form_Questions').ajaxSubmit({
                    data: {actionType: actionType, again: research_again},
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
            bootbox.confirm({
                size: 'medium',
                title: 'Alerta',
                message: 'Deseja refazer a pesquisa ou finalizar?',
                buttons: {
                    'cancel': {
                        label: 'Refazer',
                        className: 'btn-info pull-left'
                    },
                    'confirm': {
                        label: 'Finalizar',
                        className: 'btn-success pull-right'
                    }
                },
                callback: function(result) {
                    if (result) {
                        submitForm('conclude');
                    }else{
                        submitForm('conclude', true);
                    }
                }
            });
        });
    });
</script>
@endsection
