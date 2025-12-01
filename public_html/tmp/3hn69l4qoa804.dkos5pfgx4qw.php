<main class="px-md-4 py-4">
    <!-- INICIO FILA mensajes alertas  -->
    <div class="container py-1">
        <div class="row">
            <div class="col-12">
              <h2 class="inline"><?= ($page_head) ?></h2>
              <?php if ($message): ?>
                <?php if (trim($alertType)=='success'): ?>
                    
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong><?= ($message) ?></strong>
                            </div>
                    
                    <?php else: ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong><?= ($message) ?></strong>
                            </div>
                    
                <?php endif; ?>
              <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- FIN FILA mensajes alertas  --> 

    <div class="row">
        <div class="table-responsive">
			<!-- INICIO FILA mensajes alertas  -->
			<!-- <table class="table table-hover table-striped table-bordered" id="list"> -->
			<table id="list" width="600" cellpadding="5" class="table table-hover table-fixed table-bordered table-striped table-sm results">
				<thead>
					<tr>
						<th scope="col"><?= ($i18n['ui']['i18n_username']) ?></th>
						<th scope="col"><?= ($i18n['ui']['i18n_rol']) ?></th>
						<th scope="col"><?= ($i18n['ui']['i18n_datecreated']) ?></th>
						<th scope="col"><?= ($i18n['ui']['i18n_dateactivated']) ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach (($users?:[]) as $user): ?>
					<tr onclick="window.document.location='admin/users/<?= ($user['id']) ?>';" style="cursor: pointer;">
						<td><?= ($user['username']) ?></td>
						<td>
							<?php if ($user['user_type']===100): ?>
								<em><?= ($i18n['ui']['i18n_admintype']) ?></em>
								
								<?php else: ?>
									<?php if ($user['user_type']===10): ?>
										<em><?= ($i18n['ui']['i18n_superusertype']) ?></em>
										
										<?php else: ?><?= ($i18n['ui']['i18n_usertype'])."
" ?>
										
									<?php endif; ?>
								
							<?php endif; ?></td>
						<td><?= ($user['created_at']) ?></td>
						<td><?= ($user['updated_at']) ?></td>
					</tr>	
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</main>
