<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $subject }}</title>
</head>
<body>
    <table>
        <tr>
            <td>{{$subject}}</td>
        </tr>
        <tr>
            <td>Hi Mr,<strong>{{$body['name']}}</strong></td>
        </tr>

        <tr>
            <td>
                <p>We're receive a request to change your password.</p> <br>
                Code: <strong>{{ $body['code'] }}</strong>
            </td>
        </tr>
    </table>
</body>
</html>