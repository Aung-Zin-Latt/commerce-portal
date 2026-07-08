<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5>Total Users</h5>
                <p class="display-6 mb-0"><?= (int) $summary['total_users']; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5>Active Users</h5>
                <p class="display-6 mb-0"><?= (int) $summary['active_users']; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5>Total Products</h5>
                <p class="display-6 mb-0"><?= (int) $summary['total_products']; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5>Active Products</h5>
                <p class="display-6 mb-0"><?= (int) $summary['active_products']; ?></p>
            </div>
        </div>
    </div>
</div>