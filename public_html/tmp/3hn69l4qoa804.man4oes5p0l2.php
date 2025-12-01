    <!-- INICIO FILA mensajes alertas  -->
    <div class="container py-1">
        <div class="row">
            <div class="col-12">
	           <!--  <h2 class="inline"><?= ($page_head) ?></h2> -->
	           <h2 class="inline">Persons</h2>
	            <span class="badge inline pull-right text-bg-info"><?= ($total_persons) ?></span>
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
    
<form method="get" action="<?= ($BASE.'/person/listperson') ?>">   
    <div class="row">
        <div class="col-md-5 mx-auto">
            <div class="input-group mysearch-box">
                <input type="text"  id="searchInput" class="search form-control form-control-sm bg-secondary bg-opacity-10" placeholder="<?= ($i18n_placeholdersearch) ?>" name="q" >
                <button class="btn btn-secondary" type="submit"><i class="bi bi-search" data-toggle="tooltip"></i></button>
            </div>
        </div>
        <div class="row">
        <div class="col-md-10">
            
            
            <?php if ($hasPermission('create', $SESSION['user_rol'])): ?>
                            
	        <!-- <a href="person/createperson" class="btn btn-info me-md-2 pull-right" role="button" aria-disabled="true"><?= ($i18n_btnnew) ?></a> -->
	        <a href="person/createperson" class="btn me-md-2 pull-right" role="button" aria-disabled="true"><i style = "font-size: 2rem; color:#0d6efd" class="bi bi-plus-circle-fill" data-toggle="tooltip"></i></a>
                
                <?php else: ?>
                    <i style = "font-size: 2rem; color: gray;" class="bi bi-plus-circle-fill" data-toggle="tooltip"></i>
                
            <?php endif; ?>

	        
        </div>
    </div>
        <!-- INICIO Tabla Citas  -->
        <div class="row">
        <div class="table-responsive">
	    <?php if ($persons): ?>
	        
	           <font size="2">
                <table id="mytable" class="table table-hover table-striped results"> 
                <thead> 
                <tr>
            		<th class="d-none d-sm-table-cell"><?= ($i18n_person_phone) ?></th>
        	        <th scope="col"><?= ($i18n_person_name) ?></th>
        	        <th scope="col"><?= ($i18n_person_lname) ?></th>
        	        <th scope="col"><?= ($i18n_person_phone) ?></th>
        	        <th scope="col"><?= ($i18n_actionicon) ?></th>
    		    </tr>
    		    <tr class="warning no-result">
      		        <td colspan="6"><i class="fa fa-warning"></i><?= ($i18n_noresult) ?></td>
    		    </tr>
                </thead> 
  
                <tbody> 
    		    <?php $count=0; foreach (($persons?:[]) as $person): $count++; ?>
        	        <tr>
            		    <td class="d-none d-sm-table-cell"><?= (trim($person['person_phone'])) ?></td>
            		    <td><?= (trim($person['person_name'])) ?></td>
            		    <td><?= (trim($person['person_lname'])) ?></td>
            		    <td><?= (trim($person['person_phone'])) ?></td>
                 	    <td>
                	        


    <?php if (($SESSION['user_rol'] <> 1 && $hasPermission('update', $SESSION['user_rol'])) || $SESSION['user_rol'] == 1): ?>
    
        <a href="<?= ($BASE.'/person/update/'. $person['person_phone']) ?>" title="<?= ($i18n_edit) ?>" d="true"><i style = "color:#FFC107; font-size: 10px;" class="bi bi-pencil-fill" data-toggle="tooltip" disabled></i></a> 
    
    <?php else: ?>
        <span title="edit" style="color: gray;"><i class="bi bi-pencil-fill" style="color: gray;"></i></span>
    
<?php endif; ?> 











    <a href="<?= ($BASE.'/person/find/'. $person['person_phone']) ?>" title="<?= ($i18n_schedule) ?>"><i style = "color:#03A9F4; font-size: 10px;" class="bi bi-calendar3" data-toggle="tooltip"></i></a>
                    
 <?php if (($SESSION['user_rol'] <> 1 && $hasPermission('delete', $SESSION['user_rol'])) || $SESSION['user_rol'] == 1): ?>
    
        <a data-href="<?= ($BASE.'/person/delete/'. $person['person_phone']) ?>" data-bs-toggle="modal" data-bs-target="#confirm-delete" title="<?= ($i18n_delete) ?>"><i style = "color:red; font-size: 10px;" class="bi bi-trash-fill" data-toggle="tooltip"></i></a>
    
    <?php else: ?>
        <span title="delete" style="color: gray;"><i class="bi bi-trash-fill" style="color: gray;"></i></span>
    
<?php endif; ?>                     
                    
                    
		            
                            <a href="<?= ($BASE.'/task/persontask/'. $person['person_num']) ?>" class="edit" title="<?= ($i18n_tasks) ?>"><span  style = "color:#03A9F4; font-size: 8px;" class="badge text-bg-primary"><?= (trim($person['task_count'])) ?></span></a>
			    <!-- <span class="badge inline text-bg-primary pull-right"><?= (trim($person['task_count'])) ?></span> -->

                	    </td>
        	        </tr>
    		    <?php endforeach; ?>
                </tbody> 
                </table> 
                </font>
	        
	        <?php else: ?>
  	            <div class="alert alert-warning" role="alert">
     	            <?= ($i18n_noresultsearch . $q)."
" ?>
  	            </div>
	        
	    <?php endif; ?>
	    </div>
    </div>
        <!-- FIN Tabla Citas  -->
        <?php if ($count): ?>
        
	    <h2 class="inline"></h2>
	    <span class="badge inline text-bg-info pull-right"><?= ($count.'  '.$i18n_intable) ?></span>
	    
	<?php endif; ?>\>
	</div>
</form>

<!-- INICIO Bootstrap Modal Eliminar Registro Persona -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
             <h4 class="modal-title">Borrar Registro</h4>
             <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?= ($i18n_wantdelete)."
" ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?= ($i18n_cancel) ?></button>
                <a class="btn btn-danger btn-ok"><?= ($i18n_delete) ?></a>
            </div>
        </div>
    </div>
</div>

<!-- INICIO Bootstrap Modal TodayTask -->
<div class="modal fade" id="TodayTaskModal" tabindex="-1" aria-labelledby="TodayTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <form id="taskForm">
            <div class="modal-header">
                <h5 class="modal-title" id="TodayTaskModalLabel">Los Datos de la Persona</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="type_entity" name="type_entity" value="<?= ($typeentity) ?>" /> 
                <input type="hidden" id="entity_num" name="entity_num" value="<?= ($entity_num) ?>" />                
                <input type="hidden" id="TaskDate" name="TaskDate" value="<?= ($todaydate) ?>" />
                <input type="hidden" id="TaskRegTime" name="TaskRegTime" value="<?= ($todaytime) ?>" />
                <input type="hidden" id="source" name="source" value="<?= ($source) ?>" />                 
                <input type="hidden" id="person_phone" name="person_phone" value="<?= ($token) ?>" />
                
                <!-- <p><strong><?= ($i18n_person_num) ?>:</strong> <?= (trim($selected_person['person_num'])) ?></p>  -->
                <p><strong><?= ($i18n_person_name) ?>:</strong> <?= (trim($selected_person['person_name'])) ?></p>
                <p><strong><?= ($i18n_person_lname) ?>:</strong> <?= (trim($selected_person['person_lname'])) ?></p>
                <?php if ($age): ?>
		            
                	    <p><strong><?= ($i18n_person_age) ?>:</strong> <?= ($age) ?></p>
		            
		            <?php else: ?>
                	    <p><strong><?= ($i18n_person_age) ?>:</strong> <?= (trim($selected_person['person_age'])) ?></p>
		            
		        <?php endif; ?>
                <p><strong><?= ($i18n_person_addr) ?>:</strong> <?= (trim($selected_person['person_addr'])) ?></p>
                <p><strong><?= ($i18n_person_phone) ?>:</strong> <?= (trim($selected_person['person_phone'])) ?></p>
                <!-- Display other person details here -->
                

    <div class="input-group input-group-sm mb-3">
        <span class="input-group-text" id="inputGroup-sizing-sm">
            <strong><?= ($i18n_type) ?>:</strong>
        </span>
            <select class="form-select bg-primary bg-opacity-10" id="task_type" name="task_type" required>
                <option value="" selected disabled>Select a task type</option>
                <?php foreach (($types?:[]) as $type_id=>$type_name): ?>
                    <option value="<?= ($type_id) ?>"><?= ($type_name) ?></option>
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
        	    <input type="hidden" name="session_csrf" value="<?= ($CSRF) ?>" />
        	    <input type="hidden" name="createtask" value="createtask" />
		        <p><?= ($i18n_wantschedule) ?></p>
            </div>
            <div class="modal-footer">
		        <input type="button" class="btn btn-default" data-bs-dismiss="modal" value="<?= ($i18n_cancel) ?>">
                <button type="submit" class="btn btn-primary"><?= ($i18n_schedule) ?></button>
            </div>
          </form>
        </div>
    </div>
</div>
<!-- FIN Bootstrap Modal TodayTask -->

<input type="hidden" id="modal" name="modal" value="<?= ($modal) ?>" />