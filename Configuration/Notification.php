<?php
// Notification errors
define('GENERAL_ERROR', 'Bir hata oldu. Lütfen tekrar deneyiniz.');
define('FORM_INPUT_ERROR', 'Bir hata oldu. Lütfen tekrar deneyiniz.');
define('SET_CSRF_ERROR', 'Bir hata oldu. Lütfen sayfayı yenileyin.');
define('COUNT_FAIL_LOGIN_ERROR', 'Çok fazla hatalı deneme yaptınız.');
define('CAPTCHA_ERROR', 'Ben bir insanım testi başarısız. Lütfen tekrar deneyin.');
define('CAPTCHA_TIMEOUT_1', 'Ben bir insanım testinde başarısız oldunuz. ');
define('CAPTCHA_TIMEOUT_2', ' dakika sonra tekrar deneyin.');
define('LOGIN_ERROR', 'Kullanıcı bilgileri yanlış');
define('DATABASE_ERROR', 'Beklenmedik bir hata oldu. Lütfen tekrar deneyin.');
define('CAPTCHA_BANNES_ERROR', 'Ben bir insanım testinde fazla sayıda başarısız oldunuz.');
define('VERIFY_TOKEN_TIMEOUT_ERROR', 'Doğrulama kodu zaman aşımına uğradı. Lütfen giriş yaparak tekrar deneyiniz.');
define('VERIFY_TOKEN_WRONG_TOKEN_ERROR', 'Doğrulama kodunu hatalı girdiniz. Lütfen giriş yaparak tekrar deneyiniz.');
define('VERIFY_TOKEN_ERROR', 'Bir hata oldu. Lütfen tekrar deneyin.');
define('TWO_FA_NOT_ENABLE_ERROR', 'İki aşamalı doğrulamanız etkin değil. Giriş yapamazsınız.');
define('CONFIRM_EMAIL_SUCCESS', 'Üyeliğiniz başarıyla aktif edildi.' . BRAND . ' hesabınıza giriş yapabilirsiniz.');
define('PASSWORDS_NOT_SAME', 'Şifreler aynı olmalıdır');
define('EMAIL_HAS_REGISTERED', 'Email adresi zaten kayıtlı. Bilgileriniz ile giriş yapabilirsiniz.');
define('CANCEL_REGISTER_ERROR_IS_SHOPPED', 'Hesabınızda işlem gören satın alma işlemi mevcut. Kabul ettiğiniz sözleşme gereği, satın alma işlemi tamamlana kadar hesabınızı silemezsiniz.');
define('CANCEL_REGISTER_SUCCESS', BRAND . ' hesabınız başarıyla silindi.');
define('CANCEL_REGISTER_NOT_FOUND', 'Silinecek hesap bulunamadı');
define('CANCEL_REGISTER_TITLE', 'Üyelik İptal');
define('FORGOT_PASSWORD_RESULT', 'Şifre sıfırlama linki email adresinize gönderildi.');
define('RESET_PWD_ERROR', 'Bir hata oldu. Lütfen tekrar deneyin.');
define('RESET_PWD_SUCCESS', 'Şifreniz başarıyla güncellendi. Yeni şifreniz ile giriş yapabilirsiniz.');
define('FORGOT_PASSWORD_NO_EMAIL', 'Girilen email adresine ait kayıtlı bir '.BRAND.' hesabı yok');
define('LOGIN_WITH_ADMIN_LOGIN', 'Yönetici panelinden giriş yapın');
// Comment
define('ERROR_COMMENT_CREATE', 'Yorum oluşturulurken bir hata oldu. Lütfen tekrar deneyiniz.');
define('SUCCESS_COMMENT_CREATE', 'Yorum başarıyla eklendi. Değerli düşüncelerinizi paylaştığınız için teşekkür ederiz.');
define('ERROR_COMMENT_UPDATE', 'Yorum güncellenirken bir hata oldu. Lütfen tekrar deneyiniz.');
define('SUCCESS_COMMENT_UPDATE', 'Yorum başarıyla güncellendi');
define('ERROR_COMMENT_DELETE', 'Yorum silinirken bir hata oldu. Lütfen tekrar deneyiniz.');
define('SUCCESS_COMMENT_DELETE', 'Yorum başarıyla silindi');
define('COMMENT_APPROVED', 'Yorum herkese görünür olarak güncellendi');
define('COMMENT_DISAPPROVED', 'Yorum görünmez olarak güncellendi');
// Input errors
define('ERROR_MESSAGE_EMPTY_EMAIL', 'Email adresinizi girin');
define('ERROR_MAX_LENGTH_EMAIL', 'Email adresi 320 karakterden fazla olamaz');
define('ERROR_NOT_VALID_EMAIL', 'Geçerli bir email adresi girin.');
define('ERROR_MESSAGE_EMPTY_PASSWORD', 'Şifrenizi girin');
define('ERROR_MAX_LENGTH_PASSWORD', 'Şifre 11 karakterden az olamaz');
define('ERROR_PASSWORD_WHITE_SPACE', 'Şifreniz boşluk karakteri içermemelidir');
define('ERROR_MESSAGE_EMPTY_REPASSWORD', 'Şifrenizi tekrar girin');
define('ERROR_MAX_LENGTH_REPASSWORD', 'Şifre tekrarı 11 karakterden az olamaz');
define('ERROR_REPASSWORD_WHITE_SPACE', 'Şifre tekrarı boşluk karakteri içermemelidir');
define('ERROR_MESSAGE_EMPTY_CSRF', 'Form gönderilirken bir hata oldu. Lütfen tekrar deneyin.');
define('ERROR_MESSAGE_CAPTCHA', 'Ben bir insanım testi başarısız. Lütfen tekrar deneyin.');
define('ERROR_MESSAGE_EMPTY_TWO_FA_TOKEN', 'Doğrulama kodunu hatalı girdiniz. Tekrar giriş yapın.');
define('ERROR_MESSAGE_EMPTY_CONFIRM_EMAIL_TOKEN', 'Doğrulama kodunu hatalı girdiniz. Lütfen giriş yaparak tekrar deneyiniz.');
define('ERROR_MESSAGE_EMPTY_ACCEPT_REGISTER_TERMS', 'Kayıt olabilmek için gizlilik sözleşmesini ve kullanım şartlarını kabul etmelisiniz.');
define('ERROR_NOT_VALID_PASSWORD', 'Şifre en az bir küçük harf, bir büyük harf ve bir rakam içermelidir.');
// Comment
define('ERROR_MESSAGE_EMPTY_COMMENT', 'Yorum alanı boş olamaz');
define('ERROR_MAX_LENGTH_COMMENT', 'Yorum 500 karakterden fazla olamaz');