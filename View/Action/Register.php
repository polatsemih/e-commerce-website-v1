<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Kayıt | <?php echo BRAND; ?></title>
    <meta name="robots" content="all" />
    <meta name="description" content="<?php echo BRAND; ?> Kayıt" />
    <meta name="keywords" content="blanck basic, blnckk" />
    <?php require_once 'View/SharedHome/_home_head.php'; ?>
</head>

<body class="noscroll">
    <div class="notification-client"></div>
    <?php require_once 'View/SharedHome/_home_body.php'; ?>
    <main>
        <section class="action-section">
            <div class="action-container">
                <h1 class="title">Kayıt</h1>
                <form id="form-register" action="<?php echo URL . URL_REGISTER; ?>" method="POST" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false" novalidate>
                    <?php if (!empty($web_data['form_token'])) : ?>
                        <input type="hidden" name="form_token" value="<?php echo $web_data['form_token']; ?>">
                    <?php endif; ?>
                    <div class="form-row">
                        <div class="group">
                            <?php if (!empty($web_data['email'])) : ?>
                                <input class="input-action" id="input-email" type="email" name="email" value="<?php echo $web_data['email']; ?>">
                            <?php else : ?>
                                <input class="input-action" id="input-email" type="email" name="email" autofocus>
                            <?php endif; ?>
                            <span class="input-action-label">Email</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <span class="input-message" id="email-message"></span>
                    </div>
                    <div class="form-row">
                        <div class="group">
                            <?php if (!empty($web_data['password'])) : ?>
                                <input class="input-action" id="input-password" type="password" name="password" value="<?php echo $web_data['password']; ?>">
                            <?php else : ?>
                                <input class="input-action" id="input-password" type="password" name="password"<?php echo !empty($web_data['email']) ? ' autofocus' : ''; ?>>
                            <?php endif; ?>
                            <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                            <span class="input-action-label">Şifre</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <span class="input-message" id="password-message"></span>
                    </div>
                    <div class="form-row">
                        <div class="group">
                            <?php if (!empty($web_data['repassword'])) : ?>
                                <input class="input-action" id="input-repassword" type="password" name="repassword" value="<?php echo $web_data['repassword']; ?>">
                            <?php else : ?>
                                <input class="input-action" id="input-repassword" type="password" name="repassword"<?php echo (!empty($web_data['email']) && !empty($web_data['password'])) ? ' autofocus' : ''; ?>>
                            <?php endif; ?>
                            <i class="btn-action-password fas fa-eye-slash" title="Şifreyi Göster"></i>
                            <span class="input-action-label">Şifre Tekrar</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <span class="input-message" id="repassword-message"></span>
                    </div>
                    <div class="form-row">
                        <label for="accept-terms">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="checkbox" id="accept-terms" name="accept_terms">
                                <span class="checkmark register"></span>
                                <span class="checkmark-text"><?php echo BRAND; ?> <button class="action-privacy-link" id="btn-term1">kullanım şartları ve üyelik sözleşmesi</button> ile <button class="action-privacy-link" id="btn-term2">gizlilik ve güvenlik politikasını</button> okudum ve kabul ediyorum.</span>
                            </div>
                        </label>
                        <div id="popup-wrapper" class="term1 disable">
                            <div class="popup-container">
                                <div id="term1-exit" class="popup-exit">
                                    <div class="exit">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                                <h4 class="title">Kullanım Şartları ve Üyelik Sözleşmesi</h4>
                                <p class="text">SİTE KULLANIM ŞARTLARI</p>
                                <p class="text">Lütfen sitemizi kullanmadan evvel bu ‘site kullanım şartları’nı dikkatlice okuyunuz. Bu alışveriş sitesini kullanan ve alışveriş yapan müşterilerimiz aşağıdaki şartları kabul etmiş varsayılmaktadır: Sitemizdeki web sayfaları ve ona bağlı tüm sayfalar (‘site’) ……………………… adresindeki ……………………………….firmasının (Firma) malıdır ve onun tarafından işletilir. Sizler (‘Kullanıcı’) sitede sunulan tüm hizmetleri kullanırken aşağıdaki şartlara tabi olduğunuzu, sitedeki hizmetten yararlanmakla ve kullanmaya devam etmekle; Bağlı olduğunuz yasalara göre sözleşme imzalama hakkına, yetkisine ve hukuki ehliyetine sahip ve 18 yaşın üzerinde olduğunuzu, bu sözleşmeyi okuduğunuzu, anladığınızı ve sözleşmede yazan şartlarla bağlı olduğunuzu kabul etmiş sayılırsınız.</p>
                                <p class="text">İşbu sözleşme taraflara sözleşme konusu site ile ilgili hak ve yükümlülükler yükler ve taraflar işbu sözleşmeyi kabul ettiklerinde bahsi geçen hak ve yükümlülükleri eksiksiz, doğru, zamanında, işbu sözleşmede talep edilen şartlar dâhilinde yerine getireceklerini beyan ederler.</p>
                                <p class="text">1. SORUMLULUKLAR</p>
                                <p class="text">a.Firma, fiyatlar ve sunulan ürün ve hizmetler üzerinde değişiklik yapma hakkını her zaman saklı tutar.</p>
                                <p class="text">b.Firma, üyenin sözleşme konusu hizmetlerden, teknik arızalar dışında yararlandırılacağını kabul ve taahhüt eder.</p>
                                <p class="text">c.Kullanıcı, sitenin kullanımında tersine mühendislik yapmayacağını ya da bunların kaynak kodunu bulmak veya elde etmek amacına yönelik herhangi bir başka işlemde bulunmayacağını aksi halde ve 3. Kişiler nezdinde doğacak zararlardan sorumlu olacağını, hakkında hukuki ve cezai işlem yapılacağını peşinen kabul eder.</p>
                                <p class="text">d.Kullanıcı, site içindeki faaliyetlerinde, sitenin herhangi bir bölümünde veya iletişimlerinde genel ahlaka ve adaba aykırı, kanuna aykırı, 3. Kişilerin haklarını zedeleyen, yanıltıcı, saldırgan, müstehcen, pornografik, kişilik haklarını zedeleyen, telif haklarına aykırı, yasa dışı faaliyetleri teşvik eden içerikler üretmeyeceğini, paylaşmayacağını kabul eder. Aksi halde oluşacak zarardan tamamen kendisi sorumludur ve bu durumda ‘Site’ yetkilileri, bu tür hesapları askıya alabilir, sona erdirebilir, yasal süreç başlatma hakkını saklı tutar. Bu sebeple yargı mercilerinden etkinlik veya kullanıcı hesapları ile ilgili bilgi talepleri gelirse paylaşma hakkını saklı tutar.</p>
                                <p class="text">e.Sitenin üyelerinin birbirleri veya üçüncü şahıslarla olan ilişkileri kendi sorumluluğundadır.</p>
                                <p class="text">2.  Fikri Mülkiyet Hakları</p>
                                <p class="text">2.1. İşbu Site’de yer alan ünvan, işletme adı, marka, patent, logo, tasarım, bilgi ve yöntem gibi tescilli veya tescilsiz tüm fikri mülkiyet hakları site işleteni ve sahibi firmaya veya belirtilen ilgilisine ait olup, ulusal ve uluslararası hukukun koruması altındadır. İşbu Site’nin ziyaret edilmesi veya bu Site’deki hizmetlerden yararlanılması söz konusu fikri mülkiyet hakları konusunda hiçbir hak vermez.</p>
                                <p class="text">2.2. Site’de yer alan bilgiler hiçbir şekilde çoğaltılamaz, yayınlanamaz, kopyalanamaz, sunulamaz ve/veya aktarılamaz. Site’nin bütünü veya bir kısmı diğer bir internet sitesinde izinsiz olarak kullanılamaz. </p>
                                <p class="text">3. Gizli Bilgi</p>
                                <p class="text">3.1. Firma, site üzerinden kullanıcıların ilettiği kişisel bilgileri 3. Kişilere açıklamayacaktır. Bu kişisel bilgiler; kişi adı-soyadı, adresi, telefon numarası, cep telefonu, e-posta adresi gibi Kullanıcı’yı tanımlamaya yönelik her türlü diğer bilgiyi içermekte olup, kısaca ‘Gizli Bilgiler’ olarak anılacaktır.</p>
                                <p class="text">3.2. Kullanıcı, sadece tanıtım, reklam, kampanya, promosyon, duyuru vb. pazarlama faaliyetleri kapsamında kullanılması ile sınırlı olmak üzere, Site’nin sahibi olan firmanın kendisine ait iletişim, portföy durumu ve demografik bilgilerini iştirakleri ya da bağlı bulunduğu grup şirketleri ile paylaşmasına muvafakat ettiğini kabul ve beyan eder. Bu kişisel bilgiler firma bünyesinde müşteri profili belirlemek, müşteri profiline uygun promosyon ve kampanyalar sunmak ve istatistiksel çalışmalar yapmak amacıyla kullanılabilecektir.</p>
                                <p class="text">3.3. Gizli Bilgiler, ancak resmi makamlarca usulü dairesinde bu bilgilerin talep edilmesi halinde ve yürürlükteki emredici mevzuat hükümleri gereğince resmi makamlara açıklama yapılmasının zorunlu olduğu durumlarda resmi makamlara açıklanabilecektir.</p>
                                <p class="text">4. Garanti Vermeme: İŞBU SÖZLEŞME MADDESİ UYGULANABİLİR KANUNUN İZİN VERDİĞİ AZAMİ ÖLÇÜDE GEÇERLİ OLACAKTIR. FİRMA TARAFINDAN SUNULAN HİZMETLER "OLDUĞU GİBİ” VE "MÜMKÜN OLDUĞU” TEMELDE SUNULMAKTA VE PAZARLANABİLİRLİK, BELİRLİ BİR AMACA UYGUNLUK VEYA İHLAL ETMEME KONUSUNDA TÜM ZIMNİ GARANTİLER DE DÂHİL OLMAK ÜZERE HİZMETLER VEYA UYGULAMA İLE İLGİLİ OLARAK (BUNLARDA YER ALAN TÜM BİLGİLER DÂHİL) SARİH VEYA ZIMNİ, KANUNİ VEYA BAŞKA BİR NİTELİKTE HİÇBİR GARANTİDE BULUNMAMAKTADIR.</p>
                                <p class="text">5. Kayıt ve Güvenlik Kullanıcı, doğru, eksiksiz ve güncel kayıt bilgilerini vermek zorundadır. Aksi halde bu Sözleşme ihlal edilmiş sayılacak ve Kullanıcı bilgilendirilmeksizin hesap kapatılabilecektir. Kullanıcı, site ve üçüncü taraf sitelerdeki şifre ve hesap güvenliğinden kendisi sorumludur. Aksi halde oluşacak veri kayıplarından ve güvenlik ihlallerinden veya donanım ve cihazların zarar görmesinden Firma sorumlu tutulamaz.</p>
                                <p class="text">6. Mücbir Sebep</p>
                                <p class="text">Tarafların kontrolünde olmayan; tabii afetler, yangın, patlamalar, iç savaşlar, savaşlar, ayaklanmalar, halk hareketleri, seferberlik ilanı, grev, lokavt ve salgın hastalıklar, altyapı ve internet arızaları, elektrik kesintisi gibi sebeplerden (aşağıda birlikte "Mücbir Sebep” olarak anılacaktır.) dolayı sözleşmeden doğan yükümlülükler taraflarca ifa edilemez hale gelirse, taraflar bundan sorumlu değildir. Bu sürede Taraflar’ın işbu Sözleşme’den doğan hak ve yükümlülükleri askıya alınır.</p>
                                <p class="text">7. Sözleşmenin Bütünlüğü ve Uygulanabilirlik</p>
                                <p class="text">İşbu sözleşme şartlarından biri, kısmen veya tamamen geçersiz hale gelirse, sözleşmenin geri kalanı geçerliliğini korumaya devam eder.</p>
                                <p class="text">8. Sözleşmede Yapılacak Değişiklikler</p>
                                <p class="text">Firma, dilediği zaman sitede sunulan hizmetleri ve işbu sözleşme şartlarını kısmen veya tamamen değiştirebilir. Değişiklikler sitede yayınlandığı tarihten itibaren geçerli olacaktır. Değişiklikleri takip etmek Kullanıcı’nın sorumluluğundadır. Kullanıcı, sunulan hizmetlerden yararlanmaya devam etmekle bu değişiklikleri de kabul etmiş sayılır.</p>
                                <p class="text">9. Tebligat</p>
                                <p class="text">İşbu Sözleşme ile ilgili taraflara gönderilecek olan tüm bildirimler, Firma’nın bilinen e.posta adresi ve kullanıcının üyelik formunda belirttiği e.posta adresi vasıtasıyla yapılacaktır. Kullanıcı, üye olurken belirttiği adresin geçerli tebligat adresi olduğunu, değişmesi durumunda 5 gün içinde yazılı olarak diğer tarafa bildireceğini, aksi halde bu adrese yapılacak tebligatların geçerli sayılacağını kabul eder.</p>
                                <p class="text">10. Delil Sözleşmesi</p>
                                <p class="text">Taraflar arasında işbu sözleşme ile ilgili işlemler için çıkabilecek her türlü uyuşmazlıklarda Taraflar’ın defter, kayıt ve belgeleri ile ve bilgisayar kayıtları ve faks kayıtları 6100 sayılı Hukuk Muhakemeleri Kanunu uyarınca delil olarak kabul edilecek olup, kullanıcı bu kayıtlara itiraz etmeyeceğini kabul eder.</p>
                                <p class="text">11. Uyuşmazlıkların Çözümü</p>
                                <p class="text">İşbu Sözleşme’nin uygulanmasından veya yorumlanmasından doğacak her türlü uyuşmazlığın çözümünde İstanbul (Merkez) Adliyesi Mahkemeleri ve İcra Daireleri yetkilidir.</p>
                            </div>
                        </div>
                        <div id="popup-wrapper" class="term2 disable">
                            <div class="popup-container">
                                <div id="term2-exit" class="popup-exit">
                                    <div class="exit">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                                <h4 class="title">Gizlilik ve Güvenlik Politikası</h4>
                                <p class="text">Mağazamızda verilen tüm servisler ve ,………… adresinde kayıtlı  ……………….Şti. firmamıza aittir ve firmamız tarafından işletilir.</p>
                                <p class="text">Firmamız, çeşitli amaçlarla kişisel veriler toplayabilir. Aşağıda, toplanan kişisel verilerin nasıl ve ne şekilde toplandığı, bu verilerin nasıl ve ne şekilde korunduğu belirtilmiştir.</p>
                                <p class="text">Üyelik veya Mağazamız üzerindeki çeşitli form ve anketlerin doldurulması suretiyle üyelerin kendileriyle ilgili bir takım kişisel bilgileri (isim-soy isim, firma bilgileri, telefon, adres veya e-posta adresleri gibi) Mağazamız tarafından işin doğası gereği toplanmaktadır.</p>
                                <p class="text">Firmamız bazı dönemlerde müşterilerine ve üyelerine kampanya bilgileri, yeni ürünler hakkında bilgiler, promosyon teklifleri gönderebilir. Üyelerimiz bu gibi bilgileri alıp almama konusunda her türlü seçimi üye olurken yapabilir, sonrasında üye girişi yaptıktan sonra hesap bilgileri bölümünden bu seçimi değiştirilebilir ya da kendisine gelen bilgilendirme iletisindeki linkle bildirim yapabilir.</p>
                                <p class="text">Mağazamız üzerinden veya eposta ile gerçekleştirilen onay sürecinde, üyelerimiz tarafından mağazamıza elektronik ortamdan iletilen kişisel bilgiler, Üyelerimiz ile yaptığımız "Kullanıcı Sözleşmesi" ile belirlenen amaçlar ve kapsam dışında üçüncü kişilere açıklanmayacaktır.</p>
                                <p class="text">Sistemle ilgili sorunların tanımlanması ve verilen hizmet ile ilgili çıkabilecek sorunların veya uyuşmazlıkların hızla çözülmesi için, Firmamız, üyelerinin IP adresini kaydetmekte ve bunu kullanmaktadır. IP adresleri, kullanıcıları genel bir şekilde tanımlamak ve kapsamlı demografik bilgi toplamak amacıyla da kullanılabilir.</p>
                                <p class="text">Firmamız, Üyelik Sözleşmesi ile belirlenen amaçlar ve kapsam dışında da, talep edilen bilgileri kendisi veya işbirliği içinde olduğu kişiler tarafından doğrudan pazarlama yapmak amacıyla kullanabilir.  Kişisel bilgiler, gerektiğinde kullanıcıyla temas kurmak için de kullanılabilir. Firmamız tarafından talep edilen bilgiler veya kullanıcı tarafından sağlanan bilgiler veya Mağazamız üzerinden yapılan işlemlerle ilgili bilgiler; Firmamız ve işbirliği içinde olduğu kişiler tarafından, "Üyelik Sözleşmesi" ile belirlenen amaçlar ve kapsam dışında da, üyelerimizin kimliği ifşa edilmeden çeşitli istatistiksel değerlendirmeler, veri tabanı oluşturma ve pazar araştırmalarında kullanılabilir.</p>
                                <p class="text">Firmamız, gizli bilgileri kesinlikle özel ve gizli tutmayı, bunu bir sır saklama yükümü olarak addetmeyi ve gizliliğin sağlanması ve sürdürülmesi, gizli bilginin tamamının veya herhangi bir kısmının kamu alanına girmesini veya yetkisiz kullanımını veya üçüncü bir kişiye ifşasını önlemek için gerekli tüm tedbirleri almayı ve gerekli özeni göstermeyi taahhüt etmektedir.</p>
                                <p class="text">KREDİ KARTI GÜVENLİĞİ</p>
                                <p class="text">Firmamız, alışveriş sitelerimizden alışveriş yapan kredi kartı sahiplerinin güvenliğini ilk planda tutmaktadır. Kredi kartı bilgileriniz hiçbir şekilde sistemimizde saklanmamaktadır.</p>
                                <p class="text">İşlemler sürecine girdiğinizde güvenli bir sitede olduğunuzu anlamak için dikkat etmeniz gereken iki şey vardır. Bunlardan biri tarayıcınızın en alt satırında bulunan bir anahtar ya da kilit simgesidir. Bu güvenli bir internet sayfasında olduğunuzu gösterir ve her türlü bilgileriniz şifrelenerek korunur. Bu bilgiler, ancak satış işlemleri sürecine bağlı olarak ve verdiğiniz talimat istikametinde kullanılır. Alışveriş sırasında kullanılan kredi kartı ile ilgili bilgiler alışveriş sitelerimizden bağımsız olarak 128 bit SSL (Secure Sockets Layer) protokolü ile şifrelenip sorgulanmak üzere ilgili bankaya ulaştırılır. Kartın kullanılabilirliği onaylandığı takdirde alışverişe devam edilir. Kartla ilgili hiçbir bilgi tarafımızdan görüntülenemediğinden ve kaydedilmediğinden, üçüncü şahısların herhangi bir koşulda bu bilgileri ele geçirmesi engellenmiş olur.</p>
                                <p class="text">Online olarak kredi kartı ile verilen siparişlerin ödeme/fatura/teslimat adresi bilgilerinin güvenilirliği firmamiz tarafından Kredi Kartları Dolandırıcılığı'na karşı denetlenmektedir. Bu yüzden, alışveriş sitelerimizden ilk defa sipariş veren müşterilerin siparişlerinin tedarik ve teslimat aşamasına gelebilmesi için öncelikle finansal ve adres/telefon bilgilerinin doğruluğunun onaylanması gereklidir. Bu bilgilerin kontrolü için gerekirse kredi kartı sahibi müşteri ile veya ilgili banka ile irtibata geçilmektedir. Üye olurken verdiğiniz tüm bilgilere sadece siz ulaşabilir ve siz değiştirebilirsiniz. Üye giriş bilgilerinizi güvenli koruduğunuz takdirde başkalarının sizinle ilgili bilgilere ulaşması ve bunları değiştirmesi mümkün değildir. Bu amaçla, üyelik işlemleri sırasında 128 bit SSL güvenlik alanı içinde hareket edilir. Bu sistem kırılması mümkün olmayan bir uluslararası bir şifreleme standardıdır.</p>
                                <p class="text">Bilgi hattı veya müşteri hizmetleri servisi bulunan ve açık adres ve telefon bilgilerinin belirtildiği İnternet alışveriş siteleri günümüzde daha fazla tercih edilmektedir. Bu sayede aklınıza takılan bütün konular hakkında detaylı bilgi alabilir, online alışveriş hizmeti sağlayan firmanın güvenirliği konusunda daha sağlıklı bilgi edinebilirsiniz.</p>
                                <p class="text">Not: İnternet alışveriş sitelerinde firmanın açık adresinin ve telefonun yer almasına dikkat edilmesini tavsiye ediyoruz. Alışveriş yapacaksanız alışverişinizi yapmadan ürünü aldığınız mağazanın bütün telefon / adres bilgilerini not edin. Eğer güvenmiyorsanız alışverişten önce telefon ederek teyit edin. Firmamıza ait tüm online alışveriş sitelerimizde firmamıza dair tüm bilgiler ve firma yeri belirtilmiştir.</p>
                                <p class="text">MAİL ORDER KREDİ KART BİLGİLERİ GÜVENLİĞİ</p>
                                <p class="text">Kredi kartı mail-order yöntemi ile bize göndereceğiniz kimlik ve kredi kart bilgileriniz firmamız tarafından gizlilik prensibine göre saklanacaktır. Bu bilgiler olası banka ile oluşubilecek kredi kartından para çekim itirazlarına karşı 60 gün süre ile bekletilip daha sonrasında imha edilmektedir. Sipariş ettiğiniz ürünlerin bedeli karşılığında bize göndereceğiniz tarafınızdan onaylı mail-order formu bedeli dışında herhangi bir bedelin kartınızdan çekilmesi halinde doğal olarak bankaya itiraz edebilir ve bu tutarın ödenmesini engelleyebileceğiniz için bir risk oluşturmamaktadır.</p>
                                <p class="text">ÜÇÜNCÜ TARAF WEB SİTELERİ VE UYGULAMALAR</p>
                                <p class="text">Mağazamız,  web sitesi dâhilinde başka sitelere link verebilir. Firmamız, bu linkler vasıtasıyla erişilen sitelerin gizlilik uygulamaları ve içeriklerine yönelik herhangi bir sorumluluk taşımamaktadır. Firmamıza ait sitede yayınlanan reklamlar, reklamcılık yapan iş ortaklarımız aracılığı ile kullanıcılarımıza dağıtılır. İş bu sözleşmedeki Gizlilik Politikası Prensipleri, sadece Mağazamızın kullanımına ilişkindir, üçüncü taraf web sitelerini kapsamaz.</p>
                                <p class="text">İSTİSNAİ HALLER</p>
                                <p class="text">Aşağıda belirtilen sınırlı hallerde Firmamız, işbu "Gizlilik Politikası" hükümleri dışında kullanıcılara ait bilgileri üçüncü kişilere açıklayabilir. Bu durumlar sınırlı sayıda olmak üzere;</p>
                                <p class="text">1.Kanun, Kanun Hükmünde Kararname, Yönetmelik v.b. yetkili hukuki otorite tarafından çıkarılan ve yürürlülükte olan hukuk kurallarının getirdiği zorunluluklara uymak;</p>
                                <p class="text">2.Mağazamızın kullanıcılarla akdettiği "Üyelik Sözleşmesi"'nin ve diğer sözleşmelerin gereklerini yerine getirmek ve bunları uygulamaya koymak amacıyla;</p>
                                <p class="text">3.Yetkili idari ve adli otorite tarafından usulüne göre yürütülen bir araştırma veya soruşturmanın yürütümü amacıyla kullanıcılarla ilgili bilgi talep edilmesi;</p>
                                <p class="text">4.Kullanıcıların hakları veya güvenliklerini korumak için bilgi vermenin gerekli olduğu hallerdir.</p>
                                <p class="text">E-POSTA GÜVENLİĞİ</p>
                                <p class="text">Mağazamızın Müşteri Hizmetleri’ne, herhangi bir siparişinizle ilgili olarak göndereceğiniz e-postalarda, asla kredi kartı numaranızı veya şifrelerinizi yazmayınız. E-postalarda yer alan bilgiler üçüncü şahıslar tarafından görülebilir. Firmamız e-postalarınızdan aktarılan bilgilerin güvenliğini hiçbir koşulda garanti edemez.</p>
                                <p class="text">TARAYICI ÇEREZLERİ</p>
                                <p class="text">Firmamız, mağazamızı ziyaret eden kullanıcılar ve kullanıcıların web sitesini kullanımı hakkındaki bilgileri teknik bir iletişim dosyası (Çerez-Cookie) kullanarak elde edebilir. Bahsi geçen teknik iletişim dosyaları, ana bellekte saklanmak üzere bir internet sitesinin kullanıcının tarayıcısına (browser) gönderdiği küçük metin dosyalarıdır. Teknik iletişim dosyası site hakkında durum ve tercihleri saklayarak İnternet'in kullanımını kolaylaştırır.</p>
                                <p class="text">Teknik iletişim dosyası,  siteyi kaç kişinin ziyaret ettiğini, bir kişinin siteyi hangi amaçla, kaç kez ziyaret ettiğini ve ne kadar sitede kaldıkları hakkında istatistiksel bilgileri elde etmeye ve kullanıcılar için özel tasarlanmış kullanıcı sayfalarından  dinamik olarak reklam ve içerik üretilmesine yardımcı olur. Teknik iletişim dosyası, ana bellekte veya e-postanızdan veri veya başkaca herhangi bir kişisel bilgi almak için tasarlanmamıştır. Tarayıcıların pek çoğu başta teknik iletişim dosyasını kabul eder biçimde tasarlanmıştır ancak kullanıcılar dilerse teknik iletişim dosyasının gelmemesi veya teknik iletişim dosyasının gönderildiğinde uyarı verilmesini sağlayacak biçimde ayarları değiştirebilirler.</p>
                                <p class="text">Firmamız, işbu "Gizlilik Politikası" hükümlerini dilediği zaman sitede yayınlamak veya kullanıcılara elektronik posta göndermek veya sitesinde yayınlamak suretiyle değiştirebilir. Gizlilik Politikası hükümleri değiştiği takdirde, yayınlandığı tarihte yürürlük kazanır.</p>
                                <p class="text">Gizlilik politikamız ile ilgili her türlü soru ve önerileriniz için ……………….. adresine email gönderebilirsiniz. Firmamız’a ait aşağıdaki iletişim bilgilerinden ulaşabilirsiniz.</p>
                                <p class="text">Firma Ünvanı: </p>
                                <p class="text">Adres: </p>
                                <p class="text">Eposta: </p>
                                <p class="text">Tel: </p>
                                <p class="text">Fax: </p>
                            </div>
                        </div>
                    </div>
                    <div class="captcha-popup">
                        <div class="captcha-container">
                            <h2 class="captcha-title">Ben Bir İnsanım Testi</h2>
                            <div class="captcha-form-container">
                                <span class="text">Lütfen aşağıdaki testi çözerek insan olduğunuzu doğrulayın</span>
                                <div id="captchaDiv"></div>
                            </div>
                            <div class="close-popup">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <button id="btn-register" class="btn-action-submit">Üye Ol</button>
                    </div>
                </form>
                <div class="action-link-container">
                    <span class="text-1">Zaten üye misin? </span>
                    <a href="<?php echo URL . URL_LOGIN; ?>" class="text-2">Giriş Yap</a>
                </div>
            </div>
        </section>
    </main>
    <?php require_once 'View/SharedHome/_home_footer.php'; ?>
    <script src="<?php echo URL; ?>assets/js/action_input.js"></script>
    <script src="<?php echo URL; ?>assets/js/eyebtn.js"></script>
    <?php if (!empty($web_data['cookie_cart'])) : ?>
        <script src="<?php echo URL; ?>assets/js/header_cart.js"></script>
    <?php endif; ?>
    <script>
        const term1 = document.querySelector('.term1');
        document.getElementById('btn-term1').addEventListener('click', (e) => {
            e.preventDefault();
            if (term1.classList.contains('disable')) {
                term1.classList.remove('disable')
            }
            if (!bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.add('noscroll');
            }
        });
        document.getElementById('term1-exit').addEventListener('click', (e) => {
            e.preventDefault();
            if (!term1.classList.contains('disable')) {
                term1.classList.add('disable');
            }
            if (bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.remove('noscroll');
            }
        });
        term1.addEventListener('mousedown', (e) => {
            e.preventDefault();
            if (e.target.classList == 'term1') {
                if (!term1.classList.contains('disable')) {
                    term1.classList.add('disable');
                }
                if (bodyElement.classList.contains('noscroll')) {
                    bodyElement.classList.remove('noscroll');
                }
            }
        });
        const term2 = document.querySelector('.term2');
        document.getElementById('btn-term2').addEventListener('click', (e) => {
            e.preventDefault();
            if (term2.classList.contains('disable')) {
                term2.classList.remove('disable')
            }
            if (!bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.add('noscroll');
            }
        });
        document.getElementById('term2-exit').addEventListener('click', (e) => {
            e.preventDefault();
            if (!term2.classList.contains('disable')) {
                term2.classList.add('disable');
            }
            if (bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.remove('noscroll');
            }
        });
        term2.addEventListener('mousedown', (e) => {
            e.preventDefault();
            if (e.target.classList == 'term2') {
                if (!term2.classList.contains('disable')) {
                    term2.classList.add('disable');
                }
                if (bodyElement.classList.contains('noscroll')) {
                    bodyElement.classList.remove('noscroll');
                }
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            var notificationClient = $('.notification-client');
            var notificationHidden = 0;
            var notificationRemoved = 0;
            function setClientNotification(notificationMessage) {
                clearTimeout(notificationHidden);
                clearTimeout(notificationRemoved);
                notificationClient.html(notificationMessage);
                if (notificationClient.hasClass('hidden')) {
                    notificationClient.removeClass('hidden');
                }
                if (notificationClient.hasClass('removed')) {
                    notificationClient.removeClass('removed');
                }
                notificationHidden = setTimeout(() => {
                    if (!notificationClient.hasClass('hidden')) {
                        notificationClient.addClass('hidden');
                    }
                    notificationRemoved = setTimeout(() => {
                        if (!notificationClient.hasClass('removed')) {
                            notificationClient.addClass('removed');
                        }
                    }, 1500);
                }, 10000);
            }
            $(window).scroll(function() {
                if ($(window).scrollTop() > 0) {
                    notificationClient.addClass('sticky');
                } else {
                    notificationClient.removeClass('sticky');
                }
            });
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
                        if (response.hasOwnProperty('not_found_search_item')) {
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
            const inputEmail = $('#input-email');
            const emailMessage = $('#email-message');
            const inputPassword = $('#input-password');
            const passwordMessage = $('#password-message');
            const inputRepassword = $('#input-repassword');
            const repasswordMessage = $('#repassword-message');
            const captchaPopup = $('.captcha-popup');
            $('#btn-register').click(function(e) {
                e.preventDefault();
                if (inputEmail.val() == '') {
                    inputEmail.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_EMAIL; ?></span></div>');
                } else if ($.trim(inputEmail.val()).indexOf(' ') >= 0) {
                    inputEmail.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL; ?></span></div>');
                } else if (inputPassword.val() == '') {
                    inputPassword.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?></span></div>');
                } else if ($.trim(inputPassword.val()).indexOf(' ') >= 0) {
                    inputPassword.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD; ?></span></div>');
                } else if ($.trim(inputPassword.val()).length <= <?php echo PASSWORD_MIN_LIMIT; ?>) {
                    inputPassword.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD; ?></span></div>');
                } else if (!/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/.test(inputPassword.val())) {
                    inputPassword.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_PATTERN_PASSWORD; ?></span></div>');
                } else if (inputRepassword.val() == '') {
                    inputRepassword.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_RE_PASSWORD; ?></span></div>');
                } else if (inputPassword.val() != inputRepassword.val()) {
                    inputRepassword.focus();
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_NOT_SAME_PASSWORDS; ?></span></div>');
                } else if (!$('#accept-terms').is(':checked')) {
                    setClientNotification('<div class="notification danger"><span class="text"><?php echo TR_NOTIFICATION_ERROR_EMPTY_REGISTER_TERMS; ?></span></div>');
                } else {
                    clearTimeout(notificationHidden);
                    clearTimeout(notificationRemoved);
                    if (!notificationClient.hasClass('hidden')) {
                        notificationClient.addClass('hidden');
                    }
                    if (!notificationClient.hasClass('removed')) {
                        notificationClient.addClass('removed');
                    }
                    if (!captchaPopup.hasClass('active')) {
                        captchaPopup.addClass('active');
                    }
                }
            });
            $('.close-popup').click(function(e) {
                e.preventDefault();
                if (captchaPopup.hasClass('active')) {
                    captchaPopup.removeClass('active');
                }
            });
            inputEmail.keyup(function(e) {
                if (inputEmail.val() == '') {
                    emailMessage.text('<?php echo TR_NOTIFICATION_ERROR_EMPTY_EMAIL; ?>');
                } else if ($.trim(inputEmail.val()).indexOf(' ') >= 0) {
                    emailMessage.text('<?php echo TR_NOTIFICATION_ERROR_NOT_VALID_EMAIL; ?>');
                } else {
                    emailMessage.text('');
                }
            });
            inputPassword.keyup(function(e) {
                if (inputPassword.val() == '') {
                    passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_EMPTY_PASSWORD; ?>');
                } else if ($.trim(inputPassword.val()).indexOf(' ') >= 0) {
                    passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_NO_WHITE_SPACE_PASSWORD; ?>');
                } else if ($.trim(inputPassword.val()).length <= <?php echo PASSWORD_MIN_LIMIT; ?>) {
                    passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_MIN_LENGTH_PASSWORD; ?>');
                } else if (!/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/.test(inputPassword.val())) {
                    passwordMessage.text('<?php echo TR_NOTIFICATION_ERROR_PATTERN_PASSWORD; ?>');
                } else if (inputRepassword.val() != '' && inputPassword.val() != inputRepassword.val()) {
                    passwordMessage.text('');
                    repasswordMessage.text('<?php echo TR_NOTIFICATION_ERROR_NOT_SAME_PASSWORDS; ?>');
                } else {
                    passwordMessage.text('');
                    repasswordMessage.text('');
                }
            });
            inputRepassword.keyup(function(e) {
                if (inputRepassword.val() == '') {
                    repasswordMessage.text('<?php echo TR_NOTIFICATION_ERROR_EMPTY_RE_PASSWORD; ?>');
                } else if (inputPassword.val() != inputRepassword.val()) {
                    repasswordMessage.text('<?php echo TR_NOTIFICATION_ERROR_NOT_SAME_PASSWORDS; ?>');
                } else {
                    repasswordMessage.text('');
                }
            });
        });
    </script>
    <script src="<?php echo CAPTCHA_SRC; ?>" async defer></script>
    <script type="text/javascript">
        var hCaptcha = function() {
            hcaptcha.render('captchaDiv', {
                sitekey: '<?php echo CAPTCHA_SITE_KEY; ?>',
                theme: 'dark',
                'callback': 'hCaptchaCallback'
            });
        }

        function hCaptchaCallback() {
            if (loaderWrapper.classList.contains('hidden')) {
                loaderWrapper.classList.remove('hidden');
            }
            if (!bodyElement.classList.contains('noscroll')) {
                bodyElement.classList.add('noscroll');
            }
            document.getElementById('form-register').submit();
        }
    </script>
</body>

</html>