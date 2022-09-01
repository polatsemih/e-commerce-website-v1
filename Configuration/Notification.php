<?php
define('TR_NOTIFICATION_ERROR_AUTHENTICATION_KILLED', 'Beklenmedik bir hata oldu. Hata devam ederse ' . BRAND . ' çerezlerini temizleyip tekrar deneyiniz.');
define('TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED', 'Oturumunuz sonlandırıldı. Lütfen tekrar giriş yapın.');
define('TR_NOTIFICATION_ERROR_LOG_OUT', 'Oturumunuz sonlandırılırken bir hata oldu. Lütfen tekrar deneyiniz.');
define('TR_NOTIFICATION_SUCCESS_LOG_OUT', 'Oturumunuz başarıyla sonlandırıldı.');
define('TR_NOTIFICATION_ERROR_CSRF', 'Beklenmedik bir hata oldu. Lütfen tekrar deneyiniz.');
define('TR_NOTIFICATION_EMPTY_HIDDEN_INPUT', 'Form gönderilirken bir hata oldu. Lütfen tekrar deneyiniz.');
define('TR_NOTIFICATION_ERROR_DATABASE', 'Beklenmedik bir hata oldu. Lütfen tekrar deneyiniz.');
// Comment
define('TR_NOTIFICATION_EMPTY_COMMENT', 'Yorum alanı boş olamaz');
define('TR_NOTIFICATION_ERROR_COMMENT_LIMIT', 'Yorum 500 karakterden fazla olamaz');
define('TR_NOTIFICATION_ERROR_COMMENT_CREATE', 'Yorum oluşturulurken bir hata oldu. Lütfen tekrar deneyiniz.');
define('TR_NOTIFICATION_SUCCESS_COMMENT_CREATE', 'Yorum başarıyla eklendi. Değerli düşüncelerinizi paylaştığınız için teşekkür ederiz.');
define('TR_NOTIFICATION_ERROR_COMMENT_UPDATE', 'Yorum güncellenirken bir hata oldu. Lütfen tekrar deneyiniz.');
define('TR_NOTIFICATION_SUCCESS_COMMENT_UPDATE', 'Yorum başarıyla güncellendi');
define('TR_NOTIFICATION_ERROR_COMMENT_DELETE', 'Yorum silinirken bir hata oldu. Lütfen tekrar deneyiniz.');
define('TR_NOTIFICATION_SUCCESS_COMMENT_DELETE', 'Yorum başarıyla silindi');
// Favorites
define('TR_NOTIFICATION_ERROR_ADD_TO_FAVORITES', 'Ürün favorilere eklenirken bir hata oldu');
define('TR_NOTIFICATION_SUCCESS_ADD_TO_FAVORITES', 'Ürün başarıyla favorilere eklendi');
define('TR_NOTIFICATION_ERROR_REMOVE_FROM_FAVORITES', 'Ürün favorilerden kaldırılırken bir hata oldu');
define('TR_NOTIFICATION_SUCCESS_REMOVE_FROM_FAVORITES', 'Ürün başarıyla favorilerden kaldırıldı');
// Agremments
define('URL_TERMS_TITLE', 'Kullanım Şartları ve Üyelik Sözleşmesi');
define('URL_PRIVACY_TITLE', 'Gizlilik ve Güvenlik Politikası');
define('URL_RETURN_POLICY_TITLE', 'İptal ve İade Koşulları');
// Cart
define('TR_NOTIFICATION_EMPTY_CART_SIZE', 'Sepete eklemeden önce size uygun bedeni seçin');
define('TR_NOTIFICATION_ERROR_ADD_TO_CART', 'Ürün sepete eklenirken bir hata oldu');
define('TR_NOTIFICATION_ERROR_ADD_TO_CART_STOCK_LIMIT', 'Sepetinize stok adedinden fazla ürünü ekleyemezsiniz');
define('TR_NOTIFICATION_ERROR_ADD_TO_CART_FULL', 'Sepetinize tek seferde bir üründen en fazla 10 adet ekleyebilirsiniz');
define('TR_NOTIFICATION_ERROR_ADD_TO_CART_COOKIE_LIMIT', 'Sepete daha fazla ürün ekleyemezsiniz');
define('TR_NOTIFICATION_ERROR_EMPTY_CART', 'Sepet boşaltılırken bir hata oldu');
define('TR_NOTIFICATION_SUCCESS_EMPTY_CART', 'Sepet başarıyla boşaltıldı');
define('TR_NOTIFICATION_ERROR_UPDATE_CART', 'Sepet güncellenirken bir hata oldu');
// Profile
define('URL_PROFILE_INFO_TITLE', 'Profil');
define('URL_PROFILE_PWD_TITLE', 'Şifre - Profil');
define('URL_PROFILE_EMAIL_TITLE', 'Email - Profil');
define('URL_PROFILE_TEL_TITLE', 'Telefon Numarası - Profil');
define('URL_PROFILE_PHOTO_TITLE', 'Profil Fotoğrafı - Profil');
define('URL_PROFILE_ORDERS_TITLE', 'Siparişlerim - Profil');
// Action
define('TR_NOTIFICATION_ERROR_EMPTY_EMAIL', 'Email adresinizi girin');
define('TR_NOTIFICATION_ERROR_EMPTY_PASSWORD', 'Şifrenizi girin');
















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
define('FORGOT_PASSWORD_NO_EMAIL', 'Girilen email adresine ait kayıtlı bir ' . BRAND . ' hesabı yok');
define('LOGIN_WITH_ADMIN_LOGIN', 'Yönetici panelinden giriş yapın');
define('ERROR_ADD_TO_CART', 'Ürün sepete eklenirken bir hata oldu');
define('ERROR_CART_SELECT_SIZE', 'Ürünü sepete eklemeden önce size uygun bedeni seçin');
define('ERROR_CART_STOCK', 'Sepetinize stok adedinden fazla ürünü ekleyemezsiniz');
define('ERROR_CART_FULL', 'Sepete aynı üründen en fazla 10 adet ekleyebilirsiniz. Daha fazla adette sipariş verebilmek için bizimle iletişime geçin.');
define('ERROR_CART_4000_LIMIT', 'Sepete daha fazla ürün ekleyemezsiniz');
define('ERROR_UPDATE_THE_CART', 'Sepet güncellenirken bir hata oldu.');
define('SUCCESS_ACCOUNT_DELETE', BRAND . ' hesabınız başarıyla silindi');
// Comment
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
