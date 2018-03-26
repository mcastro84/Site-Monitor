<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Website Monitor</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="flex-center position-ref full-height">

    <div class="content">
        <div class="title m-b-md">
            Website Monitor
        </div>

        <div class="">
            <div class="col-lg-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="alert alert-warning hidden" role="alert"></div>
                        <div class="alert alert-success hidden" role="alert"></div>
                        <div class="input-group">
                            <input type="text" name="url" class="form-control url-input" placeholder="Website Url">
                            <span class="input-group-btn">
                                <button class="btn btn-primary add-url-button" type="button">
                                    Add
                                </button>
                            </span>
                        </div>
                        <br/>
                        @if(isset($urls))
                            @foreach($urls as $url)
                                <div class="row pt-10">
                                    <div class="col-lg-6 url">
                                        {{$url->url}}
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="btn btn-sm btn-danger pull-right remove-url-button" data-id="{{$url->id}}">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div id="template" class="row hidden pt-10">
                            <div class="col-lg-6 url"></div>
                            <div class="col-lg-6">
                                <button class="btn btn-sm btn-danger pull-right remove-url-button">Remove</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-default btn-block check-urls">Check Urls</button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var APP_URL = '{{env('APP_URL')}}';
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="{{asset('js/app.js')}}"></script>
</body>
</html>