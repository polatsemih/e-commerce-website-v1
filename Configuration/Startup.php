<?php
// ErrorController
define('URL_GO_HOME', 'anasayfaya-don');
define('URL_EXCEPTION', 'sorun');
define('URL_SHUTDOWN', 'hata');
define('URL_USER_BLOCKED', 'engel');
// HomeController
define('URL_ITEMS', 'urunler');
define('URL_ITEM_DETAILS', 'urun');
define('URL_ITEM_SEARCH', 'urun-ara');
define('URL_CART', 'sepet');
define('URL_ADD_CART', 'sepete-ekle');
define('URL_UPDATE_CART', 'sepeti-guncelle');
define('URL_EMPTY_CART', 'sepeti-bosalt');
define('URL_FAVORITES', 'favoriler');
define('URL_AGREEMENTS', 'sartlar-sozlesmeler-politikalar-ve-kosullar');
define('URL_AGREEMENT_TERMS', 'kullanim-sartlari-ve-uyelik-sozlesmesi');
define('URL_AGREEMENT_PRIVACY', 'gizlilik-ve-guvenlik-politikasi');
define('URL_AGREEMENT_RETURN_POLICY', 'iptal-ve-iade-kosullari');
define('URL_LOGOUT', 'cikis');
define('URL_PROFILE', 'profil');
define('URL_PROFILE_INFORMATIONS', 'hesabim');
define('URL_PROFILE_IDENTITY_NUMBER', 'kimlik-numaram');
define('URL_PROFILE_ADDRESS', 'adresim');
define('URL_PROFILE_PASSWORD', 'sifrem');
define('URL_PROFILE_EMAIL', 'emailim');
define('URL_PROFILE_PHONE', 'telefonum');
define('URL_PROFILE_PHOTO', 'profil-fotom');
define('URL_PROFILE_ORDERS', 'siparislerim');
define('URL_PROFILE_UPDATE', 'hesabimi-guncelle');
define('URL_IDENTITY_NUMBER_UPDATE', 'kimlik-numarami-guncelle');
define('URL_PROFILE_2FA', 'iki-asamali-dogrulama-aktif');
define('URL_ADDRESS_CREATE', 'adres-ekle');
define('URL_ADDRESS_UPDATE', 'adres-guncelle');
define('URL_ADDRESS_DELETE', 'adres-kaldir');
define('URL_PASSWORD_UPDATE', 'sifremi-guncelle');
define('URL_EMAIL_UPDATE', 'emailimi-guncelle');
define('URL_EMAIL_UPDATE_CONFIRM', 'yeni-email-dogrulama');
define('URL_PHONE_UPDATE', 'telefon-numarami-guncelle');
define('URL_PROFILE_PHOTO_UPDATE', 'resmimi-guncelle');
define('URL_PROFILE_DELETE', 'hesabimi-sil');
define('URL_ORDER_ADDRESS', 'adres-sec');
define('URL_ORDER_INITIALIZE', 'siparis-ver');
define('URL_INSTALLMENT', 'taksit-sorgula');
define('URL_ORDER_PAYMENT', 'siparis-sonuc');
define('URL_CONTACT', 'iletisim');
// ActionController
define('URL_LOGIN', 'giris');
define('URL_TWO_FA', 'iki-asamali-dogrulama');
define('URL_REGISTER', 'kayit');
define('URL_REGISTER_CONFIRM', 'uyelik-aktiflestirme');
define('URL_REGISTER_CANCEL', 'uyelik-iptal');
define('URL_FORGOT_PASSWORD', 'sifremi-unuttum');
define('URL_RESET_PASSWORD', 'sifre-reset');
define('URL_ADMIN_LOGIN', 'yonetici-giris');
// AccountController
define('URL_ADD_FAVORITES', 'favorilere-ekle');
define('URL_REMOVE_FAVORITE', 'favorilerden-kaldir');
define('URL_COMMENT_CREATE', 'yorum-ekle');
define('URL_COMMENT_UPDATE', 'yorum-guncelle');
define('URL_COMMENT_DELETE', 'yorum-sil');
define('URL_COMMENT_REPLY_CREATE', 'yorum-cevap-ekle');
define('URL_COMMENT_REPLY_UPDATE', 'yorum-cevap-guncelle');
define('URL_COMMENT_REPLY_DELETE', 'yorum-cevap-sil');
define('URL_ADMIN_COMMENT_DELETE', 'yonetici-yorum-sil');
define('URL_ADMIN_COMMENT_REPLY_DELETE', 'yonetici-yorum-cevap-sil');
define('URL_ADMIN_COMMENT_APPROVE', 'yonetici-yorum-onayla');
define('URL_ADMIN_COMMENT_REPLY_APPROVE', 'yonetici-yorum-cevap-onayla');
// AdminController
define('URL_ADMIN_INDEX', 'yonetici');
define('URL_ADMIN_LOGOUT', 'yonetici-cikis');
define('URL_ADMIN_MENU', 'yonetici-menu');
define('URL_ADMIN_LOGS', 'yonetici-istatistikler');
define('URL_ADMIN_LOGS_PAGE', 'goruntulenen-sayfalar');
define('URL_ADMIN_LOGS_USER', 'kullanicilar');
define('URL_ADMIN_LOGS_MESSAGE', 'kullanici-mesajlari');
define('URL_ADMIN_LOGS_ERROR', 'hatalar');
define('URL_ADMIN_LOGS_LOGIN_ACCOUNT', 'hesaba-giris-denemeleri');
define('URL_ADMIN_LOGS_LOGIN', 'giris-denemeleri');
define('URL_ADMIN_LOGS_EMAIL', 'sistem-emailleri');
define('URL_ADMIN_LOGS_EMAIL_ORDER', 'siparis-emailleri');
define('URL_ADMIN_LOGS_CAPTCHA', 'robot-degilim-testleri');
define('URL_ADMIN_LOGS_CAPTCHA_TIMEOUT', 'robot-degilim-kisitlama');
define('URL_ADMIN_ITEMS', 'yonetici-urunler');
define('URL_ADMIN_ITEM_DETAILS', 'yonetici-urun');
define('URL_ADMIN_ITEM_UPDATE', 'yonetici-urun-guncelle');
define('URL_ADMIN_ITEM_DELETE', 'yonetici-urun-sil');
define('URL_ADMIN_ITEM_CREATE', 'yonetici-urun-ekle');
define('URL_ADMIN_USERS', 'yonetici-kullanicilar');
define('URL_ADMIN_SEND_EMAIL', 'yonetici-email-gonder');
define('URL_ADMIN_USER_DETAILS', 'yonetici-kullanici');
define('URL_ADMIN_PROFILE', 'yonetici-profil');
define('URL_ADMIN_PROFILE_INFORMATIONS', 'isim-degistir');
define('URL_ADMIN_PROFILE_PASSWORD', 'sifre-degistir');
define('URL_ADMIN_PROFILE_PHOTO', 'resim-degistir');
define('URL_ADMIN_PROFILE_UPDATE', 'yonetici-ismimi-guncelle');
define('URL_ADMIN_PASSWORD_UPDATE', 'yonetici-sifremi-guncelle');
define('URL_ADMIN_PROFILE_PHOTO_UPDATE', 'yonetici-resmimi-guncelle');
define('URL_ADMIN_ORDERS', 'yonetici-siparisler');
define('URL_ADMIN_ORDER_DETAILS', 'yonetici-siparis');
define('URL_ADMIN_ORDER_ERRORS', 'yonetici-siparis-hatalari');
define('URL_ADMIN_ORDER_CONVERSATION_ERROR', 'gorusme-hatalari');
define('URL_ADMIN_ORDER_STATUS_ERROR', 'durum-hatalari');
define('URL_ADMIN_ORDER_STATUS_CODES', 'durum-kodlari');
define('URL_ADMIN_ORDER_MD_STATUS_CODES', 'md-durum-kodlari');
define('URL_ADMIN_USER_BLOCK', 'yonetici-kullanici-engelle');
define('URL_ADMIN_ITEM_COMMENT', 'yonetici-urun-yorumlar');
define('URL_MAPS', array(
    // ErrorController
    array('url' => 'anasayfaya-don', 'controller' => 'ErrorController', 'action' => 'GoHome'),
    array('url' => 'sorun', 'controller' => 'ErrorController', 'action' => 'Exception'),
    array('url' => 'hata', 'controller' => 'ErrorController', 'action' => 'ShutDown'),
    array('url' => 'engel', 'controller' => 'ErrorController', 'action' => 'UserBlocked'),
    // HomeController
    array('url_pattern' => 'urunler/?', 'controller' => 'HomeController', 'action' => 'Items'),
    array('url_pattern' => 'urun/?', 'controller' => 'HomeController', 'action' => 'ItemDetails'),
    array('url' => 'urun-ara', 'controller' => 'HomeController', 'action' => 'ItemSearch'),
    array('url' => 'sepet', 'controller' => 'HomeController', 'action' => 'Cart'),
    array('url' => 'sepete-ekle', 'controller' => 'HomeController', 'action' => 'AddCart'),
    array('url' => 'sepeti-guncelle', 'controller' => 'HomeController', 'action' => 'UpdateCart'),
    array('url' => 'sepeti-bosalt', 'controller' => 'HomeController', 'action' => 'EmptyCart'),
    array('url' => 'favoriler', 'controller' => 'HomeController', 'action' => 'Favorites'),
    array('url_pattern' => 'sartlar-sozlesmeler-politikalar-ve-kosullar/?', 'controller' => 'HomeController', 'action' => 'Agreements'),
    array('url' => 'cikis', 'controller' => 'HomeController', 'action' => 'LogOut'),
    array('url_pattern' => 'profil/?', 'controller' => 'HomeController', 'action' => 'Profile'),
    array('url' => 'hesabimi-guncelle', 'controller' => 'HomeController', 'action' => 'ProfileInformationsUpdate'),
    array('url' => 'kimlik-numarami-guncelle', 'controller' => 'HomeController', 'action' => 'ProfileIdentityNumberUpdate'),
    array('url' => 'iki-asamali-dogrulama-aktif', 'controller' => 'HomeController', 'action' => 'ProfileTwoFa'),
    array('url' => 'adres-ekle', 'controller' => 'HomeController', 'action' => 'ProfileCreateAddress'),
    array('url' => 'adres-guncelle', 'controller' => 'HomeController', 'action' => 'ProfileUpdateAddress'),
    array('url' => 'adres-kaldir', 'controller' => 'HomeController', 'action' => 'ProfileDeleteAddress'),
    array('url' => 'sifremi-guncelle', 'controller' => 'HomeController', 'action' => 'ProfilePasswordUpdate'),
    array('url' => 'emailimi-guncelle', 'controller' => 'HomeController', 'action' => 'ProfileEmailUpdate'),
    array('url' => 'yeni-email-dogrulama', 'controller' => 'HomeController', 'action' => 'EmailUpdateConfirm'),
    array('url' => 'telefon-numarami-guncelle', 'controller' => 'HomeController', 'action' => 'ProfilePhoneUpdate'),
    array('url' => 'resmimi-guncelle', 'controller' => 'HomeController', 'action' => 'ProfilePhotoUpdate'),
    array('url' => 'hesabimi-sil', 'controller' => 'HomeController', 'action' => 'ProfileDelete'),
    array('url' => 'adres-sec', 'controller' => 'HomeController', 'action' => 'OrderAddressPost'),
    array('url' => 'siparis-ver', 'controller' => 'HomeController', 'action' => 'OrderInitialize'),
    array('url' => 'taksit-sorgula', 'controller' => 'AccountController', 'action' => 'OrderInstallment'),
    array('url' => 'siparis-sonuc', 'controller' => 'OrderController', 'action' => 'OrderPayment'),
    array('url' => 'iletisim', 'controller' => 'HomeController', 'action' => 'Contact'),
    // ActionController
    array('url' => 'giris', 'controller' => 'ActionController', 'action' => 'Login'),
    array('url' => 'iki-asamali-dogrulama', 'controller' => 'ActionController', 'action' => 'TwoFA'),
    array('url' => 'kayit', 'controller' => 'ActionController', 'action' => 'Register'),
    array('url' => 'uyelik-aktiflestirme', 'controller' => 'ActionController', 'action' => 'RegisterConfirm'),
    array('url' => 'uyelik-iptal', 'controller' => 'ActionController', 'action' => 'RegisterCancel'),
    array('url' => 'sifremi-unuttum', 'controller' => 'ActionController', 'action' => 'ForgotPassword'),
    array('url' => 'sifre-reset', 'controller' => 'ActionController', 'action' => 'ResetPassword'),
    array('url' => 'yonetici-giris', 'controller' => 'ActionController', 'action' => 'AdminLogin'),
    // AccountController
    array('url' => 'favorilere-ekle', 'controller' => 'AccountController', 'action' => 'AddFavorites'),
    array('url' => 'favorilerden-kaldir', 'controller' => 'AccountController', 'action' => 'RemoveFavorite'),
    array('url' => 'yorum-ekle', 'controller' => 'AccountController', 'action' => 'CommentCreate'),
    array('url' => 'yorum-guncelle', 'controller' => 'AccountController', 'action' => 'CommentUpdate'),
    array('url' => 'yorum-sil', 'controller' => 'AccountController', 'action' => 'CommentDelete'),
    array('url' => 'yorum-cevap-ekle', 'controller' => 'AccountController', 'action' => 'CommentReplyCreate'),
    array('url' => 'yorum-cevap-guncelle', 'controller' => 'AccountController', 'action' => 'CommentReplyUpdate'),
    array('url' => 'yorum-cevap-sil', 'controller' => 'AccountController', 'action' => 'CommentReplyDelete'),
    array('url' => 'yonetici-yorum-sil', 'controller' => 'AccountController', 'action' => 'AdminCommentDelete'),
    array('url' => 'yonetici-yorum-cevap-sil', 'controller' => 'AccountController', 'action' => 'AdminCommentReplyDelete'),
    array('url' => 'yonetici-yorum-onayla', 'controller' => 'AccountController', 'action' => 'AdminCommentApprove'),
    array('url' => 'yonetici-yorum-cevap-onayla', 'controller' => 'AccountController', 'action' => 'AdminCommentReplyApprove'),
    // AdminController
    array('url' => 'yonetici', 'controller' => 'AdminController', 'action' => 'Index'),
    array('url' => 'yonetici-cikis', 'controller' => 'AdminController', 'action' => 'LogOut'),
    array('url' => 'yonetici-menu', 'controller' => 'AdminController', 'action' => 'Menu'),
    array('url_pattern' => 'yonetici-istatistikler/?', 'controller' => 'AdminController', 'action' => 'Statistics'),
    array('url' => 'yonetici-urunler', 'controller' => 'AdminController', 'action' => 'Items'),
    array('url_pattern' => 'yonetici-urun/?', 'controller' => 'AdminController', 'action' => 'ItemDetails'),
    array('url' => 'yonetici-urun-guncelle', 'controller' => 'AdminController', 'action' => 'ItemUpdate'),
    array('url' => 'yonetici-urun-sil', 'controller' => 'AdminController', 'action' => 'ItemDelete'),
    array('url' => 'yonetici-urun-ekle', 'controller' => 'AdminController', 'action' => 'ItemCreate'),
    array('url' => 'yonetici-kullanicilar', 'controller' => 'AdminController', 'action' => 'Users'),
    array('url' => 'yonetici-email-gonder', 'controller' => 'AdminController', 'action' => 'SendEmail'),
    array('url_pattern' => 'yonetici-kullanici/?', 'controller' => 'AdminController', 'action' => 'UserDetails'),
    array('url_pattern' => 'yonetici-profil/?', 'controller' => 'AdminController', 'action' => 'Profile'),
    array('url' => 'yonetici-ismimi-guncelle', 'controller' => 'AdminController', 'action' => 'ProfileInformationsUpdate'),
    array('url' => 'yonetici-sifremi-guncelle', 'controller' => 'AdminController', 'action' => 'ProfilePasswordUpdate'),
    array('url' => 'yonetici-resmimi-guncelle', 'controller' => 'AdminController', 'action' => 'ProfilePhotoUpdate'),
    array('url' => 'yonetici-siparisler', 'controller' => 'AdminController', 'action' => 'Orders'),
    array('url_pattern' => 'yonetici-siparis/?', 'controller' => 'AdminController', 'action' => 'OrderDetails'),
    array('url_pattern' => 'yonetici-siparis-hatalari/?', 'controller' => 'AdminController', 'action' => 'OrderErrors'),
    array('url' => 'yonetici-kullanici-engelle', 'controller' => 'AdminController', 'action' => 'UserBlock'),
    array('url' => 'yonetici-urun-yorumlar', 'controller' => 'AdminController', 'action' => 'ItemComments')
));