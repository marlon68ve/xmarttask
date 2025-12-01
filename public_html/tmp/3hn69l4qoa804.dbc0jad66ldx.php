
    <!-- INICIO FILA mensajes alertas  -->
    <div class="container py-1">
        <div class="row">
            <div class="col-12">
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

    <div class="container bootstrap snippet">
    <div class="row">
  		<div class="col-sm-10"><h1><?= ($SESSION['username']) ?></h1></div>
    </div>

    <div class="row">
  		<div class="col-sm-3"><!--left col-->

            <form method="POST" action="<?= ($BASE.'/user/loadphoto') ?>" enctype="multipart/form-data" >         
                <div class="text-center">
                <?php if ($filename): ?>
                    
                        <div id="display-image">
                            <img src="<?= ($SESSION['filename']) ?>" class="rounded-circle img-fluid" style="max-width: 200px; max-height: 200px;" alt="avatar">
                        </div>
                    
                    <?php else: ?>
                        <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" class="rounded-circle" alt="avatar">
                    
                <?php endif; ?>
                <h6><?= ($i18n_load_new_picture) ?></h6>
                <div class="form-group">
                    <!-- <input class="form-control" type="file" id="uploadfile" name="uploadfile" value="" /> -->
                    <input type="file" id="uploadfile" name="uploadfile" class="filestyle" data-btnClass="btn-warning" data-input="false" data-text="<?= ($i18n_browse) ?>">
                </div><br>
                </div>
                <input type="hidden" name="session_csrf" value="<?= ($CSRF) ?>" />

                <div class="form-group">
                    <input type="hidden" name="update" value="update" />
                    <button class="btn btn-primary" type="submit" name="upload"><?= ($i18n_update) ?></button>
                </div>
            </form>
         
        </div><!--/col-3-->

    	<div class="col-sm-9">
              
            <!--<div class="tab-content">
                <div class="tab-pane active" id="home"> -->
                    <hr>
                    <form class="form" action="" method="post" id="registrationForm">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label for="last_name"><h4><?= ($i18n_user) ?>:</h4></label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="username" value="<?= ($SESSION['username']) ?>" title="<?= ($i18n_username) ?>" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label for="last_name"><h4><?= ($i18n_name) ?>:</h4></label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="name" value="<?= ($SESSION['nombre'] . '  ' . $SESSION['apellido']) ?>" title="<?= ($i18n_fullname) ?>" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label for="email"><h4><?= ($i18n_email) ?>:</h4></label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="email" value="<?= ($SESSION['emailusuario']) ?>" title="<?= ($i18n_email) ?>" disabled>
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <div class="col-sm-6">
                            <label for="language"><h4><?= ($i18n_preferred_language) ?>:</h4></label>
                            <select name="language" id="language" class="form-control">
                                <option value="en" <?= ($SESSION['language']=='en'?'selected':'') ?>>English</option>
                                <option value="sp" <?= ($SESSION['language']=='sp'?'selected':'') ?>>Español</option>
                            </select>
                            </div>
                        </div>
                        
                        

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label for="password"><h4><?= ($i18n_password) ?></h4></label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="password" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label for="password2"><h4><?= ($i18n_password_conf) ?></h4></label>
                                <input type="password" class="form-control" name="confirm" id="confirm" placeholder="confirm" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <br>
                              	<button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i><?= ($i18n_save) ?></button>
                               	<!-- <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button> -->
                            </div>
                        </div>

                        <input type="hidden" name="session_csrf" value="<?= ($CSRF) ?>" />
                        <input type="hidden" name="edit" value="edit" />

              	    </form>
                    <hr>
              
             <!--</div>/tab-pane-->

          <!--</div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->
</div>
