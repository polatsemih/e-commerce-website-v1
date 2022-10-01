<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Email Gönder - Yönetici | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <main>
        <section class="sendemail-section container">
            <h1 class="title">Sistem Emaili Gönder</h1>
            <form class="wrapper" id="form-email-create" action="<?php echo URL . URL_ADMIN_SEND_EMAIL; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate enctype="multipart/form-data">
                <?php if (!empty($web_data['form_token'])) : ?>
                    <input type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                <?php endif; ?>
                <div class="row">
                    <div class="col-label">
                        <label class="create-label" for="user_email">Kime</label>
                    </div>
                    <div class="col-input">
                        <input class="create-input" id="user_email" type="text" name="user_email" autofocus>
                    </div>
                </div>
                <div id="ready-messages" class="row">
                    <div class="col-label">
                        <label class="create-label" for="user_email">Mesaj</label>
                    </div>
                    <div class="col-input">
                        <div id="sendemail-select" class="email-type">
                            <select class="email-select" name="email_ready_message">
                                <option id="details-option-1" value="1"></option>
                                <option id="details-option-2" value="2"></option>
                                <option id="details-option-3" value="3"></option>
                                <option id="details-option-4" value="4"></option>
                                <option id="details-option-5" value="5"></option>
                            </select>
                            <span id="select-text" class="select-text">Mesaj Seçiniz</span>
                            <span class="select-triangle"><i class="fas fa-angle-down"></i></span>
                            <div id="details-select" class="details-select">
                                <span class="option" data-option="1" data-url="Sipariş Kargoya Verildi">Sipariş Kargoya Verildi</span>
                                <span class="option" data-option="2" data-url="Sipariş Teslim Edildi">Sipariş Teslim Edildi</span>
                                <span class="option" data-option="3" data-url="Sipariş İptal Edildi">Sipariş İptal Edildi</span>
                                <span class="option" data-option="4" data-url="İade Edilen Sipariş Kargoya Verildi">İade Edilen Sipariş Kargoya Verildi</span>
                                <span class="option" data-option="5" data-url="İade Edilen Sipariş Teslim Edildi">İade Edilen Sipariş Teslim Edildi</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="manuel-messages-id" class="row">
                    <div class="col-label">
                        <label class="create-label textarea" for="user_email">Mesaj</label>
                    </div>
                    <div class="col-input">
                        <textarea class="create-textarea" id="email_manuel_message" name="email_manuel_message"></textarea>
                    </div>
                </div>
                <div id="shipping-number-wrapper" class="row">
                    <div class="col-label">
                        <label class="create-label" for="shipping_number">Kargo Takip Numarası</label>
                    </div>
                    <div class="col-input">
                        <input class="create-input" id="shipping_number" type="text" name="shipping_number">
                    </div>
                </div>
                <div class="row">
                    <div class="left">
                        <label for="manuel_checkbox" class="manuel-checkbox-wrapper">
                            <input class="manuel-checkbox" id="manuel_checkbox" type="checkbox">
                            <div class="manuel-bg">
                                <span class="manuel-inner-text">Manuel Mesaj Kapalı</span>
                                <div class="manuel-ball"></div>
                            </div>
                        </label>
                    </div>
                    <div class="right">
                        <input class="btn-email-send" type="submit" value="Emaili Gönder">
                    </div>
                </div>
            </form>
        </section>
    </main>
    <?php require_once 'View/SharedAdmin/_admin_footer.php'; ?>
    <script>
        document.getElementById('sendemail-select').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('details-select').classList.toggle('active');
        });
        const shippingNumberWrapper = document.getElementById('shipping-number-wrapper');
        document.querySelectorAll('.email-type .details-select .option').forEach(detailsSelectOption => {
            detailsSelectOption.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('details-option-' + detailsSelectOption.dataset.option).selected = true;
                document.getElementById('select-text').innerHTML = detailsSelectOption.dataset.url;
                if (detailsSelectOption.dataset.option == 1 || detailsSelectOption.dataset.option == 4) {
                    if (!shippingNumberWrapper.classList.contains('active')) {
                        shippingNumberWrapper.classList.add('active');
                    }
                } else {
                    if (shippingNumberWrapper.classList.contains('active')) {
                        shippingNumberWrapper.classList.remove('active');
                    }
                }
            });
        });
        const manuelInnerText = document.querySelector('.manuel-inner-text');
        const manuelBall = document.querySelector('.manuel-ball');
        const readyMessages = document.getElementById('ready-messages');
        const manuelMessagesId = document.getElementById('manuel-messages-id');
        document.getElementById('manuel_checkbox').addEventListener('change', (e) => {
            e.preventDefault();
            if (readyMessages.classList.contains('hidden')) {
                manuelInnerText.innerHTML = 'Manuel Mesaj Kapalı';
                readyMessages.classList.remove('hidden');
                manuelMessagesId.classList.remove('active');
            } else {
                manuelInnerText.innerHTML = 'Manuel Mesaj Açık';
                readyMessages.classList.add('hidden');
                manuelMessagesId.classList.add('active');
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#btn-hamburger').click(function() {
                $.ajax({
                    url: '<?php echo URL . URL_ADMIN_MENU; ?>',
                    type: 'POST'
                });
            });
        });
    </script>
</body>

</html>