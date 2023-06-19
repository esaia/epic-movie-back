@props(['url', 'name'])

<!DOCTYPE html>
<html>

<head>
    <title>email verification</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">


    <meta charset="UTF-8" />
</head>


<body
    style="background-color: #191623; display: flex; justify-items: center; align-items: center; flex-direction:column; font-family: 'Lato', sans-serif;">
    <div style="display: flex; justify-items: center; align-items: center; flex-direction: column; margin-top: 30px">
        <x-icons.quote />
        <p style="color:white">Movie quotes</h1>
    </div>

    <div
        style="display: flex; width: 100%; justify-items: center; align-items: flex-start; flex-direction: column; max-width: 1000px;">
        <p style="color:white; padding: 7px">Hola {{ $name }}!</p>

        <p style="color:white; padding: 7px">Thanks for joining Movie quotes! We really appreciate it. Please click the
            button below to verify your account:</p>
        <div style="padding: 7px;">

            <a href="{{ $url }}" style="text-decoration: none;">
                <button
                    style=" background-color: rgb(219, 36, 36); color:white; border: none; padding: 8px; border-radius: 4px; cursor: pointer">
                    Verify account
                </button>
            </a>

        </div>
        <p style="color:white; padding: 7px">
            If clicking doesn't work, you can try copying and pasting it to your browser:
        </p>
        <a href="{{ $url }}" style="text-decoration: none;">
            <p style="color: #c4b599; max-width: 1000px; padding: 7px; width: 400px">
                {{ $url }}
            </p>
        </a>


        <p style="color:white; padding: 7px">If you have any problems, please contact us: support@moviequotes.ge</h1>
        <p style="color:white; padding: 7px">MovieQuotes Crew</h1>





    </div>
</body>

</html>
