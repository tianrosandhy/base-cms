<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
<head>
@include ('main::template.metadata')
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
          <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
            <div class="row flex-grow">
              <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="auth-form-transparent text-left p-3">
                  <div style="margin-bottom:2em">
                    @include ('main::template.components.logo', ['height' => 20])
                  </div>
                  <h4>Welcome back!</h4>
                  <h6 class="font-weight-light">Happy to see you again!</h6>

                  <form class="pt-3" method="post">
                    {{ csrf_field() }}
                    @if(config('cms.social_login'))
                    <div class="mb-3" align="center">
                      @foreach(config('cms.social_driver') as $driver)
                        @if(config('services.'.$driver.'.client_id') && config('services.'.$driver.'.client_secret'))
                        <a href="{{ route('admin.social-login', ['mode' => $driver]) }}" class="btn btn-{{ $driver == 'facebook' ? 'primary' : ( $driver == 'google' ? 'danger' : 'secondary' ) }} btn-sm" style="margin-left:.5em; margin-right:.5em;">
                          <i class="fa fa-{{ $driver }}"></i> {{ ucfirst($driver) }} Login
                        </a>
                        @endif
                      @endforeach
                    </div>
                    @endif


                    <div class="form-group">
                      <label for="form-email">Email</label>
                      <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                          <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-account-outline text-primary"></i>
                          </span>
                        </div>
                        <input name="email" type="email" class="form-control border-left-0" id="form-email" placeholder="Email" maxlength="50" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword">Password</label>
                      <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                          <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-lock-outline text-primary"></i>
                          </span>
                        </div>
                        <input name="password" type="password" class="form-control border-left-0" id="exampleInputPassword" placeholder="Password">
                      </div>
                    </div>
                    <div class="my-2 d-flex justify-content-between align-items-center">
                      <div class="form-check">
                        <label class="form-check-label text-muted">
                          <input type="checkbox" class="form-check-input" name="remember">
                          Keep me signed in
                        </label>
                      </div>
                        @if(config('cms.admin.components.forgot_password'))
                        <a href="#" data-toggle="modal" data-target="#resetPasswordModal" class="card-link auth-link text-black">Forgot Password?</a>
                        @endif
                    </div>
                    <div class="my-3">
                      <button type="submit" class="btn btn-block btn-primary font-weight-medium auth-form-btn">LOGIN</button>
                    </div>

                    @if(config('cms.admin.components.register'))
                    <div class="row">
                      <div class="col-5">
                        <div class="text-center font-weight-light">
                          <a href="{{ admin_url('register') }}" class="text-primary">Create Acount</a>
                        </div>
                      </div>
                      <div class="col-7">
                        <div class="text-center font-weight-light">
                          <a href="#" data-toggle="modal" data-target="#resendModal">Resend Activation Link</a>
                        </div>
                      </div>
                    </div>
                    @endif
                  </form>
                </div>
              </div>
              <div class="col-lg-6 login-half-background d-flex flex-row">
                @if(setting('admin.background'))
                <img src="{{ storage_url(setting('admin.background')) }}" class="half-banner">
                @else
                <img src="{{ admin_asset('img/login-bg.jpg') }}" class="half-banner">
                @endif
                <p class="text-white font-weight-medium text-center flex-grow align-self-end copyright">Copyright &copy; {{ date('Y') }}  All rights reserved.</p>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    @include ('main::auth.partials.resend-validation')
    @include ('main::auth.partials.reset-password')
    @include ('main::auth.partials.resend-validation')
    @include ('main::template.modal')

    @include ('main::template.scripts')
    @include ('main::template.alert-management')
</body>
</html>


