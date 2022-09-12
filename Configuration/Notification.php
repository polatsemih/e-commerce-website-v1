<?php
define('TR_NOTIFICATION_ERROR_CSRF', 'Beklenmedik bir hata oldu. Lütfen tekrar deneyin.');
define('TR_NOTIFICATION_EMPTY_HIDDEN_INPUT', 'Bir hata oldu. Lütfen tekrar deneyin.');
define('TR_NOTIFICATION_SUCCESS_AUTHENTICATION_KILLED', 'Oturumunuz sonlandırıldı. Lütfen tekrar giriş yapın.');
define('TR_NOTIFICATION_ERROR_DATABASE', 'Beklenmedik bir hata oldu. Lütfen tekrar deneyin.');
define('TR_NOTIFICATION_SUCCESS_LOG_OUT', 'Oturumunuz başarıyla sonlandırıldı.');
// Comment
define('TR_NOTIFICATION_EMPTY_COMMENT', 'Yorum alanı boş olamaz');
define('TR_NOTIFICATION_ERROR_COMMENT_LIMIT', 'Yorum 500 karakterden fazla olamaz');
define('TR_NOTIFICATION_ERROR_COMMENT_CREATE', 'Yorum oluşturulurken bir hata oldu. Lütfen tekrar deneyin.');
define('TR_NOTIFICATION_SUCCESS_COMMENT_CREATE', 'Yorum başarıyla eklendi. Değerli düşüncelerinizi paylaştığınız için teşekkür ederiz.');
define('TR_NOTIFICATION_ERROR_COMMENT_UPDATE', 'Yorum güncellenirken bir hata oldu. Lütfen tekrar deneyin.');
define('TR_NOTIFICATION_SUCCESS_COMMENT_UPDATE', 'Yorum başarıyla güncellendi');
define('TR_NOTIFICATION_ERROR_COMMENT_DELETE', 'Yorum silinirken bir hata oldu. Lütfen tekrar deneyin.');
define('TR_NOTIFICATION_SUCCESS_COMMENT_DELETE', 'Yorum başarıyla silindi');
define('TR_NOTIFICATION_SUCCESS_COMMENT_APPROVED', 'Yorum herkese görünür olarak güncellendi');
define('TR_NOTIFICATION_SUCCESS_COMMENT_DISAPPROVED', 'Yorum görünmez olarak güncellendi');
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
define('TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL', 'Geçerli bir email adresi girin');
define('TR_NOTIFICATION_ERROR_NOT_UNIQUE_EMAIL', 'Email adresi zaten kayıtlı. Bilgileriniz ile giriş yapabilirsiniz.');
define('TR_NOTIFICATION_ERROR_EMPTY_PASSWORD', 'Şifrenizi girin');
define('TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD', 'Şifreniz boşluk karakteri içermemelidir');
define('TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD', 'Şifreniz '. PASSWORD_MIN_LIMIT .' karakterden az olamaz');
define('TR_NOTIFICATION_ERROR_PATTERN_PASSWORD', 'Şifreniz en az bir küçük harf, bir büyük harf ve bir rakam içermelidir');
define('TR_NOTIFICATION_ERROR_EMPTY_RE_PASSWORD', 'Şifrenizi tekrar girin');
define('TR_NOTIFICATION_ERROR_NOT_SAME_PASSWORDS', 'Şifreler aynı olmalıdır');
define('TR_NOTIFICATION_ERROR_EMPTY_REGISTER_TERMS', 'Kayıt olabilmek için kullanım şartları ve üyelik sözleşmesi ile gizlilik ve güvenlik politikasını kabul etmelisiniz');
define('TR_NOTIFICATION_ERROR_CAPTCHA_BANNED', 'Ben bir insanım testinde fazla sayıda başarısız oldunuz');
define('TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_1', 'Ben bir insanım testinde başarısız oldunuz. ');
define('TR_NOTIFICATION_ERROR_CAPTCHA_TIMEOUT_2', ' dakika sonra tekrar deneyin.');
define('TR_NOTIFICATION_ERROR_CAPTCHA', 'Ben bir insanım testi başarısız. Lütfen tekrar deneyin.');
define('TR_NOTIFICATION_ERROR_REGISTER', 'Kayıt işlemi gerçekleştirilirken bir hata oldu. Lütfen daha sonra tekrar deneyin.');
define('TR_NOTIFICATION_SUCCESS_REGISTER', 'Kayıt işlemi başarılı. Bilgileriniz ile giriş yapabilirsiniz.');
define('TR_NOTIFICATION_ERROR_REGISTER_CONFIRM_TOKEN_EXPIRIED', 'Email doğrulama kodu zaman aşımına uğradı. Lütfen giriş yaparak tekrar deneyin.');
define('TR_NOTIFICATION_ERROR_EMPTY_REGISTER_CONFIRM_TOKEN', 'Email doğrulama kodunu hatalı girdiniz. Lütfen giriş yaparak tekrar deneyin.');
define('TR_NOTIFICATION_ERROR_REGISTER_CONFIRM', 'Üyeliğiniz aktifleştirilirken bir hata oldu. Lütfen daha sonra giriş yaparak tekrar deneyin.');
define('TR_NOTIFICATION_SUCCESS_REGISTER_CONFIRM', 'Üyeliğiniz başarıyla aktif edildi. Bilgileriniz ile ' . BRAND . ' hesabınıza giriş yapabilirsiniz.');
define('TR_NOTIFICATION_ERROR_REGISTER_CANCEL', 'Üyeliğiniz sonlandırılırken bir hata oldu. Lütfen daha sonra tekrar deneyiniz.');
define('TR_NOTIFICATION_SUCCESS_REGISTER_CANCEL', BRAND . ' üyeliğiniz başarıyla sonlandırıldı.');
define('TR_NOTIFICATION_WARNING_REGISTER_CANCEL', 'Üyeliğinizi daha önce aktifleştirdiğiniz için üyelik iptali yapamazsınız. Ancak daha önce hiç ürün satın almadıysanız; ' . BRAND . ' hesabınızdan, profil sekmesi altında hesabınızı iptal edebilirsiniz.');
define('TR_NOTIFICATION_ERROR_LOGIN', 'Giriş işlemi başarısız. Lütfen tekrar deneyin.');
define('TR_NOTIFICATION_ERROR_FORGOT_PASSWORD_NO_EMAIL', 'Girilen email adresine ait kayıtlı bir ' . BRAND . ' hesabı bulunamadı');
define('TR_NOTIFICATION_ERROR_FORGOT_PASSWORD', 'Şifre sıfırlama işlemi gerçekleştirilirken bir hata oldu. Lütfen daha sonra tekrar deneyiniz.');
define('TR_NOTIFICATION_SUCCESS_FORGOT_PASSWORD', 'Şifre sıfırlama linki email adresinize gönderildi.');
define('TR_NOTIFICATION_SUCCESS_RESET_PASSWORD', 'Şifreniz başarıyla güncellendi. Yeni şifreniz ile giriş yapabilirsiniz.');
define('TR_NOTIFICATION_ERROR_LOGIN_INFORMATIONS', 'Kullanıcı bilgileri yanlış');
define('TR_NOTIFICATION_ERROR_TWO_FA_TOKEN_EXPIRIED', 'İki aşamalı doğrulama kodu zaman aşımına uğradı. Lütfen giriş yaparak tekrar deneyin.');
define('TR_NOTIFICATION_ERROR_EMPTY_TWO_FA_TOKEN', 'İki aşamalı doğrulama kodunu hatalı girdiniz. Lütfen giriş yaparak tekrar deneyin.');
define('TR_NOTIFICATION_ERROR_TWO_FA', 'İki aşamalı doğrulama sırasında bir hata oldu. Lütfen daha sonra giriş yaparak tekrar deneyin.');