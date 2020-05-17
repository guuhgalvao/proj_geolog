<!DOCTYPE html>
<html lang="pt_br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body{
            background-color: #fff;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="col-12">
        <div class="row justify-content-center">
            <div class="pb-2 mt-4 mb-2 border-bottom text-center">
                <h4>@lang('Results')</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-10">
                <img src="{{ public_path().'/../storage/app/'.$img_path }}" alt="Graphic" class="img-responsive">
            </div>
        </div>
            {{-- <div class="card border-success mt-2">
                <div class="card-header bg-success text-white">@lang('Results')</div>
                <div class="card-body">
                    <div class="col-md-12" id="alerts"></div>

                </div>
                <div class="card-footer text-right"></div>
            </div> --}}
    </div>
</body>
</html>
