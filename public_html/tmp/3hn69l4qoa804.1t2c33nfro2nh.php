<div class="container py-5">
	<div class="row">
		<div class="col-md-6 mx-auto">

            <form action="<?= ($BASE.'/person/update') ?>" method="post" class="form-horizontal">
		<div class="mb-3">
  		  <div class="row">

        <div class="mb-3">
          <div class="row">
            <div class="col">
              <label class="form-label" for="person_phone"><?= ($i18n_person_phone) ?></label>
              <div class="form-field"><input type="tel" class="form-control bg-primary bg-opacity-10" name="person_phone" id="person_phone" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" value="<?= ($POST['person_phone']) ?>" disabled = "true" />
              </div>
            </div>
          </div>
        </div>

    		<div class="col">
      		  <label class="form-label" for="person_name"><?= ($i18n_person_name) ?></label>
      		  <input type="text" class="form-control bg-primary bg-opacity-10" name="person_name" id="person_name" value="<?= ($POST['person_name']) ?>" required />
    		</div>
    		<div class="col">
      		  <label class="form-label" for="person_lname"><?= ($i18n_person_lname) ?></label>
      		  <input type="text" class="form-control bg-primary bg-opacity-10" name="person_lname" id="person_lname" value="<?= ($POST['person_lname']) ?>" required />
    		</div>
  		  </div>
	    </div>

        <div class="mb-3">
          <div class="row row-cols-2 input-group-sm">
          <div class="col-sm-5 col-md-5 col-lg-5 col-xl-5">
            <label class="form-label" for="person_dob"><?= ($i18n_person_dob) ?></label>
            <input type="text" class="form-control datepicker4 bg-primary bg-opacity-10" data-date-language="es" name="person_dob" id="person_dob" value="<?= ($POST['person_dob']) ?>" autocomplete="off"/>
          </div>

            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2">
              <label class="form-label" for="person_gender"><?= ($i18n_person_gender) ?></label>
            <select class="form-select bg-primary bg-opacity-10" id="person_gender" name="person_gender" required>
              <option value="<?= ($POST['person_gender']) ?>"><?= ($POST['person_gender']) ?></option>
              <option value="F">F</option>
              <option value="M">M</option>
            </select>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2">
              <label class="form-label" for="person_age"><?= ($i18n_person_age) ?></label>
              <div class="form-field">
                   <!-- <input type="text" class="form-control bg-primary bg-opacity-10" name="person_age" id="person_age" value="<?= ($POST['person_age']) ?>" required /> -->
		    <input type="text" class="form-control bg-primary bg-opacity-10" name="person_age" id="person_age" value="<?= ($age) ?>" disabled="true">
              </div>
            </div>

		<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">
		    <label class="form-label" for="person_maritals"><?= ($i18n_person_maritals) ?></label>
        	  <select class="form-select bg-primary bg-opacity-10" id="person_maritals" name="person_maritals">
          		<option value="<?= ($POST['person_maritals']) ?>"><?= ($POST['person_maritals']) ?></option>
          		<option value="Soltera/o">Soltera/o</option>
          		<option value="Casada/o">Casada/o</option>
          		<option value="Divorciada/o">Divorciada/o</option>
          		<option value="Viuda/o">Viuda/o</option>
        	  </select>
		</div>

          </div>
        </div>
       


        <div class="mb-3">
          <div class="row">
            <div class="col">
              <label class="control-label" for="person_addr"><?= ($i18n_person_addr) ?></label>
              <div class="form-field"><input type="text" class="form-control bg-primary bg-opacity-10" name="person_addr" id="person_addr" value="<?= ($POST['person_addr']) ?>" required />
              </div>
            </div>
          </div>
        </div>		  


        <div class="mb-3">
          <div class="row">
            <div class="col">
	    <input type="hidden" name="session_csrf" value="<?= ($CSRF) ?>" />
            <input type="hidden" name="vperson_phone" value="<?= ($POST['person_phone']) ?>" />
            <input type="hidden" name="update" value="update" />
            <div class="col-lg-xs text-center"><button type="submit" class="btn btn-primary"><i class="icon-edit icon-white"></i> Actualizar</button></div>
            </div>
          </div>
        </div>	

	   </form>
	</div>
  </div>
</div>