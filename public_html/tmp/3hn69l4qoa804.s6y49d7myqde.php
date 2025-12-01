

    <!-- INICIO FILA mensajes alertas  -->
    <div class="container py-1">
        <div class="row">
            <div class="col-12">
	            <h2 class="inline"><?= ($page_head) ?></h2>
	            <span class="badge inline pull-right text-bg-info"><?= ($total_users) ?></span>
            </div>
              <?php if ($message): ?>
                <?php if (trim($alertType)=='success'): ?>
                    
		                <div class="auto-close alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong><?= ($message) ?></strong>
                        </div>
                    
                    <?php else: ?>
		                <div class="auto-close alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong><?= ($message) ?></strong>
                        </div>
                    
                <?php endif; ?>
              <?php endif; ?>
        </div>
    </div>
    <!-- FIN FILA mensajes alertas  --> 
    
<form method="get" action="<?= ($BASE.'/user/listuser') ?>">   
    <div class="row">
        <div class="col-md-5 mx-auto">
            <div class="input-group mysearch-box">
                <input type="text"  id="searchInput" class="search form-control form-control-sm bg-secondary bg-opacity-10" placeholder="<?= ($i18n_placeholdersearch) ?>" name="q" >
                <button class="btn btn-secondary" type="submit"><i class="bi bi-search" data-toggle="tooltip"></i></button>
            </div>
        </div>

    <!-- INICIO Tabla Citas  -->
    <div class="row">
        <div class="table-responsive">
	    <?php if ($users): ?>
	        
                <table id="mytable" class="table table-hover table-striped results"> 
                <thead> 
                <tr>
        	        <th scope="col"><?= ($i18n_username) ?></th>
        	        <th scope="col"><?= ($i18n_email) ?></th>
        	        <th scope="col"><?= ($i18n_actionicon) ?></th>
    		    </tr>
    		    <tr class="warning no-result">
      		        <td colspan="6"><i class="fa fa-warning"></i><?= ($i18n_noresult) ?></td>
    		    </tr>
                </thead> 
                <tbody> 
    		    <?php $count=0; foreach (($users?:[]) as $user): $count++; ?>
        	        <tr>
            		    <td><?= (trim($user['username'])) ?></td>
            		    <td><?= (trim($user['email'])) ?></td>
                 	    <td>
                            <a href="<?= ($BASE.'/user/find/'. $user['id'] . '/' . $project_id) ?>" title="<?= ($i18n_edit) ?>"><i style = "color:#FFC107" class="bi bi-pencil-fill" data-toggle="tooltip"></i></a>
                	    </td>
        	        </tr>
    		    <?php endforeach; ?>
                </tbody> 
                </table> 
	        
	        <?php else: ?>
  	            <div class="alert alert-warning" role="alert">
     	            <?= ($i18n_noresultsearch . $q)."
" ?>
  	            </div>
	        
	    <?php endif; ?>
	    

	    </div>
    </div>
    <!-- FIN Tabla Citas  -->
</div>   

</form>

<input type="hidden" id="modal" name="modal" value="<?= ($modal) ?>" />

<!-- INICIO Bootstrap Modal TodayTask -->
<div class="modal fade" id="UserModal" tabindex="-1" aria-labelledby="UserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <form id="userForm" method="POST" action="<?= ($BASE.'/user/invite') ?>">
            <div class="modal-header">
                <h5 class="modal-title" id="UserModalLabel">Los Datos del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="user_id" name="user_id" value="<?= ($token) ?>" />
                <input type="hidden" id="project_id" name="project_id" value="<?= ($project_id) ?>" />
                <input type="hidden" id="collab_id" name="collab_id" value="<?= (@$selected_user['id']) ?>" />
                <p><strong><?= ($i18n_username) ?>:</strong> <?= (trim($selected_user['username'])) ?></p>
                <p><strong><?= ($i18n_email) ?>:</strong> <?= (trim($selected_user['email'])) ?></p>
                <p><strong><?= ($i18n_permissions) ?>:</strong></p>                
                
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="create" name="create">
  <label class="form-check-label" for="create">
    Crear
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="update" name="update">
  <label class="form-check-label" for="update">
    Editar
  </label>
</div>                
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="delete" name="delete">
  <label class="form-check-label" for="delete">
    Borrar
  </label>
</div>
                
                
                

                <!-- Display other person details here -->

        	    <input type="hidden" name="session_csrf" value="<?= ($CSRF) ?>" />
        	    <input type="hidden" name="inviteuser" value="inviteuser" />
		        <p><?= ($i18n_wantinvite) ?></p>
            </div>
            <div class="modal-footer">
		        <input type="button" class="btn btn-default" data-bs-dismiss="modal" value="<?= ($i18n_cancel) ?>">
                <button type="submit" class="btn btn-primary"><?= ($i18n_invite) ?></button>
            </div>
          </form>
        </div>
    </div>
</div>
<!-- FIN Bootstrap Modal TodayTask -->