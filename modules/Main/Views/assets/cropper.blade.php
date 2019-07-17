@push ('style')
<link rel="stylesheet" href="{{ admin_asset('vendor/cropperjs/cropper.min.css') }}">
<style>
	.cropper-label-button input{
		display:none;
	}
	
	.cropper-exec{
		width:100%;
	}

	.cropper-exec img{
		max-width:100%;
	}

	.uploaded img{
		width:100%!important;
		height:auto!important;
	}

	.uploaded-holder{
		padding:.5em;
	}
</style>
@endpush

@push ('script')
<script src="{{ admin_asset('vendor/cropperjs/cropper.min.js') }}"></script>
<script src="{{ admin_asset('vendor/cropperjs/jquery-cropper.min.js') }}"></script>
<script>
$(function(){
	$(document).on('change', '.cropper-file', function(){
		$allowedExtension = ['jpg', 'jpeg', 'gif', 'png'];

		var filename = $(this).val();
		var extension = filename.replace(/^.*\./, '');
		extension = extension.toLowerCase();
		if($.inArray(extension, $allowedExtension) < 0){
			//wrong file
			e.preventDefault();
			swal('error', ['Invalid file format uploaded']);
		}
		else{
			initializeCropper($(this));
			$(this).val('');
		}		
	});

	$(document).on('click', '.btn-cancel-crop', function(e){
		e.preventDefault();
		destroyCropper();
	});

	$(document).on('click', '.cropper-runner', function(e){
		e.preventDefault();
		target = $(this).attr('data-target');
		canvas = runCropper(target);
		basedata = canvas.toDataURL('image/jpeg');

		//handle as raw base data
		$.ajax({
			url : $(this).attr('data-upload'),
			type : 'POST',
			dataType : 'json',
			data : {
				_token : window.CSRF_TOKEN,
				image : basedata,
				target : target
			},
			success : function(resp){
				destroyCropper();
				$(".listen_uploaded_image[data-hash='"+resp.div_target+"']").val(resp.path).bind('change');
				$(".uploaded-holder[data-hash='"+resp.div_target+"']").html('<strong>Uploaded Image : </strong><div class="uploaded"><img src="'+ resp.url +'"><span class="remove-asset" data-hash="'+resp.div_target+'">&times;</span></div>');
			},
			error : function(resp){
				swal('error', ['Sorry, we cannot process your cropped image']);
			}
		});
	});
});

function initializeCropper(instance){
	//always destroy before initialize();
	destroyCropper();

	var f = instance[0].files[0];
	fileurl = window.URL.createObjectURL(f);

	dataid = instance.attr('id');
	$(".cropper-exec[data-id='"+dataid+"']").show();
	$("img[data-id='"+dataid+"']").attr('src', fileurl);

	x_ratio = $("img[data-id='"+dataid+"']").attr('data-x-ratio');
	y_ratio = $("img[data-id='"+dataid+"']").attr('data-y-ratio');

	if(x_ratio && y_ratio){
		ratio = x_ratio / y_ratio;
	}
	else{
		ratio = 1;
	}

	$("img[data-id='"+dataid+"']").cropper({
		aspectRatio : ratio,
	});
}

function destroyCropper(){
	$(".cropper-image").cropper('destroy');
	$(".cropper-exec").hide();
}

function runCropper(hash_target){
	img_instance = $('img[data-id="'+hash_target+'"]');
	x_ratio = img_instance.attr('data-x-ratio') ? img_instance.attr('data-x-ratio') : 300;
	y_ratio = img_instance.attr('data-y-ratio') ? img_instance.attr('data-y-ratio') : 300;

	canvas = img_instance.cropper('getCroppedCanvas', {
		width : x_ratio,
		height : y_ratio
	});
	return canvas;
}

</script>
@endpush