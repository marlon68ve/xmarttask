<div class="container">
    <div class="row">
	    <div class="col-12">
		    <?php if ($person != ''): ?>  
		        
	    <h1><?= ($page_head .'  '.$person['person_name'].'  '.$person['person_lname']) ?></h1>
            
            <?php endif; ?>
        
            <form id="form1" action="task/edit" method="post" >              
                <input type="hidden" id="task_num" name="task_num" value="<?= ($task['task_num']) ?>" />
                <input type="hidden" id="type_entity" name="type_entity" value="<?= ($type_entity) ?>" />                 
                <input type="hidden" id="TaskDate" name="TaskDate" value="<?= (trim($task['task_date'])) ?>" />
                <input type="hidden" id="TaskRegTime" name="TaskRegTime" value="<?= (trim($task['task_time'])) ?>" />
		        <?php if ($person): ?>  
		              
		                <input type="hidden" id="entity_num" name="entity_num" value="<?= ($entity_num) ?>" />
                        <p><strong><?= ($i18n_person_addr) ?>:</strong> <?= (trim($person['person_addr'])) ?></p>
                        <p><strong><?= ($i18n_person_phone) ?>:</strong> <?= (trim($person['person_phone'])) ?></p>
                        <!-- Display other person details here -->
                        <hr>
                    
                <?php endif; ?>
                <div class="col">
                    <h2 class="inline"><?= ($i18n_task) ?></h2>
                </div>
	            <div class="row">
    	            <div class="col">
                        <div class="input-group input-group-sm mb-3">
      		                <span class="input-group-text" id="inputGroup-sizing-sm"><strong><?= ($i18n_title) ?>:</strong></span> 
                            <input type="text" class="form-control bg-primary bg-opacity-10" id="task_title" name="task_title" value="<?= ($task['task_title']) ?>" />
                        </div>
                    </div>
                </div>
	            <div class="row">
    	            <div class="col">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text" id="myinputGroup-sizing-sm"><strong>Responsable:</strong></span>     		  
        	                <select class="form-select bg-primary bg-opacity-10" id="username" name="username">
        	                    <option value="<?= (trim($task['username'])) ?>"><?= (trim($task['username'])) ?></option>
      		                    <?php $count=0; foreach (($users?:[]) as $user): $count++; ?>
          		                    <option value="<?= ($user['username']) ?>"><?= ($user['username']) ?></option>
      		                    <?php endforeach; ?>         		
        	                </select>   
        	            </div>
    	           </div>
	            </div>  
	            <div class="row">
    	            <div class="col">
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-sm"><strong>Tipo:</strong></span>     		  
        	                <select class="form-select bg-primary bg-opacity-10" id="task_type" name="task_type">
        	                    <option value="<?= (trim($task['task_type'])) ?>"><?= (trim($task['task_type'])) ?></option>
      		                    <?php $count=0; foreach (($types?:[]) as $type): $count++; ?>
          		                    <option value="<?= ($type) ?>"><?= ($type) ?></option>
      		                    <?php endforeach; ?>         		
        	                </select>   
        	            </div>
    	            </div>
	            </div>  
                <div class="row">
    		        <div class="col">
      		        <!-- <label class="form-label" for="task_status"><?= ($i18n_t_status) ?></label>
      		        <input type="text" class="form-control bg-primary bg-opacity-10" name="task_status" id="task_status"  value="<?= ($task['task_status']) ?>" required /> -->
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-sm"><strong>Estado:</strong></span>      		  
        	                <select class="form-select bg-primary bg-opacity-10" id="task_status" name="task_status">
        	                    <option value="<?= (trim($task['task_status'])) ?>"><?= (trim($task['task_status'])) ?></option>
      		                    <?php $count=0; foreach (($statuses?:[]) as $status): $count++; ?>
          		                    <option value="<?= ($status) ?>"><?= ($status) ?></option>
      		                    <?php endforeach; ?>           		
        	                </select> 
        	            </div>
    		        </div>
  		        </div>
                <div class="input-group calendar mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm"><strong>Fecha:</strong></span>
                    <input type="text" class="form-control datepicker6"  data-date-language="es" id="datepicker6" name="task_date"  value="<?= (trim($task['task_date'])) ?>" autocomplete="off">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-th fa-fw"></i></span>
                </div>
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm"><strong>Hora:</strong></span>
                    <input type="time" class="form-control" id="timepicker1" name="task_time" value="<?= (trim($task['task_time'])) ?>" />
                </div>
                <div class="row">
                    <div class="col">
                        <label class="control-label" for="task_desc"><?= ($i18n_content) ?></label>
                        <div class="form-field">
                            <!-- <input type="text" class="form-control bg-primary bg-opacity-10" name="task_desc" id="task_desc" value="<?= ($task['task_desc']) ?>" required> -->
                            <textarea class="form-control bg-primary bg-opacity-10" id="task_desc" name="task_desc" rows="2" cols="50"><?= ($task['task_desc']) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mt-3">
                    <div class="col-auto">
                        <button type="submit" name="action" value="report" class="btn btn-warning me-4">Report</button>
                        <button type="submit" name="action"  value="update" class="btn btn-primary"><i class="icon-edit icon-white"></i> Actualizar</button>
                    </div>
                </div>
                
                <input type="hidden" id="modal" name="modal" value="<?= ($modal) ?>" />
                
            </form>
            <hr>
    
<!-- **** INICIO de TABS Notes/Images 2 - 3 **** -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" id="simple-tab-2" data-bs-toggle="tab" href="#simple-tabpanel-2" role="tab" aria-controls="simple-tabpanel-2" aria-selected="true"><h4>Notes</h4>&nbsp;&nbsp;&nbsp;</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="simple-tab-3" data-bs-toggle="tab" href="#simple-tabpanel-3" role="tab" aria-controls="simple-tabpanel-3" aria-selected="false"><h4>Images</h4>&nbsp;&nbsp;&nbsp;</a>
  </li>
</ul>    

<div class="tab-content pt-5" id="tab-content">
    <!-- ** INICIO TAB Notes ** -->    
    <div class="tab-pane active" id="simple-tabpanel-2" role="tabpanel" aria-labelledby="simple-tab-2">  
    
    
    	        <a href="#" data-bs-toggle="modal" data-bs-target="#NoteTaskModal"  class="btn me-md-2 pull-right" aria-disabled="true"><i style = "font-size: 2rem; color:#0d6efd" class="bi bi-plus-circle-fill" data-toggle="tooltip"></i></a> 
    
        <!-- INICIO de TABS  0 - 1 -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">Activas&nbsp;&nbsp;&nbsp;
                <span class="badge inline pull-right text-bg-warning"><?= ($inprogres) ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">Completadas&nbsp;&nbsp;&nbsp;
                <span class="badge inline pull-right text-bg-success"><?= ($done) ?></span>
                </a>
            </li>
        </ul>
        <div class="tab-content pt-5" id="tab-content">
            <!-- INICIO TAB Notes Activas  -->    
            <div class="tab-pane active" id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0">
                <?php $count=0; foreach (($notes?:[]) as $note): $count++; ?>
                    <?php if (trim($note['note_status']) <> 1): ?>
                        
    	                    <div class="toast show p-1 text-dark bg-opacity-50 border-0">
    	                        <div class="toast-header">
                                    <strong class="me-auto"><?= (trim($note['note_title'])) ?></strong>
                                    <small><?= (trim($note['note_createdat'])) ?></small>
		                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
    	                        </div>
    	                        <div class="toast-body">
    		                        <?= (trim($note['note_content']))."
" ?>
    		                        <div class="mt-2 pt-2 border-top">
                                        <input class="form-check-input" type="checkbox" value="" id="note_status" disabled>
                                        <label class="form-check-label" for="flexCheckDefault">DONE</label>
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      	                                    <a href="<?= ($BASE.'/note/find/'. $note['note_num']. '/' .$entity_num . '/' . $task['task_num'] . '/' . $type_entity) ?>" class="btn btn-sm btn-success">Editar</a>
      	                                    <a data-href="<?= ($BASE.'/note/delete/'. $note['note_num'] . '/' . $entity_num . '/' . $task['task_num'] . '/' . $type_entity . '/edittask') ?>" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirm-delete"><?= ($i18n_delete) ?></a>
      	                                    <!-- <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">Cerrar</button> -->
      	                                </div>
      	                            </div>
    	                        </div>
    	                    </div>
                        
                    <?php endif; ?>    	 
                <?php endforeach; ?>
            </div>
            <!-- FIN TAB Activas  -->

            <!-- INICIO TAB Notes Completadas  -->  
            <div class="tab-pane" id="simple-tabpanel-1" role="tabpanel" aria-labelledby="simple-tab-1">
                <?php $count=0; foreach (($notes?:[]) as $note): $count++; ?>
                    <?php if (trim($note['note_status']) == 1): ?>
                                
            	            <div class="toast show p-1 text-dark bg-opacity-50 border-0">
    	                        <div class="toast-header">
                                    <strong class="me-auto"><?= (trim($note['note_title'])) ?></strong>
                                    <small><?= (trim($note['note_createdat'])) ?></small>
		                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
    	                        </div>
    	                        <div class="toast-body">
    		                        <?= (trim($note['note_content']))."
" ?>
    		                        <div class="mt-2 pt-2 border-top">
                                        <input class="form-check-input" type="checkbox" value="" id="note_status" checked disabled>  
                                        <label class="form-check-label" for="note_status">DONE</label>
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      	                                    <a href="<?= ($BASE.'/note/find/'. $note['note_num']. '/' .$entity_num . '/' . $task['task_num'] . '/' . $type_entity) ?>" class="btn btn-sm btn-success">Editar</a>
      	                                    <a data-href="<?= ($BASE.'/note/delete/'. $note['note_num'] . '/' . $entity_num . '/' . $task['task_num'] . '/' . $type_entity . '/edittask') ?>" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirm-delete"><?= ($i18n_delete) ?></a>
      	                                    <!-- <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">Cerrar</button> -->
      	                                </div>
      	                            </div>
    	                        </div>
    	                    </div>
                        
                    <?php endif; ?>
                <?php endforeach; ?> 
            </div>
            <!-- FIN TAB Completadas  --> 
        </div>
        <!-- FIN de TABS Inside Notes -->
    </div>
    <!-- ** FIN TAB Notes ** --> 

    <!-- INICIO TAB Images  -->  
    <div class="tab-pane" id="simple-tabpanel-3" role="tabpanel" aria-labelledby="simple-tab-3">
        <h1>Images</h1>
        <hr>
        <form id="form2" method="POST" action="<?= ($BASE.'/task/loadimage') ?>" enctype="multipart/form-data" > 
            <div>
                <h1>Upload an Image</h1>
                <div class="form-group">
                    <input type="file" id="uploadfile" name="uploadfile" class="filestyle" data-btnClass="btn-warning" data-input="false" data-text="Buscar foto...">
                </div><br>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit" name="upload" value="upload">Cargar</button>
                </div>
                <input type="hidden" id="task_num" name="task_num" value="<?= ($task['task_num']) ?>" />
                <input type="hidden" id="type_entity" name="type_entity" value="<?= ($type_entity) ?>" />    
                <input type="hidden" id="entity_num" name="entity_num" value="<?= ($entity_num) ?>" />
                <h1>Display uploaded Image:</h1>
	            <?php if (isset($SESSION['picture'])): ?>
		             
                        <img src="<?= ($SESSION['picture']) ?>" class="rounded-circle img-fluid" style="max-width: 200px; max-height: 200px;" alt="Uploaded Image">		            
                   
                    <?php else: ?>
                        <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" class="rounded-circle" alt="avatar">
                                
                <?php endif; ?>
            </div> 
            
            
        </form> 


    </div>

</div>
    <!-- FIN TAB Images  --> 

<!-- **** FIN de TABS Notes/Images 2 - 3 **** -->

	    </div>
    </div>
</div>

<?php echo $this->render('/modal/delete.htm',NULL,get_defined_vars(),0); ?>

<!-- INICIO Bootstrap Modal CreateNote -->
<div class="modal fade" id="NoteTaskModal" tabindex="-1" aria-labelledby="NoteTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="NoteForm" action="#" method="POST">
           <!-- <form id="NoteForm" action="task/edit/<?= ($task['person_num'] .'/'. $task['task_num']) ?>" method="POST">  -->             
            <div class="modal-header">
                <h5 class="modal-title" id="NoteTaskModalLabel">Los Datos de la Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
  		  <div class="row">
    		<div class="col">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="lnote_title"><strong><?= ($i18n_title) ?></strong></span>     		    
      		  <input type="text" class="form-control bg-primary bg-opacity-10" name="note_title" id="note_title" required />
    		</div>
          </div> 
          </div>
          <div class="row">
            <div class="col">
              <label class="control-label" for="note_content"><?= ($i18n_content) ?></label>
              <div class="form-field">
                  <textarea class="form-control bg-primary bg-opacity-10" id="note_content" name="note_content" rows="2" cols="50"></textarea>
              </div>
            </div>
          </div>
                <input type="hidden" name="task_num" value="<?= (trim($task['task_num'])) ?>" />
                <input type="hidden" name="entity_num" value="<?= (trim($entity_num)) ?>" />             
                <input type="hidden" name="type_entity" value="<?= (trim($type_entity)) ?>" />                 
        	    <input type="hidden" name="session_csrf" value="<?= ($CSRF) ?>" />
        	    <input type="hidden" name="createnote" value="createnote" />
            </div>
            <div class="modal-footer">
		        <input type="button" class="btn btn-default" data-bs-dismiss="modal" value="<?= ($i18n_cancel) ?>">
                <!-- <a class="btn btn-primary btn-ok"><?= ($i18n_save) ?></a>  -->
                <button type="submit" class="btn btn-primary"><?= ($i18n_save) ?></button>                
            </div>
          </form>
        </div>
    </div>
</div>
<!-- FIN Bootstrap Modal TodayTask -->

<!-- INICIO Bootstrap Modal EditNote -->
<div class="modal fade" id="EditNoteModal" tabindex="-1" aria-labelledby="EditNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="EditNoteForm" action="#" method="POST">
           <!-- <form id="NoteForm" action="task/edit/<?= ($task['person_num'] .'/'. $task['task_num']) ?>" method="POST">  -->             
            <div class="modal-header">
                <h5 class="modal-title" id="EditNoteModalLabel">Los Datos de la Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
  		  <div class="row">
    		<div class="col">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="lnote_title"><strong><?= ($i18n_title) ?></strong></span>     		    
      		  <input type="text" class="form-control bg-primary bg-opacity-10" name="note_title" id="note_title" value="<?= ($selected_note['note_title']) ?>">
    		</div>
          </div> 
          </div>
          <div class="row">
            <div class="col">
              <label class="control-label" for="note_content"><?= ($i18n_content) ?></label>
              <div class="form-field">
                  <textarea class="form-control bg-primary bg-opacity-10" id="note_content" name="note_content" rows="2" cols="50"><?= ($selected_note['note_content']) ?></textarea>
              </div>
              
            <?php if (trim($selected_note['note_status']) == 1): ?>
                                
  <input class="form-check-input" type="checkbox" value="" id="note_status" checked>  
  <label class="form-check-label" for="note_status">
    DONE
  </label>
                
                <?php else: ?>
<div class="form-check">
  <input class="form-check-input" type="checkbox"  id="note_status" name="note_status">
  <label class="form-check-label" for="note_status">
    DONE
  </label>
</div>                     
                
            <?php endif; ?>


            </div>
          </div>
                <input type="hidden" name="note_num" value="<?= ($selected_note['note_num']) ?>" />          
                <input type="hidden" name="task_num" value="<?= (trim($task['task_num'])) ?>" />
                <input type="hidden" name="entity_num" value="<?= (trim($entity_num)) ?>" />             
                <input type="hidden" name="type_entity" value="<?= (trim($type_entity)) ?>" />              
        	    <input type="hidden" name="session_csrf" value="<?= ($CSRF) ?>" />
        	    <input type="hidden" name="createnote" value="createnote" />
            </div>
            <div class="modal-footer">
		        <input type="button" class="btn btn-default" data-bs-dismiss="modal" value="<?= ($i18n_cancel) ?>">
                <!-- <a class="btn btn-primary btn-ok"><?= ($i18n_save) ?></a>  -->
                <button type="submit" class="btn btn-primary"><?= ($i18n_save) ?></button>                
            </div>
          </form>
        </div>
    </div>
</div>
<!-- FIN Bootstrap Modal -->