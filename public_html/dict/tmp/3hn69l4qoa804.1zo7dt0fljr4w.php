    <!-- INICIO FILA mensajes alertas  -->
    <div class="container py-1">
        <div class="row">
            <div class="col-12">
	            <h2 class="inline"><?= ($page_head) ?></h2>
	            <span class="badge inline pull-right text-bg-info"><?= ($total_projects) ?></span>
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
	        <a href="project/createproject" class="btn me-md-2 pull-right" role="button" aria-disabled="true"><i style = "font-size: 2rem; color:#0d6efd" class="bi bi-plus-circle-fill" data-toggle="tooltip"></i></a>
	        <input type="hidden" id="user_id" name="user_id" value="<?= ($user_id) ?>" />

        </div>
    </div>
    
    <!-- INICIO Tabla Citas  -->
    <div class="row">
        <div class="table-responsive">
	    <?php if ($projects): ?>
	        
	            <font size="2">	            
                <table id="mytable" class="table table-hover table-striped results"> 
                <thead> 
                <tr>
        	        <th scope="col"></th>                    
        	        <th scope="col"><?= ($i18n_title) ?></th>
        	        <th scope="col"><?= ($i18n_desc) ?></th>
        	        <th scope="col"><?= ($i18n_status) ?></th>
        	        <th scope="col"><?= ($i18n_actionicon) ?></th>
    		    </tr>
    		    <tr class="warning no-result">
      		        <td colspan="6"><i class="fa fa-warning"></i><?= ($i18n_noresult) ?></td>
    		    </tr>
                </thead> 
                <tbody> 
    		    <?php $count=0; foreach (($projects?:[]) as $project): $count++; ?>
	               <?php if ($project['project_id'] === $SESSION['project_id']): ?>
	                    		        
    		        <tr class="table-primary">
    		        
    		        <?php else: ?>
        	            <tr>    		            
    		        
                    <?php endif; ?>
        	            
        	            
			            <td>
			                <!--
			                <?php if ($project['project_num'] != $SESSION['favorite_project']): ?>
	                            
				                    <input class="form-check-input" type="radio" name="rasioProject" id="<?= ($project['project_id']) ?>" value="<?= ($project['project_id']) ?>">
				                
	                            <?php else: ?>
				                    <input class="form-check-input" type="radio" name="radioProject" id="<?= ($project['project_id']) ?>" value="<?= ($project['project_id']) ?>" checked>
				                
				            <?php endif; ?> 
				            -->
	            
				            <input class="form-check-input" type="radio" name="radioProject" id="radioProject" value="<?= ($project['project_id']) ?>">				            
				            
                        </td>
	               <?php if ($project['user_rol'] > 1): ?>
	                    		        
            		    <td><i class="bi bi-people"></i><?= ('   '.trim($project['project_name']) .' / '. $project['admin_username']) ?></td>
    		        
    		        <?php else: ?>
            		    <td><?= (trim($project['project_name'])) ?></td>   		            
    		        
                    <?php endif; ?>                        
            		    <td><?= (trim($project['project_desc'])) ?></td>
            		    <td><?= (trim($project['project_status'])) ?></td>
                 	    <td>
			                <?php if ($project['user_rol'] == 1): ?>
	                                             	        
                                    <a href="<?= ($BASE.'/project/update/'. $project['project_id']) ?>" title="<?= ($i18n_edit) ?>"><i style = "color:#FFC107" class="bi bi-pencil-fill" data-toggle="tooltip"></i></a>
				                
	                            <?php else: ?>
                                    <span title="edit" style="color: gray;"><i class="bi bi-pencil-fill" style="color: gray;"></i></span>	                                                            
				                
				            <?php endif; ?>  
				            
 			                <!-- <?php if ($project['user_rol'] == 1): ?>
	                             
                            
                                    <a href="<?= ($BASE.'/project/find/'. $project['project_id']) ?>" data-bs-toggle="modal" data-bs-target="#CreateTaskModal" title="<?= ($i18n_schedule) ?>"><i style = "color:#03A9F4;" class="bi bi-calendar3" data-toggle="tooltip"></i></a> 
                                    
                                    <a href="<?= ($BASE.'/project/find/'. $project['project_id']) ?>" title="<?= ($i18n_schedule) ?>"><i style = "color:#03A9F4;" class="bi bi-calendar3" data-toggle="tooltip"></i></a>                                    
                                    
				                
	                            <?php else: ?>
                                    <span title="edit" style="color: gray;"><i class="bi bi-calendar3" style="color: gray;"></i></span>	                                                            
				                
				            <?php endif; ?>   -->

                     	    
			                <?php if ($project['user_rol'] == 1 && $project['project_num'] != 0 && $project['project_id'] != $SESSION['project_id']): ?>
	                                                  	    
                     	            <a data-href="<?= ($BASE.'/project/delete/'. $project['project_id'] . '/delete') ?>" data-bs-toggle="modal" data-bs-target="#confirm-delete" title="<?= ($i18n_delete) ?>"><i style = "color:red;" class="bi bi-trash-fill" data-toggle="tooltip"></i></a>
				                
	                            <?php else: ?>
                                    <span title="edit" style="color: gray;"><i class="bi bi-trash-fill" style="color: gray;"></i></span>	                                                            
				                
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
    <div class="col-lg-xs text-center"><button type="submit" class="btn btn-primary pull-right"><i class="icon-edit icon-white"></i> Activar</button></div>
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
            <div class="modal-body">
               <p><i class="bi bi-exclamation-triangle"></i>&nbsp;&nbsp;&nbsp; ADVERTENCIA! esta acción borrará todos los datos relacionados con este Poryecto, incluyecdo tareas y notas</p>
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


                <div class="input-group calendar mb-3">
      		  <span class="input-group-text" id="inputGroup-sizing-sm"><strong><?= ($i18n_title) ?>:</strong></span> 
      		  <input type="text" class="form-control" name="task_title" id="task_title" required />
    		</div>

	    <?php if ($users): ?>
	            		
                <div class="input-group calendar mb-3">
                    <span class="input-group-text" id="myinputGroup-sizing-sm"><strong>Responsable:</strong></span>     		  
        	     <select class="form-select" id="username" name="username">
      		        <?php $count=0; foreach (($users?:[]) as $user): $count++; ?>
          		    <option value="<?= ($user['username']) ?>"><?= ($user['username']) ?></option>
      		        <?php endforeach; ?>         		
        	     </select>   
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
                
        	<!-- <input type="hidden" id="session_csrf" name="session_csrf" value="<?= ($CSRF) ?>" />  -->
        	<!-- <input type="hidden" id="session_csrf" name="session_csrf" value="<?= ($SESSION['csrf']) ?>">  -->
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