@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">@lang('Manage Users')</div>
                <div class="card-body">
                    <div class="col-12">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-md-12" id="alerts"></div>
                                    <form class="col-12" method="POST" action="{{ route('users') }}" id="form_Management">
                                        @csrf
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="name">@lang('Name')</label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="@lang('Name')">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="email">@lang('E-mail')</label>
                                                <input type="text" class="form-control" name="email" id="email" placeholder="exemplo@exemplo.com">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="user_type">@lang('Type')</label>
                                                <select class="form-control" name="user_type" id="user_type">
                                                    <option value="" selected>Selecione</option>
                                                    <option value="1">Administrador</option>
                                                    <option value="2">Convêncional</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="sector_id">@lang('Sector')</label>
                                                <select class="form-control" name="sector_id" id="sector_id">
                                                    <option value="" selected>Selecione</option>
                                                    @foreach ($sectors as $sector)
                                                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="cpf">@lang('CPF')</label>
                                                <input type="text" class="form-control maskCPF" name="cpf" id="cpf" placeholder="999.999.999-99">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="password">@lang('Password')</label>
                                                <input type="password" class="form-control" name="password" id="password" placeholder="@lang('Password')">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success" name="btn_Action" value="add">@lang('Add')</button>
                                        <button type="button" class="btn btn-success" name="btn_Action" value="consult">@lang('Consult')</button>
                                        <button type="button" class="btn btn-success" name="btn_Action" value="update">@lang('Update')</button>
                                        <button type="button" class="btn btn-success" name="btn_Action" value="remove">@lang('Remove')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="div_ResultList"></div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function($){
            function submitForm(actionType){
                showLoader(function(){
                    $('#form_Management').ajaxSubmit({
                        data: {actionType: actionType},
                        dataType: 'json',
                        success: function(response){
                            if(!Boolean(response.error)){
                                $('#alerts').append('<div class="alert alert-'+response.alerts['type']+' alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+response.alerts['text']+'</div>');
                            }else{
                                $('#alerts').append('<div class="alert alert-'+response.alerts['type']+' alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+response.alerts['text']+'</div>');
                            }
                            $('#form_Management')[0].reset();
                            $('#form_Management #id').val('');
                            hideLoader();
                        }
                    });
                });
            }

            function getList(page = 1){
                showLoader(function(){
                    $('#form_Management').ajaxSubmit({
                        url: '{{ route("users") }}?page='+page,
                        data: {actionType: 'consult'},
                        dataType: 'html',
                        success: function(response){
                            $('#div_ResultList').html(response);
                            hideLoader();
                        }
                    });
                });
            }

            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                e.stopPropagation();
                getList($(this).prop('href').split('page=')[1]);
            });

            $(document).on('click', '#tb_ResultList tbody tr', function(e){
                $('#form_Management #id').val($(this).data('id'));
                $('#div_ResultList').html('');
                showLoader(function(){
                    $('#form_Management').ajaxSubmit({
                        data: {actionType: 'show'},
                        dataType: 'json',
                        success: function(response){
                            if(!Boolean(response.error)){
                                $('#form_Management #id').val(response.user['id']);
                                $('#form_Management #sector_id').val(response.user['sector_id']);
                                $('#form_Management #user_type').val(response.user['user_type']);
                                $('#form_Management #name').val(response.user['name']);
                                $('#form_Management #email').val(response.user['email']);
                                $('#form_Management #cpf').val(response.user['cpf']);
                            }
                            hideLoader(function(){
                                //$('#alerts').append('<div class="alert alert-'+response.alerts['type']+' alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+response.alerts['text']+'</div>');
                            });
                        }
                    });
                });
            });

            $('button[name=btn_Action]').on('click', function(){
                $('#alerts div.alert').remove();
                switch($(this).val()){
                    default:
                        if($(this).val() == 'update' || $(this).val() == 'remove'){
                            if($('#form_Management #id').val() === ""){
                                $('#alerts').append('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Por favor, consulte e selecione um usuário</div>');
                                return false;
                            }
                        }
                        submitForm($(this).val());
                        break;

                    case 'consult':
                        getList();
                        break;
                }
            });
        });
    </script>
@endsection
