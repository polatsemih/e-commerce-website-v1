<?php
define('URL_MAPS', array(
    // HomeController
    array('pattern' => 'urunler/?', 'controller' => 'HomeController', 'action' => 'Items'),
    array('pattern' => 'urun/?', 'controller' => 'HomeController', 'action' => 'ItemDetails'),
    // ActionController
    array('url' => 'yonetici-giris', 'controller' => 'ActionController', 'action' => 'AdminLogin'),
    array('url' => 'giris', 'controller' => 'ActionController', 'action' => 'Login'),
    array('url' => 'dogrula', 'controller' => 'ActionController', 'action' => 'VerifyToken'),
    array('url' => 'kayit-iptal', 'controller' => 'ActionController', 'action' => 'VerifyLink'),
    array('url' => 'kayit', 'controller' => 'ActionController', 'action' => 'Register'),
    array('url' => 'sifremi-unuttum', 'controller' => 'ActionController', 'action' => 'ForgotPassword'),
    array('url' => 'sifre-reset', 'controller' => 'ActionController', 'action' => 'ResetPassword'),
    // AccountController
    array('url' => 'cikis', 'controller' => 'AccountController', 'action' => 'LogOut'),
    array('url' => 'yorum-ekle', 'controller' => 'AccountController', 'action' => 'CommentCreate'),
    array('url' => 'yorum-guncelle', 'controller' => 'AccountController', 'action' => 'CommentUpdate'),
    array('url' => 'yorum-sil', 'controller' => 'AccountController', 'action' => 'CommentDelete'),
    array('url' => 'yorum-cevap-ekle', 'controller' => 'AccountController', 'action' => 'CommentReplyCreate'),
    array('url' => 'yorum-cevap-guncelle', 'controller' => 'AccountController', 'action' => 'CommentReplyUpdate'),
    array('url' => 'yorum-cevap-sil', 'controller' => 'AccountController', 'action' => 'CommentReplyDelete'),
    // AdminController
    array('url' => 'yonetici-yorum-sil', 'controller' => 'AdminController', 'action' => 'AdminCommentDelete'),
    array('url' => 'yonetici-yorum-onayla', 'controller' => 'AdminController', 'action' => 'AdminCommentApprove'),
    array('url' => 'yonetici-yorum-cevap-sil', 'controller' => 'AdminController', 'action' => 'AdminCommentReplyDelete'),
    array('url' => 'yonetici-yorum-cevap-onayla', 'controller' => 'AdminController', 'action' => 'AdminCommentReplyApprove'),




    



    // ErrorController
    array('url' => '404', 'controller' => 'ErrorController', 'action' => 'NotFound'),
    // HomeController
    array('url' => 'sepete-ekle', 'controller' => 'HomeController', 'action' => 'AddToCart'),
    array('url' => 'profil', 'controller' => 'HomeController', 'action' => 'Profile'),
    array('url' => 'ayarlar', 'controller' => 'HomeController', 'action' => 'Settings'),
    array('url' => 'sepet', 'controller' => 'HomeController', 'action' => 'ShoppingCart'),
    array('url' => 'favoriler', 'controller' => 'HomeController', 'action' => 'Favorites'),
    // AdminController
    array('url' => 'yonetici', 'controller' => 'AdminController', 'action' => 'Index'),
    array('url' => 'yonetici/urunler', 'controller' => 'AdminController', 'action' => 'Items'),
    array('url' => 'yonetici/urunler/ekle', 'controller' => 'AdminController', 'action' => 'ItemCreate'),
    array('url' => 'yonetici/urunguncelle', 'controller' => 'AdminController', 'action' => 'ItemUpdate'),
    array('url' => 'yonetici/urunsil', 'controller' => 'AdminController', 'action' => 'ItemDelete'),
    array('url' => 'yonetici/filtreler', 'controller' => 'AdminController', 'action' => 'Filters'),
    array('url' => 'yonetici/filtreler/ekle', 'controller' => 'AdminController', 'action' => 'FilterCreate'),
    array('url' => 'yonetici/filtreguncelle', 'controller' => 'AdminController', 'action' => 'FilterUpdate'),
    array('url' => 'yonetici/filtresil', 'controller' => 'AdminController', 'action' => 'FilterDelete'),
    array('url' => 'yonetici/altfiltreler/ekle', 'controller' => 'AdminController', 'action' => 'FilterSubCreate'),
    array('url' => 'yonetici/altfiltreguncelle', 'controller' => 'AdminController', 'action' => 'FilterSubUpdate'),
    array('url' => 'yonetici/altfiltresil', 'controller' => 'AdminController', 'action' => 'FilterSubDelete'),
    array('url' => 'yonetici/kullanicilar', 'controller' => 'AdminController', 'action' => 'Users'),
    array('url' => 'yonetici/kullanicilar/ekle', 'controller' => 'AdminController', 'action' => 'UserCreate'),
    array('url' => 'yonetici/kullaniciguncelle', 'controller' => 'AdminController', 'action' => 'UserUpdate'),
    array('url' => 'yonetici/kullanicisil', 'controller' => 'AdminController', 'action' => 'UserDelete'),
    array('url' => 'yonetici/roller', 'controller' => 'AdminController', 'action' => 'Roles'),
    array('url' => 'yonetici/roller/ekle', 'controller' => 'AdminController', 'action' => 'RoleCreate'),
    array('url' => 'yonetici/rolguncelle', 'controller' => 'AdminController', 'action' => 'RoleUpdate'),
    array('url' => 'yonetici/rolsil', 'controller' => 'AdminController', 'action' => 'RoleDelete'),
    array('url' => 'yonetici/reklambilgileri', 'controller' => 'AdminController', 'action' => 'AdvertisingInfos'),
    array('url' => 'yonetici/profil', 'controller' => 'AdminController', 'action' => 'Profile'),
    array('url' => 'yonetici/sifreguncelle', 'controller' => 'AdminController', 'action' => 'PasswordChange'),
    array('url' => 'yonetici/profilresmiguncelle', 'controller' => 'AdminController', 'action' => 'ProfilePhotoChange'),
    array('url' => 'yonetici/ayarlar', 'controller' => 'AdminController', 'action' => 'Settings'),
    // AdminController
    array('pattern' => 'yonetici/urun/?', 'controller' => 'AdminController', 'action' => 'ItemDetails'),
    array('pattern' => 'yonetici/urunyorumlari/?', 'controller' => 'AdminController', 'action' => 'ItemComments'),
    array('pattern' => 'yonetici/filtre/?', 'controller' => 'AdminController', 'action' => 'FilterDetails'),
    array('pattern' => 'yonetici/altfiltreler/?', 'controller' => 'AdminController', 'action' => 'FilterSubDetails'),
    array('pattern' => 'yonetici/kullanici/?', 'controller' => 'AdminController', 'action' => 'UserDetails'),
    array('pattern' => 'yonetici/rol/?', 'controller' => 'AdminController', 'action' => 'RoleDetails')
));

// HomeController
define('URL_ITEMS', 'urunler');
define('URL_ITEM_DETAILS', 'urun');
// ActionController
define('URL_ADMIN_LOGIN', 'yonetici-giris');
define('URL_LOGIN', 'giris');
define('URL_VERIFY_TOKEN', 'dogrula');
define('URL_VERIFY_LINK', 'kayit-iptal');
define('URL_REGISTER', 'kayit');
define('URL_FORGOT_PASSWORD', 'sifremi-unuttum');
define('URL_RESET_PASSWORD', 'sifre-reset');
// AccountController
define('URL_LOGOUT', 'cikis');
define('URL_COMMENT_CREATE', 'yorum-ekle');
define('URL_COMMENT_UPDATE', 'yorum-guncelle');
define('URL_COMMENT_DELETE', 'yorum-sil');
define('URL_COMMENT_REPLY_CREATE', 'yorum-cevap-ekle');
define('URL_COMMENT_REPLY_UPDATE', 'yorum-cevap-guncelle');
define('URL_COMMENT_REPLY_DELETE', 'yorum-cevap-sil');
// AdminController
define('URL_ADMIN_COMMENT_DELETE', 'yonetici-yorum-sil');
define('URL_ADMIN_COMMENT_APPROVE', 'yonetici-yorum-onayla');
define('URL_ADMIN_COMMENT_REPLY_DELETE', 'yonetici-yorum-cevap-sil');
define('URL_ADMIN_COMMENT_REPLY_APPROVE', 'yonetici-yorum-cevap-onayla');










// HomeController
define('URL_ADD_TO_CART', 'sepete-ekle');
define('URL_PROFILE', 'profil');
define('URL_SETTINGS', 'ayarlar');
define('URL_SHOPPINGCART', 'sepet');
define('URL_FAVORITES', 'favoriler');
// HomeController
define('URL_NOTFOUND', '404');
// AdminController
define('URL_ADMININDEX', 'yonetici');
define('URL_ADMINITEMS', 'yonetici/urunler');
define('URL_ITEMCREATE', 'yonetici/urunler/ekle');
define('URL_ITEMUPDATE', 'yonetici/urunguncelle');
define('URL_ITEMDELETE', 'yonetici/urunsil');
define('URL_FILTERS', 'yonetici/filtreler');
define('URL_FILTERCREATE', 'yonetici/filtreler/ekle');
define('URL_FILTERUPDATE', 'yonetici/filtreguncelle');
define('URL_FILTERDELETE', 'yonetici/filtresil');
define('URL_FILTERSUBCREATE', 'yonetici/altfiltreler/ekle');
define('URL_FILTERSUBUPDATE', 'yonetici/altfiltreguncelle');
define('URL_FILTERSUBDELETE', 'yonetici/altfiltresil');
define('URL_USERS', 'yonetici/kullanicilar');
define('URL_USERCREATE', 'yonetici/kullanicilar/ekle');
define('URL_USERUPDATE', 'yonetici/kullaniciguncelle');
define('URL_USERDELETE', 'yonetici/kullanicisil');
define('URL_ROLES', 'yonetici/roller');
define('URL_ROLECREATE', 'yonetici/roller/ekle');
define('URL_ROLEUPDATE', 'yonetici/rolguncelle');
define('URL_ROLEDELETE', 'yonetici/rolsil');
define('URL_ADVERTISINGINFOS', 'yonetici/reklambilgileri');
define('URL_ADMINPROFILE', 'yonetici/profil');
define('URL_ADMINPASSWORDCHANGE', 'yonetici/sifreguncelle');
define('URL_ADMINPROFILEPHOTOCHANGE', 'yonetici/profilresmiguncelle');
define('URL_ADMINSETTINGS', 'yonetici/ayarlar');
// AdminController
define('URL_ITEMCOMMENTS', 'yonetici/urunyorumlari');
define('URL_ADMINITEMDETAILS', 'yonetici/urun');
define('URL_FILTERDETAILS', 'yonetici/filtre');
define('URL_FILTERSUBDETAILS', 'yonetici/altfiltreler');
define('URL_USERDETAILS', 'yonetici/kullanici');
define('URL_ROLEDETAILS', 'yonetici/rol');