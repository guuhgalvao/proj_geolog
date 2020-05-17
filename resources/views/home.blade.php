@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-success mt-2" id="card_ResultList">
                <div class="card-header bg-success text-white">@lang('Researches List')</div>
                <div class="table-responsive" id="tb_ResultList">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>@lang('Sector')</th>
                                <th>@lang('Question Name')</th>
                                <th>@lang('Questions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($researches as $research)
                                <tr data-id="{{ $research->id }}">
                                    <td>{{ $research->sector->name }}</td>
                                    <td>{{ $research->name }}</td>
                                    <td>{{ count($research->questions) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <div class="col-12">
                        <div class="row justify-content-center">
                                {{ $researches->links() }}
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function($){
            $(document).on('click', '#tb_ResultList tbody tr', function(e){
                window.location.href = "{{ route('research') }}/"+$(this).data('id');
            });
        });
    </script>
@endsection
