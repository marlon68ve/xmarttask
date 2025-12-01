<div class="row justify-content-sm-center h-100">
    <div class="col-md-8 col-lg-8">
	<div class="card shadow-lg">
	    <div class="card-body p-5">

		<h1 class="display-6 fw-bold"><?= ($i18n_registration) ?></h1>
		<?php if ($POST['registration_ok']): ?>
		    
			<p><?= ($this->raw($i18n_reg_conf_success)) ?></p>
		    
		    <?php else: ?>
			<p><?= ($i18n_registration_confirmation_failed) ?></p>
		    	
		<?php endif; ?>

	    </div>
	</div>
    </div>
</div>