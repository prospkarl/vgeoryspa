    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <div class="navbar-header">
                <a class="navbar-brand" href="<?= base_url(); ?>">
                    <span>
                        <!-- dark Logo text -->
                        <img src="<?= assets_url('images/vge_logo.png'); ?>" alt="homepage" class="dark-logo" />
                        <!-- Light Logo text -->
                        <img src="<?= assets_url('images/vge_logo.png'); ?>" class="light-logo" alt="homepage" />
                    </span> </a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse">
                <!-- ============================================================== -->
                <!-- toggle and nav items -->
                <!-- ============================================================== -->

                <ul class="navbar-nav mr-auto" style="visibility: <?= $this->session->type == 2 ? 'hidden' : 'visible' ?>">
                    <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    <li class="nav-item hidden-sm-down"><span></span></li>
                </ul>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <ul class="navbar-nav my-lg-0">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-Bell"></i>
                            <?php if (count($notifications)): ?>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                            <ul>
                                <li>
                                    <div class="drop-title">Notifications</div>
                                </li>
                                <li>
                                    <div class="message-center">
                                        <?php if (count($notifications)): ?>
                                            <?php foreach ($notifications as $notif): ?>
                                                <a href="<?= $notif['link'] ?>">
                                                    <div class="btn btn-<?= $notif['iconClass'] ?> btn-circle" style="padding:7px"><i class="<?= $notif['icon'] ?>"></i></div>
                                                    <div class="mail-contnet">
                                                        <h5><?= !empty($notif['messageheader']) ? $notif['messageheader'] : '' ?></h5>
                                                        <span class="mail-desc"><?= $notif['message'] ?></span>
                                                    </div>
                                                </a>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                                <a href="javascript:;">
                                                    <div class="mail-contnet">
                                                        <span class="mail-desc">No notifications</span>
                                                    </div>
                                                </a>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <li>
                                    <a class="nav-link text-center" href="javascript:;"> <strong>Close</strong></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php if ($this->session->type == 2): ?>
                                <span class="user-name" style="color:#fff"><?= $userdata['fname'] . " " . $userdata['lname']?></span>
                            <?php endif; ?>
                            <img src="<?= assets_url('images/users/1.jpg'); ?>" alt="user" class="profile-pic" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right animated flipInY">
                            <ul class="dropdown-user">
                                <li>
                                    <div class="dw-user-box">
                                        <div class="u-img"><img src="<?= assets_url('images/users/1.jpg'); ?>" alt="user"></div>
                                        <div class="u-text">
                                            <h4 class="user-name"><?= $userdata['fname'] . " " . $userdata['lname']?></h4>
                                            <p class="text-muted"><?= $userdata['position']?></p>
                                            <button class="btn btn-rounded btn-danger btn-sm" data-toggle="modal" data-target="#changePassword">Change Password</button>
                                        </div>
                                    </div>
                                </li>
                                <li role="separator" class="divider"></li>
                                <!-- <li><a href="#"><i class="ti-user"></i> My Profile</a></li>
                                <li><a href="#"><i class="ti-wallet"></i> My Balance</a></li>
                                <li><a href="#"><i class="ti-email"></i> Inbox</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#"><i class="ti-settings"></i> Account Setting</a></li>
                                <li role="separator" class="divider"></li> -->
                                <?php if ($this->session->type == 2): ?>
                                    <li><a href="javascript:;>" data-target="#switchAccount" data-toggle="modal"><i class="mdi mdi-account-switch"></i> &nbsp; Switch Account</a></li>
                                    <li role="separator" class="divider"></li>
                                <?php endif; ?>
                                <li><a href="<?= base_url("logout") ?>"><i class="fa fa-power-off"></i> &nbsp; Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <?php if ($this->session->type != 2): ?>
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>

                        <?php foreach ($navigation as $nav): ?>
                            <li>
                                <a class="<?= isset($nav['sub-nav']) ? 'has-arrow' : ''; ?> waves-effect waves-dark" href="<?= $nav['link'] ?>" aria-expanded="false">
                                    <i class="<?= $nav['icon'] ?>"></i>
                                    <span class="hide-menu"><?= $nav['title'] ?></span>
                                </a>

                                <?php if (isset($nav['sub-nav'])): ?>
                                    <ul aria-expanded="false" class="collapse sub-menus">
                                        <?php foreach ($nav['sub-nav'] as $sub): ?>
                                            <?php if (isset($sub['sub_link'])): ?>
                                                <li> <a class="has-arrow"  aria-expanded="false"><?= $sub['title'] ?></a>
                                                    <ul aria-expanded="false" class="collapse">
                                                    <?php foreach ($sub['sub_link'] as $sec_sub): ?>
                                                        <li><a href="<?= base_url($sec_sub['link']) ?>"><?= $sec_sub['title'] ?></a></li>
                                                    <?php endforeach; ?>
                                                    </ul>
                                                </li>
                                            <?php else: ?>
                                                <li><a href="<?= base_url($sub['link']) ?>"><?= $sub['title'] ?></a></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>

                        <?php endforeach; ?>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
    <?php endif; ?>


    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->


    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper <?= $this->session->type == 2 ? 'sales' : '' ?>">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <?php if ($this->session->type == 2 && $this->router->fetch_class() != 'sales'): ?>
                <div class="row m-b-20">
                    <div class="col-12 text-right">
                        <a href="<?= base_url() ?>" style="color:#000"><h4><i class="fas fa-arrow-left m-t-20"></i> Back to Home</h4></a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row page-titles">
                <?php if (!isset($hide_breadcrumbs)):
                    $page_name = ucwords(str_replace('_', ' ', $this->router->fetch_class()));

                    if ($this->router->fetch_class() == 'purchaseorder') {
                        $page_name = 'Purchase Order';
                    }

                    ?>
                    <div class="col-md-5 align-self-center">
                        <h2 class="text-themecolor font-weight-bold"><?= $page_name ?></h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                            <li class="breadcrumb-item active"><?= $page_name?></li>
                        </ol>
                    </div>
                <?php endif; ?>

               <?php if (isset($header_right)): ?>

                   <div class="col-md-7 align-self-center text-right d-none d-md-block">
                       <?php foreach ($header_right as $action): ?>

                           <?php if ($action['type'] == 'button'): ?>

                               <?php if (isset($action['link'])): ?>
                                   <a href="<?= $action['link'] ?>" class="m-l-10 btn <?= isset($action['class']) ? $action['class'] : 'btn-info' ?>"><i class="<?= $action['icon'] ?>"></i> <?= $action['name'] ?></a>
                               <?php else: ?>
                                   <button type="button" class="m-l-10 btn <?= isset($action['class']) ? $action['class'] : 'btn-info' ?>" data-toggle="modal" data-target="<?= isset($action['modal']) ? '#'. $action['modal'] : '' ?>"><i class="<?= $action['icon'] ?>"></i> <?= $action['name'] ?></button>
                               <?php endif; ?>

                           <?php elseif ($action['type'] == 'select'):?>

                                    <select id="" class="selectpicker" name="<?= $action['name'] ?>" data-style="form-control btn-default nav-select">

                                       <?php foreach ($action['options'] as $key => $value): ?>

                                           <option value="<?= $key ?>" <?= isset($action['selected']) ? ($key == $action['selected'] ? 'selected' : '') : ''  ?>><?= $value ?></option>

                                       <?php endforeach; ?>

                                   </select>

                           <?php endif; ?>

                       <?php endforeach; ?>
                   </div>

               <?php endif; ?>

            </div>
