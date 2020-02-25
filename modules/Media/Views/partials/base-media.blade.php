<ul class="nav nav-tabs" id="media-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="select-uploaded-tab" data-toggle="tab" href="#select-uploaded" role="tab" aria-controls="select-uploaded" aria-selected="true"><i class="fa fa-table fa-fw"></i> Select Uploaded</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="manual-tab" data-toggle="tab" href="#manual" role="tab" aria-controls="manual" aria-selected="false"><i class="fa fa-upload fa-fw"></i> Upload Manually</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="select-uploaded" role="tabpanel" aria-labelledby="select-uploaded-tab">
    <div class="card card-body">
      <div class="form-filter-group">
        @include ('media::partials.form-filter')
      </div>

      <div class="media-holder"></div>
      @include ('media::partials.media-detail')
    </div>
  </div>
  <div class="tab-pane fade" id="manual" role="tabpanel" aria-labelledby="manual-tab">
    <div class="card card-body">
      @include ('media::inc.dropzone-multiple')
    </div>
  </div>
</div>
