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
define('TR_NOTIFICATION_ERROR_USER_BLOCKED', BRAND . ' hesabınız engellendiği için işlem yapamazsınız.');
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
define('URL_PROFILE_IDENTITY_TITLE', 'Kimlik - Profil');
define('URL_PROFILE_ADDRESS_TITLE', 'Adres - Profil');
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
// Profile
define('TR_NOTIFICATION_SUCCESS_ACCOUNT_DELETE', BRAND . ' hesabınız başarıyla sonlandırıldı.');
define('TR_NOTIFICATION_ERROR_WRONG_OLD_PASSWORD', 'Güncel şifrenizi hatalı girdiniz');
define('TR_NOTIFICATION_SUCCESS_PROFILE_PASSWORD_UPDATE', 'Şifreniz başarıyla değiştirldi');
define('TR_NOTIFICATION_ERROR_PROFILE_PASSWORD_UPDATE', 'Şifreniz değiştirilirken bir hata oldu. Lütfen daha sonra tekrar deneyin.');
define('TR_NOTIFICATION_ERROR_EMPTY_USER_NAME', 'İsminizi girin');
define('TR_NOTIFICATION_ERROR_EMPTY_USER_LAST_NAME', 'Soy isminizi girin');
define('TR_NOTIFICATION_ERROR_MAX_LIMIT_USER_NAME', 'İsminiz karakter sınırını geçmemelidir');
define('TR_NOTIFICATION_ERROR_MAX_LIMIT_USER_LAST_NAME', 'Soy isminiz karakter sınırını geçmemelidir');
define('TR_NOTIFICATION_SUCCESS_PROFILE_USER_NAME_UPDATE', 'İsminiz başarıyla güncellendi');
define('TR_NOTIFICATION_ERROR_NEW_USER_NAME', 'Yeni isminizi girin');
define('TR_NOTIFICATION_ERROR_PROFILE_NOT_UNIQUE_EMAIL', 'Email adresi zaten kayıtlı');
define('TR_NOTIFICATION_ERROR_PROFILE_NEW_EMAIL', 'Yeni email adresinizi girin');
define('TR_NOTIFICATION_ERROR_EMAIL_UPDATE', 'Email adresiniz güncellenirken bir hata oldu. Lütfen daha sonra tekrar deneyin.');
define('TR_NOTIFICATION_ERROR_EMPTY_PHONE', 'Telefon numaranızı girin');
define('TR_NOTIFICATION_ERROR_NOT_VALID_PHONE', 'Girdiğiniz telefon numarası geçerli değil');
define('TR_NOTIFICATION_ERROR_EMPTY_EMAIL_UPDATE_TOKEN', 'Email doğrulama kodunu hatalı girdiniz. Lütfen tekrar deneyin.');
define('TR_NOTIFICATION_ERROR_EMAIL_UPDATE_TOKEN_EXPIRIED', 'Email doğrulama kodu zaman aşımına uğradı. Lütfen tekrar deneyin.');
define('TR_NOTIFICATION_SUCCESS_EMAIL_UPDATE', 'Email adresiniz başarıyla değiştirildi');
define('TR_NOTIFICATION_SUCCESS_PROFILE_PHOTO_UPDATE', 'Profil fotoğrafı başarıyla değiştirildi');
define('TR_NOTIFICATION_ERROR_PROFILE_PHOTO_UPDATE', 'Profil fotoğrafınızı yükleyin');
define('TR_NOTIFICATION_ERROR_LIMIT_PROFILE_PHOTO_UPDATE', 'Profil fotoğrafınızın boyutu 1 MB dan fazla olmamalıdır');
define('TR_NOTIFICATION_ERROR_IDK_PROFILE_PHOTO_UPDATE', 'Profil fotoğrafınız değiştirilirken bir hata oldu. Lütfen daha sonra tekrar deneyiniz.');
define('TR_NOTIFICATION_ERROR_EXTENSION_PROFILE_PHOTO_UPDATE', 'Profil fotoğrafınızın uzantısı jpeg veya png olmalıdır');
define('TR_NOTIFICATION_ERROR_NEW_PHONE_NUMBER', 'Yeni telefon numaranızı girin');
define('TR_NOTIFICATION_SUCCESS_PROFILE_PHONE_NUMBER_UPDATE', 'Telefon numaranız başarıyla güncellendi');
define('TR_NOTIFICATION_ERROR_EMPTY_CITY', 'Şehir boş olamaz');
define('TR_NOTIFICATION_ERROR_NOT_VALID_CITY', 'Geçerli bir şehir girin');
define('TR_NOTIFICATION_ERROR_EMPTY_COUNTY', 'İlçe boş olamaz');
define('TR_NOTIFICATION_ERROR_NOT_VALID_COUNTY', 'Geçerli bir ilçe girin');
define('TR_NOTIFICATION_ERROR_EMPTY_FULL_ADDRESS', 'Açık adres boş olamaz');
define('TR_NOTIFICATION_ERROR_NOT_VALID_FULL_ADDRESS', 'Geçerli bir açık adres girin');
define('TR_NOTIFICATION_ERROR_EMPTY_ADDRESS_QUICK_NAME', 'Adres adı boş olamaz');
define('TR_NOTIFICATION_ERROR_NOT_VALID_ADDRESS_QUICK_NAME', 'Geçerli bir adres adı girin');
define('TR_NOTIFICATION_ERROR_CREATE_ADDRESS_LIMIT', 'En fazla 5 adet adres ekleyebilirsiniz');
define('TR_NOTIFICATION_ERROR_CREATE_ADDRESS', 'Adres oluşturulurken bir hata oldu. Lütfen daha sonra tekrar deneyin.');
define('TR_NOTIFICATION_SUCCESS_CREATE_ADDRESS', 'Adres başarıyla oluşturuldu');
define('TR_NOTIFICATION_ERROR_UPDATE_ADDRESS', 'Adres güncellenirken bir hata oldu. Lütfen daha sonra tekrar deneyin.');
define('TR_NOTIFICATION_SUCCESS_UPDATE_ADDRESS', 'Adres başarıyla güncellendi');
define('TR_NOTIFICATION_ERROR_DELETE_ADDRESS', 'Adres silinirken bir hata oldu. Lütfen daha sonra tekrar deneyin.');
define('TR_NOTIFICATION_SUCCESS_DELETE_ADDRESS', 'Adres başarıyla silindi');
define('TR_NOTIFICATION_ERROR_COMMENT_USER_EMPTY_NAME', 'Yorum eklemeden önce profilinizden isminizi eklemelisiniz');
define('TR_NOTIFICATION_SUCCESS_PROFILE_2FA_DEACTIVE', '2 aşamalı doğrulama devre dışı bırakıldı');
define('TR_NOTIFICATION_SUCCESS_PROFILE_2FA_ACTIVE', '2 aşamalı doğrulama aktif edildi');
define('TR_NOTIFICATION_ERROR_EMPTY_IDENTITY_NUMBER', 'Kimlik numaranızı girin');
define('TR_NOTIFICATION_ERROR_NOT_VALID_IDENTITY_NUMBER', 'Girdiğiniz kimlik numarası geçerli değil');
define('TR_NOTIFICATION_ERROR_NEW_IDENTITY_NUMBER', 'Güncellemek istediğiniz kimlik numarasını girin');
define('TR_NOTIFICATION_SUCCESS_PROFILE_IDENTITY_NUMBER_UPDATE', 'Kimlik numaranız başarıyla güncellendi');
define('TR_NOTIFICATION_ERROR_EMPTY_CART_NAME', 'Kartınızın üzerindeki ismi girin');
define('TR_NOTIFICATION_ERROR_NOT_VALID_CART_NAME', 'Kartınızın üzerindeki ismi eksiksiz girmelisiniz');
define('TR_NOTIFICATION_ERROR_EMPTY_CART_NUMBER', 'Kartınızın numarasını girin');
define('TR_NOTIFICATION_ERROR_NOT_VALID_CART_NUMBER', 'Kartınızın numarasını yanlış girdiniz');
define('TR_NOTIFICATION_ERROR_EMPTY_CART_EXPIRY_MONTH', 'Kartınızın son kullanma tarihinin ayını girin');
define('TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_MONTH', 'Kartınızın son kullanma tarihinin ayını yanlış girdiniz');
define('TR_NOTIFICATION_ERROR_EMPTY_CART_EXPIRY_YEAR', 'Kartınızın son kullanma tarihinin yılını girin');
define('TR_NOTIFICATION_ERROR_NOT_VALID_CART_EXPIRY_YEAR', 'Kartınızın son kullanma tarihinin yılını yanlış girdiniz');
define('TR_NOTIFICATION_ERROR_EMPTY_CART_CVC', 'Kartınızın güvenlik kodunu girin');
define('TR_NOTIFICATION_ERROR_NOT_VALID_CART_CVC', 'Kartınızın güvenlik kodunu yanlış girdiniz');
define('TR_NOTIFICATION_ERROR_EMPTY_ORDER_TERMS', 'Siparişi onaylamadan önce, sözleşmeyi okumalı ve kabul etmelisiniz');
define('WEB_SHOPPING_PERMISSION_FALSE', 'Teknik bir hatadan dolayı geçici süreliğine alışveriş hizmeti devre dışıdır. Sorundan haberdarız ve sorunun üzerinden çalışıyoruz. Anlayışınız ve sabrınız için teşekkür ederiz.');

define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_NAME', 'Ürün ismini girin');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_NAME_MAX_LENGTH', 'Ürün ismi 45 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_KEYWORDS', 'Ürün anahtar kelimeleri boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_KEYWORDS_MAX_LENGTH', 'Ürün anahtar kelimeleri 100 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_DESCRIPTION', 'Ürün açıklaması boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_DESCRIPTION_MAX_LENGTH', 'Ürün açıklaması 160 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_PRICE', 'Ürün fiyatı boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_PRICE_NOT_POSITIVE', 'Ürün fiyatı geçerli değil');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_DISCOUNT_PRICE', 'Ürün indirimli fiyatı boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_DISCOUNT_PRICE_NOT_POSITIVE', 'Ürün indirimli fiyatı geçerli değil');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_COLLECTION', 'Ürün koleksiyonu boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_COLLECTION_MAX_LENGTH', 'Ürün koleksiyonu 50 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MATERIAL', 'Ürün materyali boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_MATERIAL_MAX_LENGTH', 'Ürün materyali 50 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_CUTMODEL', 'Ürün kesim modeli boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_CUTMODEL_MAX_LENGTH', 'Ürün kesim modeli 50 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_THICKNESS', 'Ürünün kalınlığı boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_THICKNESS_MAX_LENGTH', 'Ürünün kalınlığı 50 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_PATTERN', 'Ürünün deseni boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_PATTERN_MAX_LENGTH', 'Ürünün deseni 50 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_LAPEL', 'Ürünün yakası boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_LAPEL_MAX_LENGTH', 'Ürünün yakası 50 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_SLEEVE_TYPE', 'Ürünün yaka tipi boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_SLEEVE_TYPE_MAX_LENGTH', 'Ürünün yaka tipi 50 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_SLEEVE_LENGTH', 'Ürünün yaka uzunluğu boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_SLEEVE_LENGTH_MAX_LENGTH', 'Ürünün yaka uzunluğu 50 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_WASHING_STYLE', 'Ürünün yıkama stili boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_WASHING_STYLE_MAX_LENGTH', 'Ürünün yıkama stili 200 karakterden fazla olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MODEL_SIZE', 'Ürün görselindeki modelin bedeni boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_SIZE_NOT_VALID', 'Ürün görselindeki modelin bedeni geçerli değil');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MODEL_HEIGHT', 'Ürün görselindeki modelin uzunluğu boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_HEIGHT_NOT_VALID', 'Ürün görselindeki modelin uzunluğu geçerli değil');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_MODEL_WEIGHT', 'Ürün görselindeki modelin kilosu boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_MODEL_WEIGHT_NOT_VALID', 'Ürün görselindeki modelin kilosu geçerli değil');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_TOTAL_QUANTITY', 'Ürün toplam adedi boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_TOTAL_QUANTITY_NOT_VALID', 'Ürün toplam adedi geçerli değil');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_GENDER', 'Ürünün cinsiyet boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_GENDER_MAX_LENGTH', 'Ürünün cinsiyeti geçerli değil');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_CATEGORY', 'Ürünün kategorisi boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_CATEGORY_MAX_LENGTH', 'Ürünün kategorisi geçerli değil');
define('TR_NOTIFICATION_ADMIN_ERROR_QUNATITY_NOT_EQUAL', 'Ürünün beden adetleri, toplam adedine eşit olmalıdır');
define('TR_NOTIFICATION_ERROR_ITEM_IMAGES_EXTENSION', 'Ürün görselinin uzantısı jpeg veya png olmalıdır');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES', 'Ürün görseli oluşturulurken bir hata oldu');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_IMAGES_SIZE_LIMIT', 'Ürün görseli en fazla 10MB büyüklükte olabilir');
define('TR_NOTIFICATION_ADMIN_SUCCESS_ITEM_CREATE', 'Ürün başarıyla oluşturuldu');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_CREATE', 'Ürün oluşturulurken bir hata oldu. Lütfen tekrar deneyin');
define('TR_NOTIFICATION_ADMIN_ERROR_EMPTY_ITEM_IMAGES', 'Ürün görselleri boş olamaz');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_NOT_UNIQUE_URL', 'Ürün ismi eşsiz olmalıdır');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_UPDATE', 'Ürün güncellenirken bir hata oldu. Lütfen tekrar deneyin.');
define('TR_NOTIFICATION_ADMIN_SUCCESS_ITEM_UPDATE', 'Ürün başarıyla güncellendi');
define('TR_NOTIFICATION_ADMIN_SUCCESS_ITEM_DELETE', 'Ürün başarıyla silindi');
define('TR_NOTIFICATION_ADMIN_ERROR_ITEM_DELETE', 'Ürün silinirken bir hata oldu. Lütfen tekrar deneyin.');
define('URL_ADMIN_LOGS_PAGE_TITLE', 'Görüntülenen Sayfalar');
define('URL_ADMIN_LOGS_USER_TITLE', 'Kullanıcılar');
define('URL_ADMIN_LOGS_MESSAGE_TITLE', 'Kullanıcı Mesajları');
define('URL_ADMIN_LOGS_ERROR_TITLE', 'Sistem Hataları');
define('URL_ADMIN_LOGS_LOGIN_ACCOUNT_TITLE', 'Hesaba Girişler');
define('URL_ADMIN_LOGS_LOGIN_TITLE', 'Hatalı Girişler');
define('URL_ADMIN_LOGS_EMAIL_TITLE', 'Sistem Emailleri');
define('URL_ADMIN_LOGS_EMAIL_ORDER_TITLE', 'Sipariş Emailleri');
define('URL_ADMIN_LOGS_CAPTCHA_TITLE', 'Robot Testi');
define('URL_ADMIN_LOGS_CAPTCHA_TIMEOUT_TITLE', 'Robot Testi Kısıtlama');
define('URL_ADMIN_PROFILE_PWD_TITLE', 'Şifre Değiştir - Yönetici');
define('URL_ADMIN_PROFILE_INFO_TITLE', 'İsim Değiştir - Yönetici');
define('URL_ADMIN_PROFILE_PHOTO_TITLE', 'Resim Değiştri - Yönetici');
define('TR_NOTIFICATION_ERROR_EMPTY_CONTACT_MESSAGE', 'Mesajınızı girin');
define('TR_NOTIFICATION_ERROR_MAX_LIMIT_CONTACT_MESSAGE', 'Mesajınız ' . CONTACT_MESSAGE_MAX_LIMIT . ' karakterden fazla olamaz');
define('TR_NOTIFICATION_SUCCESS_CONTACT_SENT', 'Mesajınız başarıyla gönderildi. En kısa zamanda iletişim bilgilerinizden size geri dönüş yapacağız.');
define('URL_ADMIN_ORDER_CONVERSATION_ERROR_TITLE', PAYMENT_AGENCY . ' Sipariş Görüşme Hataları');
define('URL_ADMIN_ORDER_STATUS_ERROR_TITLE', PAYMENT_AGENCY . ' Sipariş Durum Hataları');
define('URL_ADMIN_ORDER_STATUS_CODES_TITLE', 'Durum Kodları');
define('URL_ADMIN_ORDER_MD_STATUS_CODES_TITLE', 'MD Durum Kodları');
define('TR_NOTIFICATION_ADMIN_SUCCESS_USER_BLOCK', 'Kullanıcı başarıyla engellendi');
define('TR_NOTIFICATION_ADMIN_ERROR_USER_BLOCK', 'Kullanıcı engelleme başarısız');
define('TR_NOTIFICATION_ADMIN_SUCCESS_USER_BLOCK_REMOVE', 'Kullanıcı engeli başarıyla kaldırıldı');
define('TR_NOTIFICATION_ADMIN_ERROR_USER_BLOCK_REMOVE', 'Kullanıcı engeli kaldırma başarısız');
define('TR_NOTIFICATION_ADMIN_ERROR_ORDER_STATUS_UPDATE', 'Sipariş durumu değiştirilirken bir hata oldu');
define('TR_NOTIFICATION_ADMIN_SUCCESS_ORDER_STATUS_UPDATE', 'Sipariş durumu başarıyla değiştirildi');
define('TR_NOTIFICATION_ADMIN_ERROR_SEND_EMAIL', 'Kullanıcıya email gönderilirken bir hata oldu');
define('TR_NOTIFICATION_ADMIN_SUCCESS_SEND_EMAIL_1', 'Kullanıcıya');
define('TR_NOTIFICATION_ADMIN_SUCCESS_SEND_EMAIL_2', 'emaili gönderildi');
define('TR_NOTIFICATION_ADMIN_ERROR_ORDER_STATUS_UPDATE_2', 'Sipariş durumu değiştirildi. Ama kargo yöneticisine email gönderilemedi');
define('TR_NOTIFICATION_ADMIN_ERROR_SEND_MAIL_STATUS', 'Emaili göndermeden önce siparişin durumunu değiştirmelisiniz');
define('TR_NOTIFICATION_ERROR_NOT_VALID_SEND_MAIL_MESSAGE', 'Email mesajı 65535 karakterden fazla olamaz');
