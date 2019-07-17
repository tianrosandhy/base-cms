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
              <h4>New here?</h4>
              <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
              <form class="pt-3" method="post">
                {{ csrf_field() }}

                <div class="row">
                  <div class="col-md-12">
                     <div class="form-group custom-form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" placeholder="Your Full Name" class="form-control" required="" aria-required="true" value="{{ old('name') }}">
                     </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                       <div class="form-group custom-form-group">
                          <label>Email</label>
                          <input type="email" name="email" placeholder="We will send loging details to you" class="form-control" required="" aria-required="true" value="{{ old('email') }}">
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                       <div class="form-group custom-form-group">
                          <label>Password</label>
                          <input type="password" name="password" placeholder="Minimum of 8 Characters" class="form-control" required="" aria-required="true">
                       </div>
                    </div>

                    <div class="col-md-6">
                       <div class="form-group custom-form-group">
                          <label>Repeat Password</label>
                          <input type="password" name="password_confirmation" placeholder="Retype your password" class="form-control" required="" aria-required="true">
                       </div>
                    </div>
                </div>


                <div class="mb-4">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input" name="toc" value="1">
                      I agree to all Terms & Conditions
                    </label>
                  </div>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN UP</button>
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
