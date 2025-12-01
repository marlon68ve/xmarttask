    <main class="main-section backgnd">
<section class="h-100">
    <div class="container h-100">
	<div class="row justify-content-sm-center h-100">
	    <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">

		<div class="card shadow-lg">
		    <div class="card-body p-5">
			<h1 class="fs-4 card-title fw-bold mb-4"><?= ($i18n_login) ?></h1>

			<?php if (isset($message)): ?>
			    
				<div class="alert alert-danger" role="alert">
				    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				    <span class="sr-only"><?= ($i18n_error) ?></span>
				    <?= ($this->raw($message))."
" ?>
				</div>
			    
			    <?php else: ?>
				<?php if (isset($SESSION['login_message'])): ?>
				    
					<div class="alert alert-success" role="alert">
					    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					    <span class="sr-only"><?= ($i18n_msg) ?></span>
					    <?= ($this->raw($SESSION['login_message']))."
" ?>
					</div>
				    
				<?php endif; ?>
			    
			<?php endif; ?>

			<form method="POST" autocomplete="off">

			    <div class="input-group mb-3">
  				<span class="input-group-text"><i class="fa fa-user fa-fw"></i></span>
  				<div class="form-floating">
    				    <input id="username" name="username" type="text" class="form-control" placeholder="<?= ($i18n_username) ?>"  required autofocus oninvalid="this.setCustomValidity('<?= ($i18n_inusername) ?>')" oninput="setCustomValidity('')"/>
    				    <label class="mb-2 text-muted" for="username"><?= ($i18n_username) ?></label>
  				</div>
			    </div>

			    <div class="input-group mb-3">
  				<span class="input-group-text"><i class="fa fa-lock fa-fw"></i></span>
  				<div class="form-floating">
				    <input id="password" type="password" class="form-control" placeholder="<?= ($i18n_password) ?>" name="password" required oninvalid="this.setCustomValidity('<?= ($i18n_inpassword) ?>')" oninput="setCustomValidity('')"/>
				    <label class="mb-2 text-muted" for="password"><?= ($i18n_password) ?></label>
			        </div>

			    </div>

			    <div class="d-grid gap-2 d-md-flex justify-content-sm-center">
				<button class="btn btn-primary" type="submit" name="submit"><?= ($i18n_loging) ?></button>
			    </div>

			    <input type="hidden" name="login" value="login" />
			    <input type="hidden" name="session_csrf" value="<?= ($CSRF) ?>" />
			</form>
		    </div>

		</div>
		<div class="text-center mt-3 text-muted">
		    Copyright <i class="fa fa-copyright" style="font-size:24px"></i> 2017-2021 <i class="fa-solid fa-dash"></i> XMART101 
		</div>

	    </div>
	</div>
    </div>
</section>
</main>
