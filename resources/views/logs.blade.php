<!doctype html>
<html lang="en">
<head>
    <title>{{$name}} - Logs</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cyborg/bootstrap.min.css" rel="stylesheet" integrity="sha384-l7xaoY0cJM4h9xh1RfazbgJVUZvdtyLWPueWNtLAphf/UbBgOVzqbOTogxPwYLHM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/css/flag-icon.min.css">
    <script
        src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
        crossorigin="anonymous"></script>
    <script>
        $('#content tr').each(function () {
            this.children('.flag').html('')
        })
    </script>
</head>
<body>
<div class="row justify-content-center" style="margin:0">
    <div class="col-md-6  d-flex justify-content-center">
        <table class="table table-striped table-inverse table-responsive" style="width: max-content">
            <thead class="thead-inverse">
            <tr>
                <th>#</th>
                <th>IP Address</th>
                <th>Country</th>
                <th>Time</th>
            </tr>
            </thead>
            <tbody id="content">
            @foreach($logs as $log)
                <tr>
                    <td class="flag"><span class="flag-icon flag-icon-{{strtolower($ipData[$log->ip]["country_code"])}}"></span></td>
                    <td scope="row"><a href="https://dnslytics.com/search?q={{$log->ip}}">{{$log->ip}}</a></td>
                    <td>
                        @if($ipData[$log->ip]["city"] || $ipData[$log->ip]["region_name"])
                        {{($ipData[$log->ip]["city"] == $ipData[$log->ip]["region_name"] ? $ipData[$log->ip]["city"] : $ipData[$log->ip]["city"] . ' ,'. $ipData[$log->ip]["region_name"])}},
                        @endif
                            {{$ipData[$log->ip]["country_name"]}}</td>
                    <td>{{$log->created_at->format('H:i d/m/y')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>
