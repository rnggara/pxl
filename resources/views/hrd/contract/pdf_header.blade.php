<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{asset('theme/assets/plugins/global/plugins.bundle.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/assets/css/style.bundle.css?v=7.0.5')}}" rel="stylesheet" type="text/css" />
</head>
<body>
<table style="width: 100%">
    <tr>
        <td style="width: 250px">
            <img width="200px" style="max-height: 100px" src="{{ str_replace("public", "public_html", asset("images/".$comp->app_logo)) }}" alt="">
        </td>
        <td style="vertical-align: top">
            {{ $comp->company_name }}
            <br><br>
            @php
                $address = explode(",", $comp->address)
            @endphp
            @foreach ($address as $i => $item)
                {{ $item }}{!! ($i < count($address) - 1) ? ",<br>" : "" !!}
            @endforeach
            <br>
            Phone : {{ $comp->phone }}, Fax : {{ $comp->fax }}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <hr>
        </td>
    </tr>
</table>
</body>
</html>
