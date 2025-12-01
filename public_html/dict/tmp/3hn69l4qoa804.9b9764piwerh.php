    <!-- INICIO FILA mensajes alertas  -->
    <div class="container py-1">
        <div class="row">
            <div class="col-12">
	            <h2 class="inline"><?= ($page_head) ?></h2>
	            <span class="badge inline pull-right text-bg-info"><?= ($total_tasks) ?></span>
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
    
<form method="post" action="<?= ($BASE.'/project/listproject') ?>">   
    <div class="row">
        <div class="col-md-5 mx-auto">
            <div class="input-group mysearch-box">
                <input type="text"  id="searchInput" class="search form-control form-control-sm bg-secondary bg-opacity-10" placeholder="<?= ($i18n_placeholdersearch) ?>" name="q" >
                <button class="btn btn-secondary" type="submit"><i class="bi bi-search" data-toggle="tooltip"></i></button>
            </div>
        </div>

    <div class="row">
        <div class="col-md-10">
	        <!-- <a href="person/createperson" class="btn btn-info me-md-2 pull-right" role="button" aria-disabled="true"><?= ($i18n_btnnew) ?></a> -->
	        <a data-href="#" data-bs-toggle="modal" data-bs-target="#CreateTaskModal" class="btn me-md-2 pull-right" role="button" aria-disabled="true"><i style = "font-size: 2rem; color:#0d6efd" class="bi bi-plus-circle-fill" data-toggle="tooltip"></i></a>
	        
        </div>
    </div>
    
    <!-- INICIO Tabla Citas  -->
    <div class="row">
        <div class="table-responsive">
	    <?php if ($projecttasks): ?>
	        
	            <font size="2">
                <table id="mytable" class="table table-hover table-striped results"> 
                <thead> 
                <tr>
        	        <th scope="col"><?= ($i18n_responsible) ?></th>
        	        <th scope="col"><?= ($i18n_date) ?></th>
        	        <th scope="col"><?= ($i18n_time) ?></th>
        	        <th scope="col"><?= ($i18n_title) ?></th>
        	        <th scope="col"><?= ($i18n_status) ?></th>
        	        <th scope="col"><?= ($i18n_actionicon) ?></th>        	        
    		    </tr>
    		    <tr class="warning no-result">
      		        <td colspan="6"><i class="fa fa-warning"></i><?= ($i18n_noresult) ?></td>
    		    </tr>
                </thead> 
                <tbody> 
    		    <?php $count=0; foreach (($projecttasks?:[]) as $ptask): $count++; ?>
        	        <tr>
            		    <td><?= (trim($ptask['username'])) ?></td>
            		    <td><?= (trim($ptask['task_date'])) ?></td>
            		    <td><?= (trim($ptask['entity_num'])) ?></td>
	               <?php if ($ptask['type_entity'] === 'person'): ?>
	                    		        
            		    <td><i class="bi bi-person-bounding-box"></i><?= ('     '.trim($ptask['task_title'])) ?></td>
    		        
    		        <?php else: ?>
            		    <td><i class="bi bi-journals"></i><?= ('     '.trim($ptask['task_title'])) ?></td>   		            
    		        
                    <?php endif; ?>             		    

            		    <td><?= (trim($ptask['task_status'])) ?></td>            		    
                 	    <td>
<!-- <?php if ($granted_edit): ?><?php endif; ?>  -->
    <!-- <?php if ($ptask['username'] == $SESSION['username'] || $SESSION['user_rol'] == 1 || $SESSION['user_rol'] == 6): ?><?php endif; ?>  -->
<?php if ($ptask['username'] == $SESSION['username'] || $SESSION['user_rol'] == 1): ?>
    
        <a href="<?= ($BASE.'/task/edit/'. $ptask['entity_num'] . '/' . $ptask['task_num'] . '/' . $ptask['type_entity']) ?>" title="<?= ($i18n_edit) ?>" d="true"><i style = "color:#FFC107" class="bi bi-pencil-fill" data-toggle="tooltip" disabled></i></a> 
    
    <?php else: ?>
        <span title="edit" style="color: gray;"><i class="bi bi-pencil-fill" style="color: gray;"></i></span>
    
<?php endif; ?>                 	        

                    <a href="<?= ($BASE.'/task/view/'. $ptask['entity_num'] . '/' . $ptask['task_num'] . '/' . $ptask['type_entity']) ?>" title="<?= ($i18n_schedule) ?>"><i style = "color:#03A9F4;" class="bi bi-calendar3" data-toggle="tooltip"></i></a>
                    
<!--  <?php if ($granted_delete): ?><?php endif; ?>  -->
    <?php if ($ptask['username'] == $SESSION['username']  || $SESSION['user_rol'] == 1): ?>    
    
        <a data-href="<?= ($BASE.'/task/tskdelete/'. $ptask['task_num'] . '/' . $ptask['entity_num'] . '/' . $ptask['type_entity'] . '/' . $myview) ?>" data-bs-toggle="modal" data-bs-target="#confirm-delete" title="<?= ($i18n_delete) ?>"><i style = "color:red;" class="bi bi-trash-fill" data-toggle="tooltip"></i></a>
    
    <?php else: ?>
        <span title="delete" style="color: gray;"><i class="bi bi-trash-fill" style="color: gray;"></i></span>
    
<?php endif; ?>                     

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
</div>   




</form>

<!-- INICIO Bootstrap Modal Eliminar Registro Proyecto -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
             <h4 class="modal-title">Borrar Registro</h4>
             <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

        </div>
    </div>
</div>


<!-- INICIO Bootstrap Modal TodayTask -->
<div class="modal fade" id="CreateTaskModal" tabindex="-1" aria-labelledby="TaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <form id="taskForm">
            <div class="modal-header">
                <h5 class="modal-title">Los Datos de la Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="type_entity" name="type_entity" value="<?= ($typeentity) ?>" /> 
                <input type="hidden" id="entity_num" name="entity_num" value="<?= ($entity_num) ?>" />                
                <input type="hidden" id="TaskDate" name="TaskDate" value="<?= ($todaydate) ?>" />
                <input type="hidden" id="TaskRegTime" name="TaskRegTime" value="<?= ($todaytime) ?>" />
                <input type="hidden" id="source" name="source" value="<?= ($source) ?>" />                


                <div class="input-group calendar mb-3">
      		  <span class="input-group-text" id="inputGroup-sizing-sm"><strong><?= ($i18n_title) ?>:</strong></span> 
      		  <input type="text" class="form-control" name="task_title" id="task_title" required />
    		</div>

	    <?php if ($users): ?>
	            		
                <div class="input-group calendar mb-3">

                    
    <?php if ($SESSION['user_rol'] == 1): ?>

    
                <span class="input-group-text" id="myinputGroup-sizing-sm"><strong>Responsable:</strong></span>                     
        	     <select class="form-select" id="username" name="username">
      		        <?php $count=0; foreach (($users?:[]) as $user): $count++; ?>
          		    <option value="<?= ($user['username']) ?>"><?= ($user['username']) ?></option>
      		        <?php endforeach; ?>         		
        	     </select>   
    
    <?php else: ?>
      		  <span class="input-group-text" id="inputGroup-sizing-sm"><strong>Responsable:</strong></span> 
      		  <input type="text" class="form-control" name="task_title" id="task_title"  value="<?= ($SESSION['username']) ?>" disabled>        
    
	<?php endif; ?>       	     
        	     
        	     
        	 </div> 
            
	    <?php endif; ?>
    		
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
                
                
                <div class="input-group calendar mb-3">
              <label class="control-label" for="task_desc"><strong><?= ($i18n_desc) ?></strong></label>
              <div class="form-field">
                  <textarea class="form-control" id="task_desc" name="task_desc" rows="2" cols="50"></textarea>
              </div>
    		</div>                
                
        	    <input type="hidden" id="session_csrf" name="session_csrf" value="<?= ($CSRF) ?>" />
        	    <input type="hidden" name="createtask" value="createtask" />
		        <p><?= ($i18n_wantcreatetask) ?></p>
            </div>
            <div class="modal-footer">
		        <input type="button" class="btn btn-default" data-bs-dismiss="modal" value="<?= ($i18n_cancel) ?>">
                <button type="submit" class="btn btn-primary"><?= ($i18n_create) ?></button>
            </div>
          </form>
        </div>
    </div>
</div>
<!-- FIN Bootstrap Modal TodayTask -->

<input type="hidden" id="modal" name="modal" value="<?= ($modal) ?>" />