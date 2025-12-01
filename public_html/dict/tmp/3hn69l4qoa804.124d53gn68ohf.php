<div class="row">
	<div class="col-12">

	    <?php if (isset($SESSION['logged_in']) && $SESSION['logged_in']): ?>
		

            <!-- CONFETTI Efect  -->
            <!-- Modal -->
            <?php if ($SESSION['username'] == 'admin'): ?>
            
                <!-- Modal to show only at session start -->
                <div id="homeModal" class="modal">
                    <div class="modal-content">
                        <h2>Congrats!</h2>
                        <p>You have successfully started your session.</p>
                        <button id="close-btn">Close</button>
                    </div>
                </div>
            
            <?php endif; ?>
            <!-- END CONFETTI Efect   -->
 
            <div class="bg-body-tertiary p-3 rounded">
       	        <!-- <h1><?= ($i18n_titleapp) ?></h1>  -->
		        <legend><?= ($i18n_welcome) ?>&nbsp;&nbsp;<?= ($SESSION['username']) ?>!</legend>
		        <p><?= ($i18n_welcome_msg) ?></p>

                <!-- **** OJO Implementacion de RBAC (Role Based Access Control) **** -->
                <!-- **** viene desde el metodo homepage() en PageController     **** -->                
                <!-- ********  Verificar permisologia para acceder a botones ******** -->
                <?php if ($hasPermission('create', $SESSION['user_type'])): ?>
                
		            <p> <a class="btn btn-lg btn-primary" href="/user/update">Go to your settings</a>
		            <a class="btn btn-lg btn-warning" href="/logout">Logout</a></p>
                
                <?php endif; ?>
                <!-- ********  *************************************** ******** -->
            </div>

            <!-- INICIO Toasts
  		    <div class="toast show">
    			<div class="toast-header">
      			    <strong class="me-auto">Toast Header</strong>
      			    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
    			</div>
    			<div class="toast-body">
    			    Hello, world! This is a toast message.
    			    <div class="mt-2 pt-2 border-top">
      			    	<a class="btn btn-sm btn-success" href="<?= ($SCHEME.'://'.$HOST.$BASE.'/') ?>user/profile">Profile</a>
      			    	<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">Close</button>
    			    </div>
    		    	</div>
  		     </div>

		    <div class="toast-container position-static">
  		    	<div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
    			    <div class="toast-header">
      			    	<img src="..." class="rounded me-2" alt="...">
      			    	<strong class="me-auto">Bootstrap</strong>
      			    	<small class="text-muted">just now</small>
      			    	<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    			    </div>
    	            <div class="toast-body">
      			    	See? Just like this.
    		        </div>
  		    	</div>
    	    </div>
	    	FIN Toasts  -->
	    	
	    	<!-- INICIO Charts
            <div class="col-12 col-xl-4">
                <div class="card">
                    <h5 class="card-header">Traffic last 6 months</h5>
                    <div class="card-body">
                        <div id="traffic-chart">
				        </div>
                    </div>
                </div>
            </div>  -->


<div class="container mt-4">
  <div class="row">

    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm rounded">
          <h5 class="card-header">Project Progress - Tasks</h5>
        <div class="card-body p-3">
          <h5 class="card-title"></h5>
          <canvas id="taskChart"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm rounded">
          <h5 class="card-header">Team Productivity</h5>
        <div class="card-body p-3">
          <h5 class="card-title"></h5>
          <canvas id="productivityChart"></canvas>
        </div>
      </div>
    </div>

  </div>
</div>

            <!-- FIN Charts
            <div class="col-12 col-xl-4">
                <div class="card">
                    <h5 class="card-header">Tasks' actions Performance This week</h5>
                    <div class="card-body">
                        

                        <div class="row d-flex justify-content-center">
                            <div class="col-4 d-flex justify-content-center">
                                <div class="chart-wrapper">
                                    <canvas id="budget-chart"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div> 
            FIN Charts  -->

		
		<?php else: ?>
		    <p>You are not logged in right now. </p>
		    <p><a href="login">You can login here</a>.</p>
		
	    <?php endif; ?>
	</div>
    </div>
    

<!-- El proximo Toast se mostrara por un breve tiempo cada vez que aparezca la pagina homepage.html --> 
<div class="toast-container position-fixed top-0 end-0 p-3">
  <div id="taskNotification" class="toast shadow-sm rounded bg-light" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-warning text-white rounded-top">
      <strong class="me-auto">Task Reminder</strong>
      <small>Just now</small>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Task "Submit Report" is due today. Please review it now.
    </div>
  </div>
</div>

<!-- ALERTS mostrados como Toasts por poco tiempo -->
<div class="toast-container position-fixed top-2 end-0 p-3">
  <!-- Critical Alert -->
  <div id="criticalAlert" class="toast shadow-sm rounded bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-danger text-white rounded-top">
      <strong class="me-auto">Critical Alert</strong>
      <small>Just now</small>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Task "Review Proposal" is overdue! Immediate action is required.
    </div>
  </div>

  <!-- Warning Alert -->
  <div id="warningAlert" class="toast shadow-sm rounded bg-warning text-dark" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-warning text-dark rounded-top">
      <strong class="me-auto">Warning</strong>
      <small>2 minutes ago</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Task "Finalize Presentation" is nearing its deadline.
    </div>
  </div>
</div>
<!-- *********************************************************************************** --> 