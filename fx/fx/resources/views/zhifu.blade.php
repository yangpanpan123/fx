<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <form class="" action="/payok" method="post">
            {!!csrf_field()!!}
            <input type="hidden" name="oid" value="{{$oid}}">
            <input type="submit"  value="确认付款">
        </form>
    </body>
</html>
