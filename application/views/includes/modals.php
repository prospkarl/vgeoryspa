<?php if (isset($need_verification) && $need_verification && $this->router->fetch_class() != 'daily_inventory' && $this->router->fetch_class() != 'sales'): ?>
    <div id="verifyFirst" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true" style="display:block;background: rgba(0,0,0,.5);">
        <div class="modal-dialog modal-sm modal-dialog-centered animated bounceIn">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-12">
                            <div class="m-t-30 m-b-30">
                                <i class="fa fa-exclamation-triangle faa-tada animated text-danger" style="font-size:5em"></i>
                            </div>
                            <h3> Please verify inventory first.</h3>
                            <a class="btn btn-info btn-sm m-t-20" href="<?= base_url('daily_inventory'); ?>">Verify Inventory</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div id="underMaintenance" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered animated bounceIn">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-12">
                        <div class="m-t-30 m-b-30">
                            <i class="fa fa-wrench faa-wrench animated" style="font-size:5em"></i>
                        </div>
                        <h5> Coming Soon...</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="viewLogs" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true" style="background:rgba(0,0,0,.5)">
    <div class="modal-dialog modal-md modal-dialog-centered animated bounceIn">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Logs</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-12">
                        <table class="table no-border" style="max-height: 300px; display: block; overflow-y: auto;">
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
                <div class="row text-right m-t-20">
                    <div class="col-12">
                        <button data-dismiss="modal" class="btn btn-inverse form-control esc-input" style="color:#fff" type="submit">Return </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="changePassword" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered animated bounceIn">
        <div class="modal-content">
            <form action="<?= base_url('common/changepassword') ?>" method="post" novalidate>
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">Change Password</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p class="m-0">New Password</p>
                        <input type="password" class="form-control m-t-5" name="password" data-validation-required-message="This field is required" required>
                        <div class="help-block"></div>
                    </div>
                    <div class="form-group">
                        <p class="m-0">Confirm password</p>
                        <input type="password" class="form-control m-t-5" name="con_password" data-validation-required-message="This field is required" required>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-info waves-effect action-btn"> Submit</button>
                    <button type="button" class="btn btn-inverse" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($this->session->type == 2): ?>
    <div id="switchAccount" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered animated bounceIn">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-12">
                            <div class="m-t-30 m-b-30">
                                <i class="fa fa-user faa-tada animated" style="font-size:5em;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-12">
                            <div class="form-group">
                                <h4 class="font-weight-bold">Please select a user</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-12">
                            <div class="list-group text-left">
                                <?php foreach ($_SESSION['accounts'] as $username => $name): ?>
                                    <?php if ($username != $this->session->username): ?>
                                        <a
                                            href="javascript:;"
                                            data-toggle="modal"
                                            data-target="#loginPassword"
                                            data-dismiss="modal"
                                            data-username="<?= $username ?>"
                                            data-fullname="<?= $name ?>"
                                            class="click-user faa-parent animated-hover list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                        >
                                            <span>
                                                <i class="fa fa-user"></i>
                                                &nbsp;
                                                <?= $name ?>
                                            </span>
                                            <i class="fa fa-arrow-right faa-horizontal"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <a href="<?= base_url('logout') ?>" class="list-group-item list-group-item-action list-group-item-info text-center"><i class="fa fa-plus"></i> Add an account</a>
                            </div>
                        </div>
                    </div>
                    <div class="row text-right m-t-20">
                        <div class="col-12">
                            <button data-dismiss="modal" class="btn btn-inverse form-control esc-input" style="color:#fff" type="submit">Cancel </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="loginPassword" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered animated bounceIn">
            <form action="<?= base_url('common/switch_account') ?>" method="post">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row text-center">
                            <div class="col-12">
                                <h3 class="font-weight-bold full-name">Name</h3>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-12">
                                <div class="m-t-20 m-b-30">
                                    <i class="fa fa-lock faa-wrench animated" style="font-size:5em"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" name="username" required>
                            <input type="password" class="form-control m-t-5" name="password" data-validation-required-message="This field is required" placeholder="Enter password" required>
                            <div class="help-block"></div>
                        </div>
                        <div class="row m-t-20">
                            <button class="btn btn-info form-control esc-input" style="color:#fff" type="submit">Login </button>
                            <button data-dismiss="modal" data-toggle="modal" data-target="#switchAccount" class="btn btn-warning form-control esc-input m-t-10" style="color:#fff" type="button">Back </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
