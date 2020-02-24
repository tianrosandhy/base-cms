@include ('media::inc.dropzone-asset')

@push ('modal')
<div class="modal fade fill-in" id="mediaModal" tabindex="-1" role="dialog" aria-hidden="true">
  <button type="button" class="modal-custom-close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="text-left p-b-5 default-modal-title">Choose Image</h5>
      </div>
      <div class="modal-body default-modal-content">

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

      </div>
    </div>
  </div>
</div>
@endpush

@push ('script')
<script>
var DEFAULT_THUMBNAIL = '{{ admin_asset('img/broken-image.jpg') }}';
$(function(){
  $(document).on('click', ".media-set-image", function(){
    openPage();
    $("#mediaModal").attr('data-target', $(this).attr('data-target'));
    $("#mediaModal").modal('show');
  });


  $(document).on('click', ".media-container .image-thumb-selection", function(e){
    e.preventDefault();
    $(".media-detail .image-thumbnail").attr('src', $(this).attr('data-thumb'));
    $(".media-detail .filename").html($(this).attr('data-filename'));
    $(".media-detail .url a").html($(this).attr('data-origin'));
    $(".media-detail .url a").attr('href', $(this).attr('data-origin'));
    $("#media-selected-id").val($(this).attr('data-id'));
    $(".media-detail").show();
  });

  $(document).on('click', ".media-detail .image-closer", function(){
    $(".media-detail").fadeOut(250);
  });

  $(document).on('click', '#set-this-image', function(e){
    e.preventDefault();

    let resp = new Object;
    resp['id'] = $("#media-selected-id").val();
    resp['thumb'] = $('.media-detail .thumbnail_size').val();

    target = $('#mediaModal').attr('data-target');
    $('.image-input-holder[data-hash="'+target+'"] img').attr('src', $(".media-detail .image-thumbnail").attr('src'));
    $(target).val(window.JSON.stringify(resp));
    $(".media-detail").fadeOut();
    $("#mediaModal").modal('hide');
  });

  $(document).on('click', '.image-input-holder .image-closer', function(){
    ih = $(this).closest('.image-input-holder');
    $('input' + ih.attr('data-hash')).val('');
    ih.find('img').attr('src', window.DEFAULT_THUMBNAIL);
  });

  $(document).on('click', '.btn-add-images', function(e){
    //khusus utk multiple images
    e.preventDefault();
    input = $("template#single-input").html();
    $(this).closest('.input-multiple-holder').find('.input-multiple-container').append(input);


    newhash = makeId(25);
    last_obj = $(".input-multiple-holder .image-input-holder:last");
    last_obj.attr('data-hash', '#'+newhash);
    last_obj.find('input[type="hidden"]').attr('id', newhash);
    last_obj.find('.media-set-image').attr('data-target', '#'+newhash);

  });

  $(document).on('click', '.input-multiple-holder .image-closer', function(e){
    $(this).closest('.image-input-holder').remove();
    if($(".input-multiple-holder .image-input-holder").length == 0){
      $(".btn-add-images").trigger('click');
    }
  });

});

function openPage(page){
  page = page || 1;
  target_url = window.BASE_URL + '/media/load?page=' + page;

  $("#page-loader").show();
  $.ajax({
    url : target_url,
    type : 'GET',
    dataType : 'html',
    success : function(resp){
      $(".media-holder").html(resp);
      $("#page-loader").hide();

      thumb_width = $(".media-holder img").width();
      $(".media-holder img").height(thumb_width);

    },
    error : function(resp){
      swal('error', ['Failed to load the media']);
      $("#page-loader").hide();
    }
  });
}

</script>
@endpush