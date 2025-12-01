    <div class="container mt-5">
        <h1 class="text-center mb-4">Compact Database Table</h1>
        <div class="card shadow">
            <div class="card-body">
                <form id="compactTableForm" method="post" action="<?= ($BASE.'/admin/compacttable') ?>">
                    <div class="mb-3">
                        <label for="tableName" class="form-label">Table Name</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="tableName" 
                            name="tableName" 
                            placeholder="Enter table name" 
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="primaryKey" class="form-label">Primary Key</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="primaryKey" 
                            name="primaryKey" 
                            placeholder="Enter primary key column name" 
                            required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Compact Table</button>
                    </div>
                </form>
            </div>
        </div>
    </div>