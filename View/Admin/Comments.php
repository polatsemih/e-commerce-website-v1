<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Ürün Yorumları - Yönetici | <?php echo BRAND; ?></title>
    <meta name="robots" content="none" />
    <?php require_once 'View/SharedAdmin/_admin_head.php'; ?>
</head>

<body class="noscroll">
    <?php require_once 'View/SharedAdmin/_admin_body.php'; ?>
    <main>
        <section class="comment-section container">
            <?php if (!empty($web_data['item_comments'])) : ?>
                <h1 class="title">Yorumlar (<?php echo count($web_data['item_comments']); ?>)</h1>
                <?php if (!empty($web_data['item_name'])) : ?>
                    <span class="title-theme"><?php echo $web_data['item_name']; ?></span>
                <?php endif; ?>
                <?php if (!empty($web_data['user_name'])) : ?>
                    <span class="title-theme"><?php echo $web_data['user_name']; ?></span>
                <?php endif; ?>
                <div class="row">
                    <div class="box box-1 th">Kullanıcı</div>
                    <div class="box box-1 th">Ürün</div>
                    <div class="box box-3 th">Yorum</div>
                    <div class="box box-1 th">Onay</div>
                    <div class="box box-2 th">Onay Tarihi</div>
                    <div class="box box-2 th">Oluşturulma Tarihi</div>
                    <div class="box box-2 th">Son Güncelleme Tarihi</div>
                    <div class="box box-1 th">Sil</div>
                    <div class="box box-2 th">Silinme Tarihi</div>
                    <div class="box box-1 th">Cevaplar</div>
                </div>
                <?php foreach ($web_data['item_comments'] as $item_comments) : ?>
                    <div class="row">
                        <div class="box box-1">
                            <a class="info-link" href="<?php echo URL . URL_ADMIN_USER_DETAILS . '/' . $item_comments['user_id']; ?>"><i class="fas fa-info"></i></a>
                        </div>
                        <div class="box box-1">
                            <a class="info-link" href="<?php echo URL . URL_ADMIN_ITEM_DETAILS . '/' . $item_comments['item_url']; ?>"><i class="fas fa-info"></i></a>
                        </div>
                        <div class="box box-3"><?php echo $item_comments['comment']; ?></div>
                        <div class="box box-1">
                            <label for="is_comment_approved_<?php echo $item_comments['id']; ?>">
                                <div class="checkbox-wrapper create-checkbox">
                                    <input type="hidden" name="comment_id" value="<?php echo $item_comments['id']; ?>">
                                    <input type="checkbox" class="checkbox" id="is_comment_approved_<?php echo $item_comments['id']; ?>" name="is_comment_approved" <?php echo !empty($item_comments['is_comment_approved']) ? ' checked' : ''; ?> disabled>
                                    <span class="checkmark"></span>
                                </div>
                            </label>
                        </div>
                        <?php if (!empty($item_comments['date_comment_approved'])) : ?>
                            <div class="box box-2"><?php echo date('d/m/Y H:i:s', strtotime($item_comments['date_comment_approved'])); ?></div>
                        <?php else : ?>
                            <div class="box box-2">-</div>
                        <?php endif; ?>
                        <div class="box box-2"><?php echo date('d/m/Y H:i:s', strtotime($item_comments['date_comment_created'])); ?></div>
                        <?php if (!empty($item_comments['date_comment_last_updated'])) : ?>
                            <div class="box box-2"><?php echo date('d/m/Y H:i:s', strtotime($item_comments['date_comment_last_updated'])); ?></div>
                        <?php else : ?>
                            <div class="box box-2">-</div>
                        <?php endif; ?>
                        <div class="box box-1">
                            <label for="is_comment_deleted_<?php echo $item_comments['id']; ?>">
                                <div class="checkbox-wrapper create-checkbox">
                                    <input type="checkbox" class="checkbox" id="is_comment_deleted_<?php echo $item_comments['id']; ?>" name="is_comment_deleted" <?php echo !empty($item_comments['is_comment_deleted']) ? ' checked' : ''; ?> disabled>
                                    <span class="checkmark"></span>
                                </div>
                            </label>
                        </div>
                        <?php if (!empty($item_comments['date_comment_deleted'])) : ?>
                            <div class="box box-2"><?php echo date('d/m/Y H:i:s', strtotime($item_comments['date_comment_deleted'])); ?></div>
                        <?php else : ?>
                            <div class="box box-2">-</div>
                        <?php endif; ?>
                        <div class="box box-1 show-comment-reply" data-id="<?php echo $item_comments['id']; ?>"><i class="fas fa-chevron-right"></i></div>
                    </div>
                    <div class="hover-wrapper" id="hover_wrapper_<?php echo $item_comments['id']; ?>">
                        <div class="hover-container">
                            <div class="close remove-comment-reply" data-id="<?php echo $item_comments['id']; ?>"><i class="fas fa-times"></i></div>
                            <?php if (!empty($item_comments['comment_reply'])) : ?>
                                <div class="row">
                                    <div class="box box-11 th">Kullanıcı</div>
                                    <div class="box box-33 th">Yorum Cevap</div>
                                    <div class="box box-11 th">Onay</div>
                                    <div class="box box-22 th">Onay Tarihi</div>
                                    <div class="box box-22 th">Oluşturulma Tarihi</div>
                                    <div class="box box-22 th">Son Güncelleme Tarihi</div>
                                    <div class="box box-11 th">Sil</div>
                                    <div class="box box-22 th">Silinme Tarihi</div>
                                </div>
                                <?php foreach ($item_comments['comment_reply'] as $comment_reply) : ?>
                                    <div class="row">
                                        <div class="box box-11">
                                            <a class="info-link" href="<?php echo URL . URL_ADMIN_USER_DETAILS . '/' . $comment_reply['user_id']; ?>" target="_blanck"><i class="fas fa-info"></i></a>
                                        </div>
                                        <div class="box box-33"><?php echo $comment_reply['comment_reply']; ?></div>
                                        <div class="box box-11">
                                            <label for="is_comment_reply_approved_<?php echo $comment_reply['id']; ?>">
                                                <div class="checkbox-wrapper create-checkbox">
                                                    <input type="hidden" name="comment_id" value="<?php echo $comment_reply['id']; ?>">
                                                    <input type="checkbox" class="checkbox" id="is_comment_reply_approved_<?php echo $comment_reply['id']; ?>" name="is_comment_reply_approved" <?php echo !empty($comment_reply['is_comment_reply_approved']) ? ' checked' : ''; ?> disabled>
                                                    <span class="checkmark"></span>
                                                </div>
                                            </label>
                                        </div>
                                        <?php if (!empty($comment_reply['date_comment_reply_approved'])) : ?>
                                            <div class="box box-22"><?php echo date('d/m/Y H:i:s', strtotime($comment_reply['date_comment_reply_approved'])); ?></div>
                                        <?php else : ?>
                                            <div class="box box-22">-</div>
                                        <?php endif; ?>
                                        <div class="box box-22"><?php echo date('d/m/Y H:i:s', strtotime($comment_reply['date_comment_reply_created'])); ?></div>
                                        <?php if (!empty($comment_reply['date_comment_reply_last_updated'])) : ?>
                                            <div class="box box-22"><?php echo date('d/m/Y H:i:s', strtotime($comment_reply['date_comment_reply_last_updated'])); ?></div>
                                        <?php else : ?>
                                            <div class="box box-22">-</div>
                                        <?php endif; ?>
                                        <div class="box box-11">
                                            <label for="is_comment_reply_deleted_<?php echo $comment_reply['id']; ?>">
                                                <div class="checkbox-wrapper create-checkbox">
                                                    <input type="checkbox" class="checkbox" id="is_comment_reply_deleted_<?php echo $comment_reply['id']; ?>" name="is_comment_reply_deleted" <?php echo !empty($comment_reply['is_comment_reply_deleted']) ? ' checked' : ''; ?> disabled>
                                                    <span class="checkmark"></span>
                                                </div>
                                            </label>
                                        </div>
                                        <?php if (!empty($comment_reply['date_comment_reply_deleted'])) : ?>
                                            <div class="box box-22"><?php echo date('d/m/Y H:i:s', strtotime($comment_reply['date_comment_reply_deleted'])); ?></div>
                                        <?php else : ?>
                                            <div class="box box-22">-</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <span class="no-comment-reply">Henüz yoruma yapılmış cevap yok.</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <span class="no-comment-found">Kayıtlı Yorum Yok.</span>
            <?php endif; ?>
        </section>
    </main>
    <?php require_once 'View/SharedAdmin/_admin_footer.php'; ?>
    <script>
        document.querySelectorAll('.show-comment-reply').forEach(element => {
            element.addEventListener('click', (e) => {
                var hoverWrapper = document.getElementById('hover_wrapper_' + element.dataset.id);
                if (!hoverWrapper.classList.contains('active')) {
                    hoverWrapper.classList.add('active');
                }
            });
        });
        document.querySelectorAll('.remove-comment-reply').forEach(element => {
            element.addEventListener('click', (e) => {
                var hoverWrapper = document.getElementById('hover_wrapper_' + element.dataset.id);
                if (hoverWrapper.classList.contains('active')) {
                    hoverWrapper.classList.remove('active');
                }
            });
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