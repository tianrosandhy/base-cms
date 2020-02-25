<form action="" class="media-filter">
  <div class="row">
    <div class="col-sm-4">
      <div class="form-group compact">
        <label>File Name</label>
        <input type="text" maxlength="32" class="form-control" name="filename">
      </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group compact">
        <label>Period</label>
        <input type="text" name="period" class="form-control" data-monthpicker>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group compact">
        <label>Extension</label>
        <select name="extension" class="form-control">
          <option value="">- Any -</option>
          <option value="image/jpeg">JPG</option>
          <option value="image/png">PNG</option>
          <option value="image/webp">WEBP</option>
          <option value="another">Another</option>
        </select>
      </div>
    </div>
  </div>

  <input type="reset" class="btn btn-danger btn-reset-filter" value="Reset Filter">
</form>