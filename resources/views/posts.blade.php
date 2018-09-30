<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel Resource</title>

    </head>
    <body>
        <h3>FORM PUT - {{ $user }}</h3>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <form method="post" action="/posts">
                    @method('PUT')

                    <input type="text" name="name" value="tom">

                    <button type="submit">PUT</button>
                </form>
            </div>
        </div>
    </body>
</html>
