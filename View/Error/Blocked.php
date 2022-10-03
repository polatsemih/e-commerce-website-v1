<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Blanck Basic</title>
    <meta name="robots" content="none" />
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <style>
        main {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #000000;
            font-family: sans-serif;
        }
        
        div {
            box-shadow: 0px 4px 12px rgb(0 0 0 / 12%), 0px 0px 2px rgb(0 0 0 / 8%);
            background-color: #ffffff;
            width: 100vw;
            border-radius: 10px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            font-size: 35px;
            color: #000000;
            margin-bottom: 30px;
        }

        span {
            font-size: 30px;
            font-weight: 600;
            line-height: 1.4;
            color: #beab00;
            margin-bottom: 5px;
            text-align: center;
        }

        @media only screen and (min-width: 768px) {
            div {
                width: 50vw;
            }
        }
    </style>
</head>

<body>
    <main>
        <div>
            <h1>Blanck Basic</h1>
            <span>Hesabınız Engellendi</span>
        </div>
    </main>
</body>

</html>