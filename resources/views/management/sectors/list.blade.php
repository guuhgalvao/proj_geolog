@if(count($sectors) > 0)
    <div class="card border-success mt-4" id="card_ResultList">
        <div class="card-header bg-success text-white">@lang('Sectors List')</div>
        <div class="table-responsive" id="tb_ResultList">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sectors as $sector)
                        <tr data-id="{{ $sector->id }}">
                            <td>{{ $sector->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="col-12">
                <div class="row justify-content-center">
                        {{ $sectors->links() }}
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        Não há resultados
    </div>
@endif
