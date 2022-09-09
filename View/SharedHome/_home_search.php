<div id="header-search-container" class="header-search-container">
    <div class="container">
        <form id="form-search">
            <div class="search-row">
                <input id="input-search" class="input-search" name="search_item" type="text" placeholder="Ne Arıyorsunuz?">
            </div>
        </form>
        <ul class="nav-search hidden">
            <h4 class="search-title">Arama Sonuçları</h4>
            <div id="nav-search-wrapper"></div>
        </ul>
        <ul class="nav-search-popular">
            <h4 class="search-title">Popüler Aramalar</h4>
            <?php if (!empty($web_data['popular_search_items'])) : ?>
                <?php foreach ($web_data['popular_search_items'] as $popular_search_items) : ?>
                    <li class="search-item"><a class="search-link" href="<?php echo URL . URL_ITEM_DETAILS . '/' . $popular_search_items['item_url']; ?>"><?php echo $popular_search_items['item_name']; ?></a></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>