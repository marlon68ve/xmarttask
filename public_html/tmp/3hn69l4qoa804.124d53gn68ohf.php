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




            
<div class="container mt-4">
    <h2 class="mb-4">Notifications</h2>
    <div id="notification-area">
 
        <!-- Notifications will be dynamically added here -->

    </div>
</div>            




<?php if ($taskCount > 0): ?>


<div class="container mt-4">
  <div class="row">

    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm rounded">
          <h5 class="card-header">Team Productivity/Tasks</h5>
        <div class="card-body p-3">
          <h5 class="card-title"></h5>
          <canvas id="productivityChart"></canvas>
        </div>
      </div>
    </div>


<div class="col-md-6 col-lg-4">
    <div class="card shadow-sm rounded">
        <h5 class="card-header">Team Productivity/Actions</h5>
        <div class="card-body p-3">
            <canvas id="userNotesChart"></canvas>
        </div>
    </div>
</div>



    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm rounded">
          <h5 class="card-header">Project Progress - Tasks</h5>
        <div class="card-body p-3">
          <h5 class="card-title"></h5>
          <canvas id="taskChart"></canvas>
        </div>
      </div>
    </div>

<div class="col-md-6 col-lg-4"> <!-- Increased width on small screens -->
  <div class="card shadow-sm rounded">
      <h5 class="card-header">Task Distribution</h5>
    <div class="card-body p-3">
      <div style="width: 100%; height: 200px;"> <!-- Make it flexible -->
        <canvas id="taskDistributionChart"></canvas>
      </div>
    </div>
  </div>
</div>

  </div>
</div>


<?php endif; ?>


<!--

<div class="container-fluid">
  <section>
    <div class="row">
      <div class="col-12 mt-3 mb-1">
        <h5 class="text-uppercase">Statistics With Subtitle</h5>
        <p>Statistics on minimal cards with Title &amp; Sub Title.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between p-md-1">
              <div class="d-flex flex-row">
                <div class="align-self-center">
                  <i class="fas fa-pencil-alt text-info fa-3x me-4"></i>
                </div>
                <div>
                  <h4>Total Posts</h4>
                  <p class="mb-0">Monthly blog posts</p>
                </div>
              </div>
              <div class="align-self-center">
                <h2 class="h1 mb-0">18,000</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between p-md-1">
              <div class="d-flex flex-row">
                <div class="align-self-center">
                  <i class="far fa-comment-alt text-warning fa-3x me-4"></i>
                </div>
                <div>
                  <h4>Total Comments</h4>
                  <p class="mb-0">Monthly blog posts</p>
                </div>
              </div>
              <div class="align-self-center">
                <h2 class="h1 mb-0">84,695</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between p-md-1">
              <div class="d-flex flex-row">
                <div class="align-self-center">
                  <h2 class="h1 mb-0 me-4">$76,456.00</h2>
                </div>
                <div>
                  <h4>Total Sales</h4>
                  <p class="mb-0">Monthly Sales Amount</p>
                </div>
              </div>
              <div class="align-self-center">
                <i class="far fa-heart text-danger fa-3x"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between p-md-1">
              <div class="d-flex flex-row">
                <div class="align-self-center">
                  <h2 class="h1 mb-0 me-4">$36,000.00</h2>
                </div>
                <div>
                  <h4>Total Cost</h4>
                  <p class="mb-0">Monthly Cost</p>
                </div>
              </div>
              <div class="align-self-center">
                <i class="fas fa-wallet text-success fa-3x"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

-->





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


		
		<?php else: ?>
        <div class="d-flex align-items-center justify-content-center vh-100">
            <div class="text-center">
                <h4 class="display-1 fw-bold"><?= ($i18n_not_loggedin) ?></h4>
                <p class="fs-3"> <span class="text-danger"></span><a href="login"><?= ($i18n_login_here) ?></p>
            </div>
        </div>		    
		
	    <?php endif; ?>
	</div>
    </div>