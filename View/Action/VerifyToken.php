<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Doğrulama | <?php echo BRAND; ?></title>
    <?php require 'View/SharedCommon/_common_meta.php'; ?>
    <meta name="robots" content="none" />
    <?php require 'View/SharedCommon/_common_favicon.php'; ?>
    <link rel="stylesheet" href="<?php echo URL; ?>assets/css/home.css">
    <style>
        .verify-token-popup {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .verify-token-container {
            width: 100%;
            background-color: #ffffff;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            padding: 2rem;
        }

        .verify-token-title {
            font-size: 1.7rem;
            color: #028565;
            line-height: 1.4;
            margin-bottom: 1rem;
            text-align: center;
        }

        .verify-token-text {
            font-size: 1.4rem;
            color: #000000;
        }

        .verify-token-text.green {
            color: #00b487 !important;
        }

        .verify_token-form {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .verify-token-input {
            width: 12%;
            height: 6rem;
            font-size: 2.5rem;
            text-align: center;
            border-width: 3px;
            border-style: solid;
            border-color: #000000;
        }

        .verify-token-input:not(:last-child) {
            margin-right: 1%;
        }

        .verify-token-input:focus {
            outline: 0;
            border-width: 3px;
            border-style: solid;
            border-color: #5155d3;
        }

        .verify_token-submit,
        .go-back-login {
            background-color: #5155d3;
            color: #ffffff;
            border-radius: 5px;
            padding: 1rem;
            cursor: pointer;
            margin-top: 3rem;
            font-size: 1.5rem;
            border: none;
        }

        .verify_token-submit.disable {
            display: none !important;
        }

        .verify_token-submit:hover {
            background-color: #5155d3da;
        }

        .go-back-login {
            display: none;
        }

        .go-back-login.active {
            display: block !important;
        }

        .go-back-login:hover {
            background-color: #5155d3da;
        }

        .counter-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-bottom: 2rem;
        }

        .counter {
            margin-left: auto;
        }

        .counter-text,
        .counter-remain {
            font-size: 1.4rem;
            color: #000000;
            font-weight: 700;
        }

        .counter-text.timeout {
            color: #ca0101 !important;
        }

        @media only screen and (min-width: 576px) {
            .verify-token-container {
                width: 70%;
            }

            .verify-token-title {
                text-align: left;
            }
        }

        @media only screen and (min-width: 768px) {
            .verify-token-container {
                width: 60%;
            }

            .verify-token-title {
                margin-bottom: 0px;
            }

            .verify-token-input {
                font-size: 4rem;
                height: 8rem;
            }

            .verify-token-title {
                margin-bottom: 2rem;
            }

            .verify_token-submit {
                margin-top: 4rem;
            }
        }

        @media only screen and (min-width: 992px) {
            .verify-token-container {
                width: 50%;
            }
        }

        @media only screen and (min-width: 1200px) {
            .verify-token-container {
                width: 40%;
            }
        }
    </style>
</head>

<body>
    <?php require 'View/SharedCommon/_common_loader.php'; ?>
    <header>
        <div class="header-container">
            <div class="container row-adjacent">
                <div class="header-brand-container">
                    <a class="header-brand" href="<?php echo URL; ?>"><?php echo BRAND; ?></a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <section class="action-section">
            <div class="verify-token-popup">
                <div class="verify-token-container">
                    <?php if (!empty($web_data['verify_token_type'])) : ?>
                        <?php if ($web_data['verify_token_type'] == 'two_fa') {
                            $token_time_remain = !empty($web_data['token_time_remain']) ? (int)$web_data['token_time_remain'] : EXPIRY_TWO_FA_TOKEN_MINUTE;
                            echo '<h1 class="verify-token-title">Email kutunuza gelen doğrulama kodunu alt bölüme girerek giriş yapabilirsiniz.</h1>';
                        } elseif ($web_data['verify_token_type'] == 'confirm_email') {
                            $token_time_remain = !empty($web_data['token_time_remain']) ? (int)$web_data['token_time_remain'] : EXPIRY_CONFIRM_EMAIL_TOKEN_MINUTE;
                            echo '<h1 class="verify-token-title">Email kutunuza gelen doğrulama kodunu alt bölüme girerek üyeliğinizi aktif edebilirsniz.</h1>';
                        } ?>
                        <div style="width: 100%; height: 3px; background-color: #000000; margin-bottom: 10px;"></div>
                        <div class="counter-container">
                            <div class="counter">
                                <span class="counter-text">Kalan Süre:</span>
                                <span class="counter-remain counter-remain-m"><?php echo $token_time_remain; ?></span>
                                <span class="counter-remain counter-remain-dot">.</span>
                                <span class="counter-remain counter-remain-s">00</span>
                            </div>
                        </div>
                        <form class="verify_token-form" action="<?php echo URL . URL_VERIFY_TOKEN; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                            <div class="row">
                                <?php if (!empty($web_data['csrf_token'])) : ?>
                                    <input type="hidden" name="form_token" value="<?php echo $web_data['csrf_token']; ?>">
                                <?php endif; ?>
                                <?php if (!empty($web_data['verify_token'])) : ?>
                                    <input type="hidden" name="verify_token" value="<?php echo $web_data['verify_token']; ?>">
                                <?php endif; ?>
                                <input class="verify-token-input" type="text" name="token_1" maxlength="1" autofocus>
                                <input class="verify-token-input" type="text" name="token_2" maxlength="1">
                                <input class="verify-token-input" type="text" name="token_3" maxlength="1">
                                <input class="verify-token-input" type="text" name="token_4" maxlength="1">
                                <input class="verify-token-input" type="text" name="token_5" maxlength="1">
                                <input class="verify-token-input" type="text" name="token_6" maxlength="1">
                                <input class="verify-token-input" type="text" name="token_7" maxlength="1">
                                <input class="verify-token-input" type="text" name="token_8" maxlength="1">
                            </div>
                            <?php if (!empty($web_data['verify_token_type'])) : ?>
                                <?php if ($web_data['verify_token_type'] == 'two_fa') : ?>
                                    <input class="verify_token-submit" type="submit" name="verify_token-submit" value="Giriş Yap" title="Giriş Yap">
                                <?php elseif ($web_data['verify_token_type'] == 'confirm_email') : ?>
                                    <input class="verify_token-submit" type="submit" name="verify_token-submit" value="Doğrula" title="Email Adresimi Doğrula">
                                <?php endif; ?>
                            <?php endif; ?>
                            <a class="go-back-login" href="<?php echo URL . URL_ADMIN_LOGIN; ?>">Giriş Ekranına Dön</a>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <?php require 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/loader.js"></script>
    <script>
        const emailConfirmInputs = document.querySelectorAll('.verify-token-input');
        for (let index = 0; index < emailConfirmInputs.length; index++) {
            emailConfirmInputs[index].addEventListener('keyup', (e) => {
                if ((e.keyCode >= 48 && e.keyCode <= 90) || e.keyCode == 39) {
                    if (emailConfirmInputs.length > index + 1) {
                        emailConfirmInputs[index + 1].focus();
                    } else if (emailConfirmInputs.length == index + 1) {
                        emailConfirmInputs[index].blur();
                    }
                } else if (e.keyCode == 8 || e.keyCode == 37) {
                    if (index + 1 > 1) {
                        emailConfirmInputs[index - 1].focus();
                    }
                }
            });
        }
        const verifyTokenSubmit = document.querySelector('.verify_token-submit');
        const counterRemainM = document.querySelector('.counter-remain-m');
        const counterRemainS = document.querySelector('.counter-remain-s');
        var startSec = 59;
        var startMin = <?php echo $token_time_remain - 1; ?>;
        var interval;

        function counter() {
            if (counterRemainM.innerHTML == <?php echo $token_time_remain; ?>) {
                counterRemainM.innerHTML = <?php echo $token_time_remain - 1; ?>;
                counterRemainS.innerHTML = '59';
            }
            if (startSec == 0) {
                startSec = 59;
                counterRemainS.innerHTML = startSec;
                if (startMin == 0) {
                    clearInterval(interval);
                    counterRemainM.innerHTML = '';
                    counterRemainS.innerHTML = '';
                    document.querySelector('.counter-remain-dot').innerHTML = '';
                    document.querySelector('.counter-text').classList.add('timeout');
                    document.querySelector('.counter-text').innerHTML = 'Süre doldu. Giriş ekranından tekrar giriş yapın.';
                    document.querySelector('.go-back-login').classList.add('active');
                    verifyTokenSubmit.classList.add('disable');
                    verifyTokenSubmit.disabled = true;
                    verifyTokenSubmit.setAttribute('value', 'Süre Doldu');
                    verifyTokenSubmit.setAttribute('title', 'Giriş ekranından tekrar giriş yapın');
                } else if (startMin < 10) {
                    startMin--;
                    counterRemainM.innerHTML = '0' + startMin;
                } else {
                    startMin--;
                    counterRemainM.innerHTML = startMin;
                }
            } else {
                if (startSec < 10) {
                    counterRemainS.innerHTML = '0' + startSec;
                } else {
                    counterRemainS.innerHTML = startSec;
                }
                startSec--;
            }
        }
        interval = setInterval(counter, 1000);
        document.querySelector('.verify_token-form').addEventListener('submit', () => {
            verifyTokenSubmit.classList.add('disable');
            if (loader.classList.contains('disable') && loader.classList.contains('loading')) {
                loader.classList.remove('disable');
                loader.classList.remove('loading');
            }
        });
    </script>
</body>

</html>