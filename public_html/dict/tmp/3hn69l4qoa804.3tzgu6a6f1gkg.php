<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
             <h4 class="modal-title"><?= ($i18n['ui']['i18n_deletereg']) ?></h4>
             <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?= ($i18n['ui']['i18n_wantdelete'])."
" ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?= ($i18n['ui']['i18n_cancel']) ?></button>
                <a class="btn btn-danger btn-ok"><?= ($i18n['ui']['i18n_delete']) ?></a>                
            </div>
        </div>
    </div>
</div>