<?php
$random_tokens[0] = 'W';
$random_tokens[1] = 'X';
$random_tokens[2] = '1';
$random_tokens[3] = 'a';
$random_tokens[4] = '0';
$random_tokens[5] = 'a';
$random_tokens[6] = 'a';
$random_tokens[7] = 'a';
$resetpwd_token = 'a';
echo
'<!DOCTYPE html>
<html lang="tr">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta charset="UTF-8" />
    <title>Şifre Sıfırlama | ' . BRAND . '</title>
    <style>
        * {
            margin: 0px;
            padding: 0px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            background-color: #ffffff;
            width: 100%;
            height: 100%;
        }

        .container {
            width: 100%;
            height: 100%;
            margin-left: auto;
            margin-right: auto;
        }

        .header {
            background-color: #000000;
            text-align: center;
            padding-top: 20px;
            padding-bottom: 20px;
            padding-left: 10px;
            padding-right: 10px;
            border-bottom-width: 1px;
            border-bottom-style: solid;
            border-bottom-color: #ffffff;
        }

        .title {
            font-size: 40px;
            letter-spacing: 5px;
            color: #ffffff;
            margin-bottom: 20px;
        }

        .text-1 {
            font-size: 16px;
            line-height: 1.4;
            color: #ffffff;
            letter-spacing: 1px;
        }

        .main {
            background-color: #000000;
            text-align: center;
        }

        .text-2 {
            font-size: 15px;
            line-height: 1.4;
            color: #ffffff;
            padding-top: 20px;
            margin-bottom: 10px;
            padding-left: 10px;
            padding-right: 10px;
            border-top-width: 1px;
            border-top-style: solid;
            border-top-color: #ffffff;
        }

        .text-2-link {
            color: #6466ec !important;
        }

        .text-3 {
            font-size: 13px;
            line-height: 1.4;
            color: #ffffff;
            padding-left: 10px;
            padding-right: 10px;
            padding-bottom: 20px;
        }

        .footer {
            background-color: #f3f3f398;
            text-align: center;
            padding-top: 20px;
            padding-bottom: 20px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .footer-text {
            font-size: 13px;
            line-height: 1.4;
            color: #000000;
            margin-bottom: 20px;
        }

        .footer-url {
            font-size: 12px;
            color: #000000;
            margin-right: 10px;
        }

        .footer-date {
            font-size: 12px;
            color: #000000;
            margin-left: 10px;
        }

        @media only screen and (min-width: 768px) {
            .container {
                width: 70%;
            }
        }

        @media only screen and (min-width: 992px) {
            .container {
                width: 50%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 class="title">BB</h1>
            <p class="text-1">' . BRAND . ' Şifre Sıfırlama</p>
        </div>
        <main class="main">
            <p class="text-2">Şifrenizi sıfırlamak için linke <a class="text-2-link" href="' . URL . URL_RESET_PASSWORD . '?resetpwd=' . $resetpwd_token . '">tıklayın.</a></p>
            <p class="text-3">Şifre sıfırlama linkinin kullanım süresi ' . EXPIRY_RESET_PWD_TOKEN_MINUTE . ' dakikadır</p>
        </main>
        <footer class="footer">
            <p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, bu emaili önemsemeyin</p>
            <a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a>
            <span class="footer-date">' . date('d/m/Y H:i:s') . '</span>
        </footer>
    </div>
</body>

</html>';
