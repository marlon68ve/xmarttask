<main class="px-md-4">
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
    <form action="task/listtask" method="post" class="" id="tasks"> 
        <!-- INICIO FILA de calendario y campos de texto   -->
        <div class="container content">
          <div class="row">
            <div class="col-md-4">
              <div id="divstyle">


	            <div class="row">
	              <!-- <div class="col">
		            <a class="btn report btn-primary btn-sm" href="<?= ($BASE.'/task/listtask/'. $todaydate) ?>/btnresumen" name="btnsummary" role="button">Resumen</a>
	              </div> -->
	            </div>
					
                <div class="input-group calendar">
                  <span class="input-group-text d-sm-none" id="basic-addon1"><?= ($i18n_date) ?></span>                    
                  <input type="text" class="form-control datepicker1 d-sm-none"  data-date-language="es" id="datepicker1" value="<?= ($datepicker) ?>" autocomplete="off">
                  <span class="input-group-text d-sm-none" id="basic-addon1"><i class="fa fa-th fa-fw"></i></span>
                </div>

    	        <!-- INICIO de Datepicker Inline para pantalla grande-->                
                <div class="input-group mb-3">
	              <div class="form-control datepicker2 d-none d-sm-block" id="datepick2" data-date-language="es" data-date-today-btn="linked" data-date-today-highlight="true" data-date="<?= ($datepicker) ?>">
	              </div>
		          <input type="hidden" id="my_hidden_input">
                </div>
    		    <!-- FIN de Datepicker -->

    		      
	           </div>
            </div>

            <div class="col-sm-12 col-md-8">
	          <div id="sandbox">
                <!-- <label for="date">Fecha  -->
                <input class="span2 col-md-2 form-control form-control-sm" id="date" type ="hidden" name="date" value="<?= ($datepicker) ?>" placeholder="dd/mm/yyyy">
	          </div>
              <!-- INICIO Barra de Busqueda  -->
              <div class="mb-3">
                <div class="row">
                  <div class="col">
                    <div class="input-group mysearch-box">
                      <input type="text"  id="searchInput" class="search form-control form-control-sm bg-secondary bg-opacity-10" placeholder="<?= ($i18n_placeholdersearch) ?>" name="q" >
                      <button type="button" class="btn btn-secondary" name="btnFindPerson" id="btnFindPerson"><i class="bi bi-search" data-toggle="tooltip"></i></button>
                      <!-- <button class="btn btn-outline-secondary" name="btnFindPerson" id="btnFindPerson" type="submit"><?= ($i18n_btnsearch) ?></button> -->
                    </div> 
                  </div>
                </div>
              </div>
              <!-- FIN Barra de Busqueda  -->
			</div>
			
    	</div>
	</div>
    <!-- FIN FILA calendario y campos de texto   -->


<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">Personas&nbsp;&nbsp;
    <span class="badge inline pull-right text-bg-info"><?= ($pertasks_count) ?></span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">Proyecto&nbsp;&nbsp;
    <span class="badge inline pull-right text-bg-info"><?= ($protasks_count) ?></span>
    </a>
  </li>
</ul>

<div class="tab-content pt-5" id="tab-content">
    
  <div class="tab-pane active" id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0">

    <!-- INICIO Tabla Tareas Persona -->
    <?php if ($pertasks): ?>  
    <h2 class="inline"><?= ($i18n_persons) ?></h2>
    
    <div class="row">
        <!-- <font size="2" face="Courier New" > -->
        <font size="2">
       <div class="table-responsive">
            <table id="mytable" class="table table-hover table-sm table-striped results">
             <thead>
                <tr>
        	   <th width="10%" class="d-none d-sm-table-cell" scope="col"><?= ($i18n_task_time) ?></th>
        	   <th width="15%" scope="col"><?= ($i18n_person_name) ?></th>
        	   <th width="15%" scope="col"><?= ($i18n_person_lname) ?></th>
        	   <th width="15%" scope="col"><?= ($i18n_t_title) ?></th>
        	   <th width="15%" scope="col"><?= ($i18n_responsible) ?></th>        	   
        	   <th width="10%" scope="col"><?= ($i18n_t_status) ?></th>
        	   <th width="10%" scope="col"></th> 
    		</tr>
    		<tr class="warning no-result">
      		   <td colspan="6"><i class="fa fa-warning"></i> No tiene historia registrada o no tiene task para este dia</td>
    		</tr>
    	     </thead>

    	     <tbody>
    		     <?php foreach (($pertasks?:[]) as $task): ?>
		        <?php if (trim($task['task_status'])=='Completada'): ?>
    		        
        	            <tr class="table-success">
      		        
    		        <?php else: ?>
        	            <tr>
    		        
		        <?php endif; ?> 
            		<td class="d-none d-sm-table-cell"><?= (date('g:i A', strtotime($task['task_time']))) ?></td>
            		<td><?= (trim($task['person_name'])) ?></td>
            		<td><?= (trim($task['person_lname'])) ?></td>
            		<td><?= (trim($task['task_title'])) ?></td>            		
            		<td><?= (trim($task['username'])) ?></td>            		
            		<td><?= (trim($task['task_status'])) ?></td>
                 	<td>
			            <a href="<?= ($BASE.'/task/edit/'. $task['projectperson_num'] . '/' . $task['task_num'] . '/person') ?>" title="<?= ($i18n_tasks) ?>"><i style = "color:orange; font-size: 10px;" class="bi bi-file-text-fill" data-toggle="tooltip"></i></a>
			            
                        <a data-href="<?= ($BASE.'/task/delete/' . $todaydate . '/'  . $task['projectperson_num'] . '/'. $task['task_num'] . '/person') ?>" data-bs-toggle="modal" data-bs-target="#confirm-delete" title="<?= ($i18n_delete) ?>"><i  style = "color:red; font-size: 10px;" class="bi bi-trash-fill" data-toggle="tooltip"></i></a>
                        <!-- <a data-href="<?= ($BASE.'/task/delete/'. $task['projectperson_num'] . '/' . $todaydate . '/' . $task['task_num'] . '/person') ?>" data-bs-toggle="modal" data-bs-target="#confirm-delete" title="<?= ($i18n_delete) ?>"><i  style = "color:red; font-size: 10px;" class="bi bi-trash-fill" data-toggle="tooltip"></i></a>    -->                    
                        
                	</td>            		
        	      </tr>

    		<?php endforeach; ?>
   	         </tbody>
            </table>
	    </div>
	    </font>
     </div>
    <!-- FIN Tabla  -->
    
    <?php endif; ?>

  </div>
  
  <div class="tab-pane" id="simple-tabpanel-1" role="tabpanel" aria-labelledby="simple-tab-1">

    <!-- INICIO Tabla Tareas Proyecto -->
    <?php if ($protasks): ?>  
    <h2 class="inline"><?= ($i18n_project) ?></h2>
    
    <div class="row">
       <div class="table-responsive">
        <font size="2">
            <table id="mytable2" class="table table-hover table-sm table-striped results">
             <thead>
                <tr>
        	   <th width="10%" class="d-none d-sm-table-cell" scope="col"><?= ($i18n_task_time) ?></th>
        	   <th width="15%" scope="col"><?= ($i18n_t_title) ?></th>
        	   <th width="15%" scope="col"><?= ($i18n_responsible) ?></th>        	   
        	   <th width="10%" scope="col"><?= ($i18n_t_status) ?></th>
        	   <th width="10%" scope="col"></th>        	   
    		</tr>
    		<tr class="warning no-result">
      		   <td colspan="6"><i class="fa fa-warning"></i> No tiene historia registrada o no tiene task para este dia</td>
    		</tr>
    	     </thead>

    	     <tbody>
    		     <?php foreach (($protasks?:[]) as $protask): ?>
		        <?php if (trim($protask['task_status'])=='Completada'): ?>
    		        
        	            <tr class="table-success">
      		        
    		        <?php else: ?>
        	            <tr>
    		        
		        <?php endif; ?> 
            		<td ><?= (date('g:i A', strtotime($protask['task_time']))) ?></td>
            		<td><?= ($protask['task_title']) ?></td>
            		<td><?= ($protask['username']) ?></td>             		
            		<td><?= ($protask['task_status']) ?></td>
                 	<td>
			            <a href="<?= ($BASE.'/task/edit/'. $protask['project_id'] . '/' . $protask['task_num'] .'/project') ?>" title="<?= ($i18n_tasks) ?>"><i style = "color:orange; font-size: 10px;" class="bi bi-file-text-fill" data-toggle="tooltip"></i></a>
			            
                        <a data-href="<?= ($BASE.'/task/delete/' .$todaydate . '/'  . $protask['project_id'] . '/' . $protask['task_num'] . '/project') ?>" data-bs-toggle="modal" data-bs-target="#confirm-delete" title="<?= ($i18n_delete) ?>"><i  style = "color:red; font-size: 10px;" class="bi bi-trash-fill" data-toggle="tooltip"></i></a>
                	</td>             		
        	      </tr>

    		<?php endforeach; ?>
   	         </tbody>
            </table>
            </font>
	    </div>
     </div>
    <!-- FIN Tabla  -->
    
    <?php endif; ?>

  </div>
  
</div>


    
    <!-- <div class="b-example-divider"></div> -->
    <input type="hidden" id="mTodayDate" name="mTodayDate" value="<?= ($todaydate) ?>" />
    <input type="hidden" id="cfech" name="cfech" value="" />
	<input type="hidden" id="referring_view" value="list_task">
	<input type="hidden" name="session_csrf" value="<?= ($CSRF) ?>" />
    <input type="hidden" name="createperson" value="createperson" />
</form>
    
<!-- INICIO Bootstrap Modal Eliminar Registro de task -->
<?php echo $this->render('/modal/delete.htm',NULL,get_defined_vars(),0); ?>

<!-- INICIO Bootstrap Modal Eliminar Registro de task -->
<div class="modal fade" id="person-modal" tabindex="-1" role="dialog" aria-labelledby="personmodalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="fromListTaskForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="TodayTaskModalLabel"><?= ($i18n_personinfo) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"> <!-- body -->
               <div id="alerta"></div>

                <div class="input-group">
                    <div class="col-3">
                        <label for="person_name" class="col-form-label"><?= ($i18n_person_name) ?>:</label>
                    </div>
                    <div class="col-9">
                        <input type="text" readonly class="form-control-plaintext" id="person_name" value="">
                    </div>
                </div>
                <div class="input-group">
                    <div class="col-3">
                        <label for="person_lname" class="col-form-label"><?= ($i18n_person_lname) ?>:</label>
                    </div>
                    <div class="col-9">
                        <input type="text" readonly class="form-control-plaintext" id="person_lname" value="">
                    </div>
                </div>
                <div class="input-group">
                    <div class="col-3">
                        <label for="person_age" class="col-form-label"><?= ($i18n_person_age) ?>:</label>
                    </div>
                    <div class="col-9">
                        <input type="text" readonly class="form-control-plaintext" id="person_age" value="">
                    </div>
                </div>
                <div class="input-group">
                    <div class="col-3">
                        <label for="person_addr" class="col-form-label"><?= ($i18n_person_addr) ?>:</label>
                    </div>
                    <div class="col-9">
                        <input type="text" readonly class="form-control-plaintext" id="person_addr" value="">
                    </div>
                </div>
                                <div class="input-group">
                    <div class="col-3">
                        <label for="person_phone" class="col-form-label"><?= ($i18n_person_phone) ?>:</label>
                    </div>
                    <div class="col-9">
                        <input type="text" readonly class="form-control-plaintext" id="person_phone" value="">
                    </div>
                </div>
                

                <div class="input-group calendar mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm"><strong>Tipo:</strong></span>     		  
        	     <select class="form-select" id="task_type" name="task_type">
      		        <?php $count=0; foreach (($types?:[]) as $type): $count++; ?>
          		    <option value="<?= ($type) ?>"><?= ($type) ?></option>
      		        <?php endforeach; ?>         		
        	     </select>   
        	 </div>
        	 
                
                <div class="input-group calendar mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm"><strong>Fecha:</strong></span>
                    <input type="text" class="form-control datepicker6"  data-date-language="es" id="datepicker6" value="<?= ($datepicker) ?>" autocomplete="off">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-th fa-fw"></i></span>
                </div>
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm"><strong>Hora:</strong></span>
                    <input type="time" class="form-control" id="timepicker1" value="18:05" />
                </div>

		        <p><?= ($i18n_wantschedule) ?></p>               
               
            </div>                 <!-- fin de body -->
            <div class="modal-footer">
		        <input type="button" class="btn btn-default" data-bs-dismiss="modal" value="<?= ($i18n_cancel) ?>">
                <button type="submit" class="btn btn-primary"><?= ($i18n_schedule) ?></button>
            </div>
            <input type="hidden" id="TaskDate" name="TaskDate" value="<?= ($datepicker) ?>" />
            <input type="hidden" id="person_num" name="person_num" value="" />            
        	<input type="hidden" id="session_csrf1" name="session_csrf" value="<?= ($CSRF) ?>" />
        	<input type="hidden" name="createtask" value="createtask" />            
          </form>
        </div>
    </div>
</div>

<!-- Resumen de tasks Modal HTML -->
<div id="resumenCitasModal" role="dialog" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="resumen">
				<div class="modal-header">						
					<h4 class="modal-title">Resumen de tasks</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">	
					<p><strong><?= ($fechapicker) ?></strong></p>				
					<input type="hidden" id="nFechHoy" name="nFechHoy" value="<?= ($fechoy) ?>" />
					<div class="container-fluid">
						<div class="row">
						<div class="col-md-12">

						<div class="mb-3">
							<div class="row">
								<?php foreach (($arraycount?:[]) as $count): ?>
								<div class="col-sm-4 g-2">
									<label class="form-label" for="mSinAtender"><?= ($count['CondicionCita']) ?></label>
									<input type="text" class="form-control form-control-sm bg-primary bg-opacity-10" id="mSinAtender" name="mSinAtender" value="<?= (trim($count['count'])) ?>" disabled />
							</div>
								<?php endforeach; ?>
								  
							</div>
						  </div>

						</div>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" id="btncancelar" name="btncancelar" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<!-- <button type="button" id="btnimprimir" name="btnimprimir" class="btn btn-info" data-bs-dismiss="modal">Imprimir</button> -->
					<a class="btn report btn-primary" href="<?= ($BASE.'/tasksummary/'. $todaydate) ?>" name="btnprint" role="button">Imprimir</a>
					<!-- <input type="submit" class="btn btn-info" value="Imprimir"> -->
				</div>
			</form>
		</div>
	</div>
</div>

<!-- INICIO Bootstrap Modal CitaHoy -->
<div class="modal fade" id="ListTaskModal" tabindex="-1" aria-labelledby="ListTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <form id="ListTaskForm">
            <div class="modal-header">
                <h5 class="modal-title" id="ListTaskModalLabel"><?= ($i18n_personinfo) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="person_phone" name="person_phone" class="fromlisttask" value="<?= ($token) ?>" />
		<input type="hidden" id="PersonNum" name="PersonNum" class="fromlisttask" value="<?= (trim($selected_person['person_num'])) ?>" />
		<input type="hidden" id="person_num" name="person_num" value="<?= (trim($selected_person['person_num'])) ?>" />
                <input type="hidden" id="TaskDate" name="TaskDate" value="<?= ($todaydate) ?>" />
                <input type="hidden" id="TaskTime" name="TaskTime" value="<?= ($todaytime) ?>" />
                <!-- <p><strong><?= ($i18n_person_num) ?>:</strong> <?= (trim($selected_person['person_num'])) ?></p> -->
                <p><strong><?= ($i18n_person_name) ?>:</strong> <?= (trim($selected_person['person_name'])) ?></p>
                <p><strong><?= ($i18n_person_lname) ?>:</strong> <?= (trim($selected_person['person_lname'])) ?></p>
                <p><strong><?= ($i18n_person_dob) ?>:</strong> <?= (trim($selected_person['person_dob'])) ?></p>
                <p><strong><?= ($i18n_person_phone) ?>:</strong> <?= (trim($selected_person['person_phone'])) ?></p>
                <!-- Display other patient details here -->

        	<input type="hidden" name="session_csrf" value="<?= ($CSRF) ?>" />
        	<input type="hidden" name="createtask" value="createtask" />

		<p><?= ($i18n_wantschedule.'  '.$todaydate) ?></p>
<div class="input-group input-group-sm mb-3">
  <span class="input-group-text" id="inputGroup-sizing-sm"><strong>Hora:</strong></span>
<input type="time" class="form-control" id="timepicker1" value="18:05" />

</div>
            </div>
            <div class="modal-footer">
		<input type="button" class="btn btn-default" data-bs-dismiss="modal" value="<?= ($i18n_cancel) ?>">
                <button type="submit" class="btn btn-primary"><?= ($i18n_schedule) ?></button>

            </div>
          </form>
        </div>
    </div>
</div>
<!-- FIN Bootstrap Modal taskHoy -->

<!-- Nuevo Paciente Modal HTML -->
<div id="newPersonModal" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="registerperson" method="post" class="" id="newPersonModalForm">
				<div class="modal-header">						
					<h4 class="modal-title"><?= ($i18n_regperson) ?></h4>
					<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">	
					<input type="hidden" id="nPersonPhone" name="nPersonPhone" value="<?= ($token) ?>" />				
					<p><?= ($i18n_createperson) ?><?= ($token) ?></p>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-bs-dismiss="modal" value="<?= ($i18n_cancel) ?>">
					<input type="submit" class="btn btn-success" value="<?= ($i18n_accept) ?>">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal for user not found -->
<div class="modal fade" id="person-not-found-modal" tabindex="-1" role="dialog" aria-labelledby="person-not-found-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
             <h4 class="modal-title">No encontrado</h4>
             <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Esta persona no se encuentra en la base de datos.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Aceptar</a>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="modal" name="modal" value="<?= ($modal) ?>" />
<input type="hidden" id="nPersonPhone" name="nPersonPhone" value="<?= ($token) ?>" />
</main>