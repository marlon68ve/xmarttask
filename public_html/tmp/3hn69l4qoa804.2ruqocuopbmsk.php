    <!-- INICIO de NavBar top horizontal -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark  bg-dark p-3" aria-label="Offcanvas navbar large flex-md-nowrap shadow">
      <div class="container fluid d-flex col-12 col-md-3 col-lg-2 flex-wrap flex-md-nowrap">
        <a class="navbar-brand" href="#"><?= ($i18n_titleapp.' / '.  $SESSION['project_name'] . '   ')."
" ?>
           <?php if ($SESSION['user_rol'] > 1): ?>
                		        
    		    <i class="bi bi-people"></i>
	        
            <?php endif; ?>
        </a> 

      </div>
      <div class="col-12 col-md-5 col-lg-8 d-flex align-items-center justify-content-end mt-3 mt-md-0">

        
	    <?php if (isset($SESSION['logged_in']) && $SESSION['logged_in']): ?>
	        
        <!-- Notification icon with badge -->
        <div class="me-auto me-md-4">
          <a href="user/notifications" class="text-white position-relative">
            <i class="bi bi-bell"></i>
            <?php if ($SESSION['unreadNotif'] > 0): ?>
                		        
            <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger">
              <?= ($SESSION['unreadNotif']) ?> <!-- This number should be dynamically updated based on unread notifications -->
              <span class="visually-hidden">unread notifications</span>
            </span>
	        
            <?php endif; ?>
          </a>
        </div>	            
                <img src="<?= ($SESSION['filename']) ?>"  class="rounded-circle navbar-img" style="margin-right: 10px;width:44px">
                <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle " type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              <?= ($i18n_hello.', '.  $SESSION['username'])."
" ?>
                </button>
       	        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li class="dropdown-header">
                    <h6><?= ($SESSION['nombre'] . '  ' . $SESSION['apellido']) ?></h6>
                    </li>
                    <?php if ($SESSION['user_type']==100): ?>
		             
	                    <li>
		                    <a class="dropdown-item d-flex align-items-center" href="admin/users">
                            <i class="bi bi-people"></i>
                            <span>&nbsp;&nbsp;<?= ($i18n_listuser) ?></span>
                            </a>
		                </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="register">
                            <i class="bi bi-person-plus"></i>
                            <span>&nbsp;&nbsp;<?= ($i18n_createuser) ?></span>
                            </a>
                        </li>
	                    <li>
		                    <a class="dropdown-item d-flex align-items-center" href="admin/compactusers">
                            <i class="bi bi-people"></i>
                            <span>&nbsp;&nbsp;<?= ($i18n_compactusers) ?></span>
                            </a>
		                </li> 
	                    <li>
		                    <a class="dropdown-item d-flex align-items-center" href="admin/compacttableForm">
                            <i class="bi bi-people"></i>
                            <span>&nbsp;&nbsp;<?= ($i18n_compacttable) ?></span>
                            </a>
		                </li> 		                
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="admin/backup">
                            <i class="bi bi-database"></i>
                            <span>&nbsp;&nbsp;<?= ($i18n_backupdb) ?></span>
                            </a>
                        </li>
                    
		            <?php endif; ?>

              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="page/homepage">
                  <i class="bi bi-house-door"></i>
                  <span>&nbsp;&nbsp;<?= ($i18n_home) ?></span>
                </a>
              </li>

              <li><hr class="dropdown-divider"></li>

               <li><a class="dropdown-item d-flex align-items-center" href="task/listtasks">
                <i class="bi bi-list-check"></i>
                <span>&nbsp;&nbsp;<?= ($i18n_tasks) ?></span>
              </a></li> 

              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="task/listtask">
                  <i class="bi bi-calendar3"></i>
                  <span>&nbsp;&nbsp;<?= ($i18n_scheduler) ?></span>
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="person/listperson">
                  <i class="bi bi-person-bounding-box"></i>
                  <span>&nbsp;&nbsp;<?= ($i18n_persons) ?></span>
                </a>
              </li>
              
              <li>
                <a class="dropdown-item d-flex align-items-center" href="project/listproject">
                  <i class="bi bi-journals"></i>
                  <span>&nbsp;&nbsp;<?= ($i18n_projects) ?></span>
                </a>
              </li>              

              <li><a class="dropdown-item d-flex align-items-center" href="user/profile">
                <i class="bi bi-person"></i>
                <span>&nbsp;&nbsp;<?= ($i18n_profile) ?></span>
              </a></li>  

<!--              
              <li>
                <a class="dropdown-item d-flex align-items-center" href="task/summary">
                  <i class="bi bi-journals"></i>
                  <span>&nbsp;&nbsp;Task Summary</span>
                </a>
              </li> 
-->

              <li>
                <a class="dropdown-item d-flex align-items-center" href="user/notifications">
                  <i class="bi bi-journals"></i>
                  <span>&nbsp;&nbsp;Notifications</span>
                </a>
              </li>               

		            <?php if ($SESSION['user_type']==100): ?>
		            
              <li><a class="dropdown-item d-flex align-items-center" href="admin/projectbackup">
                <i class="bi bi-plus-circle"></i>
                <span>&nbsp;&nbsp;<?= ($i18n_project_backup) ?></span>
              </a></li>                

              <li><a class="dropdown-item d-flex align-items-center" href="admin/xanalyze">
                <i class="bi bi-plus-circle"></i>
                <span>&nbsp;&nbsp;XAI Data Analyze</span>
              </a></li> 

              <li><a class="dropdown-item d-flex align-items-center" href="admin/openai">
                <i class="bi bi-plus-circle"></i>
                <span>&nbsp;&nbsp;OpenAI</span>
              </a></li>               

              <li><a class="dropdown-item d-flex align-items-center" href="admin/analyze">
                <i class="bi bi-plus-circle"></i>
                <span>&nbsp;&nbsp;OpenAI Data Analyze</span>
              </a></li> 
                    
		            <?php endif; ?>

               <li><hr class="dropdown-divider"></li>


              <li>
                <a class="dropdown-item d-flex align-items-center" href="logout">
                  <i class="bi bi-arrow-bar-right"></i>
                  <span>&nbsp;&nbsp;<?= ($i18n_logout) ?></span>
                </a>
              </li>
            </ul>
            
      		<?php endif; ?>
        </div>
      </div>
    </nav>
    <!-- FIN de NavBar top horizontal -->

    <!-- INICIO del contenedor principal de la pagina completa -->
    <!-- <div class="container-fluid"> -->
    <div class="container-fluid">
        <div class="row">  <!-- INICIO PRIMERA fila de la pagina completa -->