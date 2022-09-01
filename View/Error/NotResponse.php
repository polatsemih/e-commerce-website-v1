<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Blanck Basic</title>
    <meta name="robots" content="none" />
    <style>
        ::selection {
            color: #ca0101;
            background-color: #000000;
        }

        ::-moz-selection {
            color: #ca0101;
            background-color: #000000;
        }

        * {
            margin: 0px;
            padding: 0px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

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
            width: 50vw;
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
            font-size: 20px;
            line-height: 1.4;
            color: #ca0101;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <main>
        <div>
            <h1>Blanck Basic</h1>
            <span>Bir sorundan dolayı hizmet veremiyoruz</span>
            <span>Sorundan haberdarız ve üzerinde çalışıyoruz</span>
            <span>Anlayışınız ve sabrınız için teşekkür ederiz</span>
        </div>
    </main>
</body>

</html>