<div class="col-12">
   <div class="card">
      <div class="card-header">
        <h3 class="card-title">Notifications</h3>
      </div>
      <div class="card-body">
        <!-- Bootstrap Tabs -->
        <ul class="nav nav-tabs" id="notificationTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab" aria-controls="unread" aria-selected="true">Unread</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="read-tab" data-bs-toggle="tab" data-bs-target="#read" type="button" role="tab" aria-controls="read" aria-selected="false">Read</button>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="notificationTabsContent">
          <!-- Unread Notifications -->
          <div class="tab-pane fade show active" id="unread" role="tabpanel" aria-labelledby="unread-tab">
            <div class="list-group list-group-flush overflow-auto" style="max-height: 35rem">
              <?php foreach (($notifications?:[]) as $notif): ?>
                <?php if ($notif['is_read'] === 0): ?>
                  
                    <div class="list-group-item bg-light">
                      <div class="row">
                        <div class="col-auto">
                          <span class="avatar">
                            <img src="<?= ($notif['userpicture']) ?>" class="rounded-circle navbar-img" style="margin-right: 10px; width: 44px;">
                          </span>
                          
          <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-notif" 
                  notif-id="<?= ($notif['id']) ?>">
            <i class="bi bi-trash"></i>
          </button>                           
                          
                          
                          
                        </div>
                        <div class="col text-truncate">
                          <div class="d-flex align-items-center">
                            <!-- Toggle Switch for Read/Unread -->
                            <div class="form-check">
                              <input class="form-check-input toggle-read" type="checkbox" id="notif-<?= ($notif['id']) ?>" data-id="<?= ($notif['id']) ?>">
                            </div>
                            <span class="ms-2 text-wrap"><?= ($notif['message']) ?></span>
                          </div>
                          <small class="text-muted d-block mt-1">Received: <?= ($notif['created_at']) ?></small>
                        </div>
                      </div>
                    </div>
                  
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Read Notifications -->
          <div class="tab-pane fade" id="read" role="tabpanel" aria-labelledby="read-tab">
              
           
            <div class="list-group list-group-flush overflow-auto" style="max-height: 35rem">
              <?php foreach (($notifications?:[]) as $notif): ?>
                <?php if ($notif['is_read'] === 1): ?>
                  
                    <div class="list-group-item notif-container" id="notific-<?= ($notif['id']) ?>">
                      <div class="row">
                        <div class="col-auto">
                          <span class="avatar">
                            <img src="<?= ($notif['userpicture']) ?>" class="rounded-circle navbar-img" style="margin-right: 10px; width: 44px;">
                          </span>

          <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-notif" 
                  notif-id="<?= ($notif['id']) ?>">
            <i class="bi bi-trash"></i>
          </button>                          
                          
                          
                        </div>
                        <div class="col text-truncate">
                          <div class="d-flex align-items-center">
                            <!-- Toggle Switch for Read/Unread -->
                            <div class="form-check">
                              <input class="form-check-input toggle-read" type="checkbox" id="notif-<?= ($notif['id']) ?>" data-id="<?= ($notif['id']) ?>" checked>
                            </div>
                            <span class="ms-2 text-wrap"><?= ($notif['message']) ?></span>
                          </div>
                          <small class="text-muted d-block mt-1">Received: <?= ($notif['created_at']) ?></small>
                        </div>
                      </div>
                    </div>
                  
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
   </div>
</div>