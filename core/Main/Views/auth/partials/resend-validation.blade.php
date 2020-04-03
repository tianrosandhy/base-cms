<div class="modal fade slide-right" id="resendModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i></button>
        <div class="container-xs-height full-height">
          <div class="row-xs-height">
            <div class="modal-body col-xs-height col-middle text-center">
              <form action="{{ admin_url('resend-validation') }}" method="post">
                {{ csrf_field() }}
                <h4>Resend validation email</h4>
                <div class="form-group">
                  <label>Type your email</label>
                  <input type="email" class="form-control" name="email">
                </div>
                <div class="padd">
                  <button class="btn btn-primary">Resend</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
