<div class="row justify-content-sm-center h-100">
    <div class="col-md-8 col-lg-8">
	<div class="card shadow-lg">
	    <div class="card-body p-5">

			<h1><?= ($i18n_create_user) ?></h1>
			<?php if (isset($message)): ?>
				
				<div class="alert alert-danger" role="alert">
			  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			  <span class="sr-only">Error:</span>
				<?= ($this->raw($message))."
" ?>
				</div>
				
			<?php endif; ?>

			<form action="" method="post" class="">

				  <!-- <div class="form-group"> -->
        <div class="mb-3">
          <div class="row">
    		    <div class="col-sm-9 col-md-9 col-lg-9 col-xl-9">				  
					<label class="form-label" for="username"><?= ($i18n_username) ?></label>
				    <input type="text" class="form-control bg-primary bg-opacity-10" name="username" id="username" required oninvalid="this.setCustomValidity('<?= ($i18n_inusername) ?>')" />
				</div>
				    
                <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <label class="form-label" for="language"><?= ($i18n_language) ?></label>
                    <select class="form-select bg-primary bg-opacity-10" id="language" value="" name="language" required>
          		        <option value="en">English</option>
                        <option value="en">English</option>
                        <option value="sp">Español</option>
                    </select>
                </div>
  		    </div>
	    </div>

        <div class="mb-3">
          <div class="row">
    		<div class="col">
      		  <label class="form-label" for="person_name"><?= ($i18n_person_name) ?></label>
      		  <input type="text" class="form-control bg-primary bg-opacity-10" name="name" id="name" required />
    		</div>
    		
    		<div class="col">
      		  <label class="form-label" for="person_lname"><?= ($i18n_person_lname) ?></label>
      		  <input type="text" class="form-control bg-primary bg-opacity-10" name="lname" id="lname" required />
    		</div>
  		  </div>
	    </div>

				  <div class="form-group">
					<label class="control-label" for="email"><?= ($i18n_email) ?></label>
					<div class="form-field"><input type="email" class="form-control bg-primary bg-opacity-10" data-validation="email" value="" name="email" required />
					</div>
				  </div>
				  
				  <div class="form-group">
					<label class="control-label" for="password"><?= ($i18n_password) ?></label>
					<!-- data-validation="length"  data-validation-length="min8"   -->
					<div class="form-field"><input type="password" class="form-control bg-primary bg-opacity-10" name="password" id="password" required oninvalid="this.setCustomValidity('<?= ($i18n_inpassword) ?>')" oninput="setCustomValidity('')"/>
					</div>
				  </div>
				  
				  <div class="form-group">
					<label class="control-label" for="confirm"><?= ($i18n_password_conf) ?></label>
					<div class="form-field"><input type="password" class="form-control bg-primary bg-opacity-10" name="confirm" id="confirm" required />
					</div>
				  </div>
				  
				  <div class="form-group">
					<label class="control-label checkbox-inline" for="conditions"> </label>
					<div class="form-field"><input value=1 type="checkbox" name="conditions" id="conditions" required /><?= ($this->raw($i18n_agree_conditions))."
" ?>
					<a href="<?= ($i18n_tandc) ?>"><?= ($i18n_terms) ?></a>
					</div>
				  </div>
				  
				<input type="hidden" name="session_csrf" value="<?= ($CSRF) ?>" />

				<div class="col-lg-xs text-center"><input class="btn btn-primary" value="<?= ($i18n_submit) ?>" name="create" id="create" type="submit"></div>

			</form>
	    </div>
	</div>
    </div>
</div>