<div class="container py-5">
  <div class="row">
    <div class="col-md-6 mx-auto">

    <!-- INICIO FILA mensajes alertas  -->
    <div class="container py-1">
        <div class="row">
            <div class="col-12">
                <h1><?= ($i18n_create_project) ?></h1>

            </div>
        </div>
    </div>
    <!-- FIN FILA mensajes alertas  -->


	  <form action="<?= ($BASE.'/project/createproject') ?>" method="post" class="" id="creaproject">
      <div id="alerta"></div>
      
      
        <div class="mb-3">
          <div class="row">
    		<div class="col">
      		  <label class="form-label" for="project_name"><?= ($i18n_project_name) ?></label>
      		  <input type="text" class="form-control bg-primary bg-opacity-10" name="project_name" id="project_name" required />
    		</div>
  		  </div>
	    </div>

        <div class="mb-3">
          <div class="row">
            <div class="col">
      		  <label class="form-label" for="project_desc"><?= ($i18n_desc) ?></label>
      		  <input type="text" class="form-control bg-primary bg-opacity-10" name="project_desc" id="project_desc" required />
              </div>
            </div>
          </div>
	    
        <div class="mb-3">
          <div class="row row-cols-2 input-group-sm">        

<div class="col">
                        <label class="form-label" for="project_duedate"><?= ($i18n_date) ?></label>
                <div class="input-group calendar mb-3">
                    <input type="text" class="form-control datepicker6  bg-primary bg-opacity-10"  data-date-language="es" name="duedate" id="duedate" value="" autocomplete="off">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-th fa-fw"></i></span>
                </div>
</div>
<div class="col">
                        <label class="form-label" for="project_duedate"><?= ($i18n_time) ?></label>
                <div class="input-group input-group-sm mb-3">
                    <input type="time" class="form-control bg-primary bg-opacity-10" id="duetime" name="duetime" value="" />
                </div>
</div>

<div class="col">
		    <label class="form-label" for="project_status"><?= ($i18n_type) ?></label>
        <select class="form-select bg-primary bg-opacity-10" id="project_type" name="project_type" required>
            <!-- Pre-select the current task type -->
            <option value="" selected>
               
            </option>            
            <!-- Populate the dropdown with all task types -->
            <?php foreach (($project_types?:[]) as $type_id=>$type_name): ?>
                <option value="<?= ($type_id) ?>"><?= ($type_name) ?></option>
            <?php endforeach; ?>
        </select>

</div> 
          </div>
        </div>

        <div class="mb-3">
          <div class="row">
            <div class="col">
        	<input type="hidden" id="session_csrf" name="session_csrf" value="<?= ($CSRF) ?>" />
        	<input type="hidden" id="createproject" name="createproject" value="createproject" />
            <input type="hidden" id="user_id" name="user_id" value="<?= ($user_id) ?>" /> 
        	<div class="col-lg-xs text-center"><input class="btn btn-primary" value="<?= ($i18n_register) ?>" name="btncreateproject" id="btncreateproject" type="submit"></div>
            </div>
          </div>
        </div>				  
	   </form>
	</div>
  </div>
</div>