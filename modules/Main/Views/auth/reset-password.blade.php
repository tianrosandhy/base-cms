<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
<head>
  @include ('main::template.metadata')
</head>
<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-xl-5 col-lg-7 col-md-9 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                @include ('main::template.components.logo')
              </div>
              <h4>Input Your New Password</h4>
              <h6 class="font-weight-light">Make sure the new password is kept secure but easy to remind</h6>
              <form class="pt-3" method="post">
                {{ csrf_field() }}

                <div class="row">
				  <div class="col-md-12">
				     <div class="form-group custom-form-group form-group-default">
				        <label>Email</label>
				        <div style="padding:.5em">{{ $user->email }}</div>
				     </div>
				  </div>
				</div>
				<div class="row">
				  <div class="col-md-6">
				     <div class="form-group custom-form-group form-group-default">
				        <label>New Password</label>
				        <input type="password" name="password" placeholder="8 Characters Minimum" class="form-control" required="" aria-required="true">
				     </div>
				  </div>

				  <div class="col-md-6">
				     <div class="form-group custom-form-group form-group-default">
				        <label>Repeat Password</label>
				        <input type="password" name="password_confirmation" placeholder="Retype your new password" class="form-control" required="" aria-required="true">
				     </div>
				  </div>
				</div>

                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Reset Password</button>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Already have an account? <a href="{{ admin_url('login') }}" class="text-primary">Login</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  @include ('main::template.modal')
  @include ('main::template.scripts')
</body>
</html>
