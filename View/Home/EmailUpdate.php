<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Doğrulama | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body>
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <section class="action-section">
            <div class="verify-token-container">
                <h1 class="verify-token-title">Yeni email adresinizi doğrulamak için email kutunuza gelen doğrulama kodunu alt bölüme giriniz.</h1>
                <div class="counter-container">
                    <div class="right">
                        <span class="counter-text">Kalan Süre:</span>
                        <span id="counter-remain-m" class="counter-text"></span>
                        <span id="counter-remain-dot" class="counter-text">.</span>
                        <span id="counter-remain-s" class="counter-text"></span>
                    </div>
                </div>
                <form id="form-verify-token" action="<?php echo URL . URL_EMAIL_UPDATE_CONFIRM; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                    <div class="row">
                        <?php if (!empty($web_data['form_token'])) : ?>
                            <input type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                        <?php endif; ?>
                        <?php if (!empty($web_data['confirm_token'])) : ?>
                            <input type="hidden" name="confirm_token" value="<?php echo $web_data['confirm_token']; ?>">
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
                    <div class="row-center">
                        <button class="verify-token-submit" title="Yeni Email Adresimi Doğrula">Doğrula</button>
                        <a class="go-back-login" href="<?php echo URL; ?>">Ana Sayfaya Dön</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <?php if (!empty($web_data['cookie_cart'])) : ?>
        <script src="<?php echo URL; ?>assets/js/header_cart.js"></script>
    <?php endif; ?>
    <script>
        $(document).ready(function() {
            var request;
            var requestUsable = true;
            var inputSearch = $('#input-search');
            var navSearch = $('.nav-search');
            var navSearchPopular = $('.nav-search-popular');
            inputSearch.on('input', function(e) {
                e.preventDefault();
                if (!$.trim(inputSearch.val())) {
                    $('#nav-search-wrapper').remove();
                    if (navSearchPopular.hasClass('hidden')) {
                        navSearchPopular.removeClass('hidden');
                    }
                    if (!navSearch.hasClass('hidden')) {
                        navSearch.addClass('hidden');
                    }
                } else if (requestUsable) {
                    requestUsable = false;
                    const formSearch = $('#form-search');
                    const inputsformSearch = formSearch.find('input');
                    request = $.ajax({
                        url: '<?php echo URL . URL_ITEM_SEARCH; ?>',
                        type: 'POST',
                        data: formSearch.serialize()
                    });
                    inputsformSearch.prop('disabled', true);
                    request.done(function(response) {
                        requestUsable = true;
                        if (!navSearchPopular.hasClass('hidden')) {
                            navSearchPopular.addClass('hidden');
                        }
                        if (navSearch.hasClass('hidden')) {
                            navSearch.removeClass('hidden');
                        }
                        response = jQuery.parseJSON(response);
                        if (response.hasOwnProperty('shutdown')) {
                            window.location.href = '<?php echo URL . URL_SHUTDOWN; ?>';
                        } else if (response.hasOwnProperty('exception')) {
                            window.location.href = '<?php echo URL . URL_EXCEPTION; ?>';
                        } else if (response.hasOwnProperty('stop')) {

                        } else if (response.hasOwnProperty('not_found_search_item')) {
                            $('#nav-search-wrapper').remove();
                            let ss1 = $("<div></div>").attr('id', 'nav-search-wrapper');
                            let ss2 = $("<li></li>").addClass('search-item');
                            ss1.append(ss2);
                            let ss3 = $("<a></a>").addClass('not-found-search').text('Aranılan kriterde ürün bulunamadı');
                            ss2.append(ss3);
                            navSearch.append(ss1);
                        } else if (response.hasOwnProperty('searched_items')) {
                            $('#nav-search-wrapper').remove();
                            let s1 = $("<div></div>").attr('id', 'nav-search-wrapper');
                            $.each(response['searched_items'], function(key, searchitem) {
                                let s2 = $("<li></li>").addClass('search-item');
                                s1.append(s2);
                                let s3 = $("<a></a>").addClass('search-link').attr('href', '<?php echo URL . URL_ITEM_DETAILS . '/' ?>' + searchitem['item_url']).text(searchitem['item_name']);
                                s2.append(s3);
                            });
                            navSearch.append(s1);
                        }
                    });
                    request.always(function() {
                        inputsformSearch.prop('disabled', false);
                        inputSearch.focus();
                    });
                }
            });
        });
    </script>
    <script>
        const emailConfirmInputs = document.querySelectorAll('.verify-token-input');
        for (let index = 0; index < emailConfirmInputs.length; index++) {
            emailConfirmInputs[index].addEventListener('keyup', (e) => {
                if (/^[a-z]$/i.test(e.key) || /^[0-9]$/i.test(event.key)) {
                    if (emailConfirmInputs.length > index + 1) {
                        emailConfirmInputs[index + 1].focus();
                    } else if (emailConfirmInputs.length == index + 1) {
                        emailConfirmInputs[index].blur();
                    }
                } else {
                    emailConfirmInputs[index].value = '';
                }
            });
        }
        const verifyTokenSubmit = document.querySelector('.verify-token-submit');
        const goBackLogin = document.querySelector('.go-back-login');
        const counterRemainM = document.getElementById('counter-remain-m');
        const counterRemainS = document.getElementById('counter-remain-s');
        const counterText = document.querySelector('.counter-text');
        var startMin = <?php echo $web_data['expiry_remain_minute']; ?>;
        var startSec = <?php echo $web_data['expiry_remain_second']; ?>;
        if (startMin < 10) {
            counterRemainM.innerHTML = '0' + startMin;
        } else {
            counterRemainM.innerHTML = startMin;
        }
        if (startSec < 10) {
            counterRemainS.innerHTML = '0' + startSec;
        } else {
            counterRemainS.innerHTML = startSec;
        }
        var interval;

        function counter() {
            if (startSec == 0) {
                startSec = 59;
                if (startMin == 0) {
                    clearInterval(interval);
                    counterRemainM.innerHTML = '';
                    counterRemainS.innerHTML = '';
                    document.getElementById('counter-remain-dot').innerHTML = '';
                    if (!verifyTokenSubmit.classList.contains('disable')) {
                        verifyTokenSubmit.classList.add('disable');
                    }
                    if (!counterText.classList.contains('timeout')) {
                        counterText.classList.add('timeout');
                    }
                    counterText.innerHTML = 'Süre doldu.';
                    if (!goBackLogin.classList.contains('active')) {
                        goBackLogin.classList.add('active')
                    }
                    verifyTokenSubmit.disabled = true;
                } else if (startMin < 10) {
                    startMin--;
                    counterRemainM.innerHTML = '0' + startMin;
                    counterRemainS.innerHTML = startSec;
                } else {
                    startMin--;
                    counterRemainM.innerHTML = startMin;
                    counterRemainS.innerHTML = startSec;
                }
            } else {
                startSec--;
                if (startSec < 10) {
                    counterRemainS.innerHTML = '0' + startSec;
                } else {
                    counterRemainS.innerHTML = startSec;
                }
            }
        }
        interval = setInterval(counter, 1000);
        verifyTokenSubmit.addEventListener('click', (e) => {
            e.preventDefault();
            let emptyInput = true;
            emailConfirmInputs.forEach(emailConfirmInput => {
                if (emailConfirmInput.value == '') {
                    emptyInput = false;
                }
            });
            if (emptyInput) {
                if (!verifyTokenSubmit.classList.contains('disable')) {
                    verifyTokenSubmit.classList.add('disable');
                }
                if (loaderWrapper.classList.contains('hidden')) {
                    loaderWrapper.classList.remove('hidden');
                }
                if (!bodyElement.classList.contains('noscroll')) {
                    bodyElement.classList.add('noscroll');
                }
                document.getElementById('form-verify-token').submit();
            }
        });
    </script>
</body>

</html>