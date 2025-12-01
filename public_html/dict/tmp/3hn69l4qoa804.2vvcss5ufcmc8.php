<!DOCTYPE html>
<html lang="en">


    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Clinoz Error</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>


    <body>
<div class="container py-5">
	<div class="row">
		<div class="col-12">

<?php switch ($ERROR['code']): ?><?php case '401': ?>
        <div class="d-flex align-items-center justify-content-center vh-100">
            <div class="text-center">
                <h1 class="display-1 fw-bold"><?= ($i18n_401number) ?></h1>
                <p class="fs-3"> <span class="text-danger">Opps!</span><?= ($i18n_401) ?></p>
                <!-- <p class="lead">The page you’re looking for doesn’t exist.</p> -->
                <a href="<?= ($SCHEME.'://'.$HOST.$BASE.'/') ?>" class="btn btn-primary"><?= ($i18n_home) ?></a>
            </div>
        </div>
	<?php if (TRUE) break; ?><?php case '403': ?>
		<p><?= ($i18n_403) ?></p>
	<?php if (TRUE) break; ?><?php case '404': ?>
        <div class="d-flex align-items-center justify-content-center vh-100">
            <div class="text-center">
                <h1 class="display-1 fw-bold"><?= ($i18n_404number) ?></h1>
                <p class="fs-3"> <span class="text-danger">Opps!</span><?= ($i18n_404) ?></p>
                <!-- <p class="lead">The page you’re looking for doesn’t exist.</p> -->
            </div>
        </div>
	<?php if (TRUE) break; ?><?php case '405': ?>
		<p><?= ($i18n_405) ?></p>
	<?php if (TRUE) break; ?><?php case '500': ?>
        <div class="d-flex align-items-center justify-content-center vh-100">
            <div class="text-center">
                <h1 class="display-1 fw-bold"><?= ($i18n_500number) ?></h1>
                <p class="fs-3"> <span class="text-danger">Opps!</span><?= ($i18n_500) ?></p>
                <!-- <p class="lead">The page you’re looking for doesn’t exist.</p> -->
            </div>
        </div>
	<?php if (TRUE) break; ?><?php default: ?>
		<p><?= ($i18n_othererror) ?></p>
	<?php break; ?><?php endswitch; ?>




		</div>
	</div>
</div>

<?php echo $this->render('footer.htm',NULL,get_defined_vars(),0); ?>
    </body>


</html>