<?php
$random_tokens[0] = 'W';
$random_tokens[1] = 'X';
$random_tokens[2] = 'a';
$random_tokens[3] = 'a';
$random_tokens[4] = 'a';
$random_tokens[5] = 'a';
$random_tokens[6] = 'a';
$random_tokens[7] = 'a';
$cancel_register_token = 'a';
echo
'<!DOCTYPE html>
<html lang="tr">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta charset="UTF-8" />
    <title>Üyelik Aktifleştirme | ' . BRAND . '</title>
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

        .confirm-container {
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .confirm {
            display: inline-block;
            font-size: 20px;
            text-align: center;
            background-color: #ffffff;
            color: #000000;
            width: 10%;
            padding-top: 10px;
            padding-bottom: 10px;
            margin-right: 1%;
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

        .footer-text-url {
            color: #6466ec !important;
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

            .confirm-container {
                width: 70%;
            }

            .confirm {
                padding-top: 20px;
                padding-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 class="title">BB</h1>
            <p class="text-1">' . BRAND . ' Hesabınızı Doğrulayın</p>
        </div>
        <main class="main">
            <div class="confirm-container">
                <span class="confirm">' . $random_tokens[2] . '</span>
                <span class="confirm">' . $random_tokens[4] . '</span>
                <span class="confirm">' . $random_tokens[0] . '</span>
                <span class="confirm">' . $random_tokens[7] . '</span>
                <span class="confirm">' . $random_tokens[1] . '</span>
                <span class="confirm">' . $random_tokens[3] . '</span>
                <span class="confirm">' . $random_tokens[6] . '</span>
                <span class="confirm">' . $random_tokens[5] . '</span>
            </div>
            <p class="text-2">Üyeliğinizi aktif etmek için üstteki kodu girin</p>
            <p class="text-3">Doğrulama kodunun kullanım süresi ' . EXPIRY_CONFIRM_EMAIL_TOKEN_MINUTE . ' dakikadır</p>
        </main>
        <footer class="footer">
            <p class="footer-text">Bu işlemi siz gerçekleştirmediyseniz, üyelik işlemini iptal etmek için <a class="footer-text-url" href="' . URL . URL_VERIFY_LINK . '?cr=' . $cancel_register_token . '">tıklayınız.</a></p>
            <a class="footer-url" href="' . PURE_URL . '">' . PURE_URL . '</a>
            <span class="footer-date">' . date('d/m/Y H:i:s') . '</span>
        </footer>
    </div>
</body>

</html>';
