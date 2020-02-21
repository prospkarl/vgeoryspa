<div class="row m-b-40 sales-dashboard remove-margin-sm">
    <div class="padding-sm col-md-6 col-sm-12 justify-content-sm-center dashboard-box">
        <div class="card">
            <div class="card-body">
                <div class="d-flex p-10 no-block">
                    <div class="align-slef-center">
                        <h1 class="welcome-message">Welcome, <strong class="text-green"><?= ucwords($userdata['fname'])  ?>!</strong></h1>
                        <input type="hidden" name="current_location_id" value="<?= $this->session->location ? $this->session->location: '' ?>">
                        <h5 class="sales-muted">Location: <strong id="current_location"><?= $this->session->location ? $current_location : 'My Location' ?></strong></h5>
                    </div>
                    <div class="align-self-center display-6 hide-sm ml-auto"><i class="text-info fas fa-user-circle"></i></div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:70%; height:3px;"> <span class="sr-only">50% Complete</span></div>
            </div>
        </div>
    </div>
    <div class="padding-sm col-md-3 col-sm-6 dashboard-box">
        <div class="card">
            <div class="card-body">
                <div class="d-flex p-10 no-block">
                    <div class="align-slef-center">
                        <h1 class="m-b-1 vh-font">
                            <span class="sales_target"><?= $sales_target ?></span>
                        </h1>
                        <h5 class="sales-muted text-muted m-b-0">Sales Target this <strong class="display_time_frame">Week</strong></h5>
                    </div>
                    <div class="align-self-center display-6 hide-sm ml-auto"><i class="text-violet fas fa-calendar-check"></i></div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-violet" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:70%; height:3px;"> <span class="sr-only">50% Complete</span></div>
            </div>
        </div>
    </div>
    <div class="padding-sm col-md-3 col-sm-6 dashboard-box">
        <div class="card">
            <div class="card-body">
                <div class="d-flex p-10 no-block">
                    <div class="align-slef-center">
                        <h1 class="m-b-1 vh-font">
                            <span class="sales_this_week">0</span>
                        </h1>
                        <h5 class="sales-muted text-muted m-b-0">Sales this <strong class="display_time_frame">Week</strong></h5>
                    </div>
                    <div class="align-self-center display-6 hide-sm ml-auto"><i class="text-success fas fa-calendar-alt"></i></div>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:70%; height:3px;"> <span class="sr-only">50% Complete</span></div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="row">
    <div class="col-lg-3"> </div>

    <div class="col-lg-3"> </div>
</div> -->

<div class="row justify-content-sm-center m-t-40 sales-buttons remove-margin-sm">
    <?php $ci->render_buttons() ?>
</div>
