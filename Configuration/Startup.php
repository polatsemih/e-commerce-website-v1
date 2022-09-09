<?php
// ErrorController
define('URL_EXCEPTION', 'sorun');
define('URL_SHUTDOWN', 'hata');
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
// ActionController
define('URL_LOGIN', 'giris');
define('URL_REGISTER', 'kayit');
define('URL_REGISTER_CONFIRM', 'uyelik-aktiflestirme');
define('URL_REGISTER_CANCEL', 'uyelik-iptal');
define('URL_FORGOT_PASSWORD', 'sifremi-unuttum');
define('URL_RESET_PASSWORD', 'sifre-reset');
define('URL_ADMIN_LOGIN', 'yonetici-giris');
// AccountController
define('URL_LOGOUT', 'cikis');
define('URL_ADD_FAVORITES', 'favorilere-ekle');
define('URL_REMOVE_FAVORITE', 'favorilerden-kaldir');
define('URL_COMMENT_CREATE', 'yorum-ekle');
define('URL_COMMENT_UPDATE', 'yorum-guncelle');
define('URL_COMMENT_DELETE', 'yorum-sil');
define('URL_COMMENT_REPLY_CREATE', 'yorum-cevap-ekle');
define('URL_COMMENT_REPLY_UPDATE', 'yorum-cevap-guncelle');
define('URL_COMMENT_REPLY_DELETE', 'yorum-cevap-sil');
define('URL_PROFILE', 'profil');
define('URL_PROFILE_INFORMATIONS', 'bilgilerim');
define('URL_PROFILE_PASSWORD', 'sifrem');
define('URL_PROFILE_EMAIL', 'emailim');
define('URL_PROFILE_PHONE', 'telefonum');
define('URL_PROFILE_PHOTO', 'profil-fotom');
define('URL_PROFILE_ORDERS', 'siparislerim');
define('URL_PROFILE_UPDATE', 'profil-bilgilerimi-guncelle');
define('URL_PASSWORD_UPDATE', 'sifremi-guncelle');
define('URL_EMAIL_UPDATE', 'emailimi-guncelle');
define('URL_PROFILE_PHOTO_UPDATE', 'resmimi-guncelle');
define('URL_PHONE_UPDATE', 'telefon-numarami-guncelle');
define('URL_PROFILE_DELETE', 'hesabimi-sil');
// HalfAdminController
define('URL_ADMIN_COMMENT_DELETE', 'yonetici-yorum-sil');
define('URL_ADMIN_COMMENT_APPROVE', 'yonetici-yorum-onayla');
define('URL_ADMIN_COMMENT_REPLY_DELETE', 'yonetici-yorum-cevap-sil');
define('URL_ADMIN_COMMENT_REPLY_APPROVE', 'yonetici-yorum-cevap-onayla');
// AdminController
define('URL_ADMIN_INDEX', 'yonetici');

define('URL_MAPS', array(
    // ErrorController
    array('url' => 'sorun', 'controller' => 'ErrorController', 'action' => 'Exception'),
    array('url' => 'hata', 'controller' => 'ErrorController', 'action' => 'ShutDown'),
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
    // ActionController
    array('url' => 'giris', 'controller' => 'ActionController', 'action' => 'Login'),
    array('url' => 'kayit', 'controller' => 'ActionController', 'action' => 'Register'),
    array('url' => 'uyelik-aktiflestirme', 'controller' => 'ActionController', 'action' => 'RegisterConfirm'),
    array('url' => 'uyelik-iptal', 'controller' => 'ActionController', 'action' => 'RegisterCancel'),
    array('url' => 'sifremi-unuttum', 'controller' => 'ActionController', 'action' => 'ForgotPassword'),
    array('url' => 'sifre-reset', 'controller' => 'ActionController', 'action' => 'ResetPassword'),
    array('url' => 'yonetici-giris', 'controller' => 'ActionController', 'action' => 'AdminLogin'),
    // AccountController
    array('url' => 'cikis', 'controller' => 'AccountController', 'action' => 'LogOut'),
    array('url' => 'favorilere-ekle', 'controller' => 'AccountController', 'action' => 'AddFavorites'),
    array('url' => 'favorilerden-kaldir', 'controller' => 'AccountController', 'action' => 'RemoveFavorite'),
    array('url' => 'yorum-ekle', 'controller' => 'AccountController', 'action' => 'CommentCreate'),
    array('url' => 'yorum-guncelle', 'controller' => 'AccountController', 'action' => 'CommentUpdate'),
    array('url' => 'yorum-sil', 'controller' => 'AccountController', 'action' => 'CommentDelete'),
    array('url' => 'yorum-cevap-ekle', 'controller' => 'AccountController', 'action' => 'CommentReplyCreate'),
    array('url' => 'yorum-cevap-guncelle', 'controller' => 'AccountController', 'action' => 'CommentReplyUpdate'),
    array('url' => 'yorum-cevap-sil', 'controller' => 'AccountController', 'action' => 'CommentReplyDelete'),
    array('url_pattern' => 'profil/?', 'controller' => 'AccountController', 'action' => 'Profile'),
    array('url' => 'profil-bilgilerimi-guncelle', 'controller' => 'AccountController', 'action' => 'ProfileInformationsUpdate'),
    array('url' => 'sifremi-guncelle', 'controller' => 'AccountController', 'action' => 'ProfilePasswordUpdate'),
    array('url' => 'emailimi-guncelle', 'controller' => 'AccountController', 'action' => 'ProfileEmailUpdate'),
    array('url' => 'resmimi-guncelle', 'controller' => 'AccountController', 'action' => 'ProfilePhotoUpdate'),
    array('url' => 'telefon-numarami-guncelle', 'controller' => 'AccountController', 'action' => 'ProfilePhoneUpdate'),
    array('url' => 'hesabimi-sil', 'controller' => 'AccountController', 'action' => 'ProfileDelete'),
    // HalfAdminController
    array('url' => 'yonetici-yorum-sil', 'controller' => 'AdminController', 'action' => 'AdminCommentDelete'),
    array('url' => 'yonetici-yorum-onayla', 'controller' => 'AdminController', 'action' => 'AdminCommentApprove'),
    array('url' => 'yonetici-yorum-cevap-sil', 'controller' => 'AdminController', 'action' => 'AdminCommentReplyDelete'),
    array('url' => 'yonetici-yorum-cevap-onayla', 'controller' => 'AdminController', 'action' => 'AdminCommentReplyApprove'),
    // AdminController
    array('url' => 'yonetici', 'controller' => 'AdminController', 'action' => 'Index')
));