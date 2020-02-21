<section id="wrapper">
    <div class="login-register" style="background: url('<?= assets_url('images/login-register.jpg') ?>')">
        <div class="row justify-content-md-center">
            <div class="col-lg-2 col-md-4 col-sm-4 vge_logo_holder">
                 <img width="100%" height="" src="<?= assets_url('images/vge_logo.png')?>" alt="">
            </div>
            <div class="col-xl-3 col-lg-3 col-md-5 col-sm-5 login_holder">
                <form class="form-horizontal form-material" id="loginForm" style="width:100%">
                    <h3 class="box-title m-b-20" style="color:#165e15">Sign In</h3>
                    <div style="display:none" class="alert alert-danger error_alert"></div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control log_int" type="text" name="username" required="" placeholder="Username"> </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control log_int" type="password" name="password" required="" placeholder="Password"> </div>
                    </div>
                    <div class="form-group row">

                    </div>
                    <div class="form-group text-center">
                        <div class="col-xs-12 p-b-20">
                            <button class="btn btn-block btn-info btn-log" type="submit">Log In</button>
                        </div>
                    </div>
                    <!-- <div class="form-group text-center">
                        <div class="col-xs-12 p-b-20">
                            <button class="btn btn-block btn-info btn-log" type="submit">
                                <span class="spinner-border spinner-border-sm" style="" role="status" aria-hidden="true"></span>
                                <span class="">Loading...</span>
                            </button>
                        </div>
                    </div> -->
                    <div class="row">
                    </div>
                    <div class="form-group m-b-0"></div>
                </form>
            </div>
        </div>

    </div>
</section>

<!-- <div class="">
   <form id="loginForm" class="login" method="">
      <div class="alertError"></div>
      <label for="username">Username</label><input type="text" name="username" value="">
      <label for="password">Password</label><input type="password" name="password" value="">
      <button type="submit">Login</button>
   </form>
</div> -->
