@push ('style')
    <link rel="stylesheet" href="{{ admin_asset('vendor/dropzone/css/dropzone.css') }}">
@endpush

@push ('script')
<script src="{{ admin_asset('vendor/dropzone/dropzone.js') }}"></script>
<script>
//dropzone instance
Dropzone.autoDiscover = false;
$(function(){
	if( 
		$(".mydropzone").length || 
		$(".mydropzone-multiple").length || 
		$(".filedropzone").length ||
		$(".filedropzone-multiple").length 
	){
		refreshDropzone();
	}

	$(document).on('change', '.listen_uploaded_image', function(){
		hash = $(this).attr('data-hash');
		val = $(this).val();
		$(".uploaded-holder[data-hash='"+hash+"']").html('<div class="uploaded"><a href="'+ STORAGE_URL + '/' + val+'" data-fancybox="gallery"><img src="'+ STORAGE_URL + '/' + val+'" style="height:100px;"></a><span class="remove-asset" data-hash="'+hash+'">&times;</span></div>');
	});

	$(document).on('change', '.listen_uploaded_image_multiple', function(){
		hash = $(this).attr('data-hash');
		val = $(this).val();
		imgs = val.split('|');

		htmlPaste = '';
		$.each(imgs, function(k, v){
			htmlPaste += '<div class="uploaded"><img src="'+ STORAGE_URL + '/' + v+'"><span class="remove-asset-multiple" data-hash="'+hash+'" data-value="'+v+'">&times;</span></div>';
		});

		$(".uploaded-holder[data-hash='"+hash+"']").html(htmlPaste);
	});


});

function refreshDropzone(){
	$(".mydropzone").each(function(){
		var ajaxurl = $(this).data("target");
		var dropzonehash = $(this).attr('data-hash');
		var maxsize = $(this).attr('upload-limit');
		if(maxsize.length == 0){
			maxsize = 2;
		}

		if($(this).find('.dz-default').length == 0){
			$(this).dropzone({
				url : ajaxurl,
				acceptedFiles : 'image/*',
				maxFilesize : maxsize,
				sending : function(file, xhr, formData){
					formData.append("_token", window.CSRF_TOKEN);
					disableAllButtons();
				},
				init : function(){
					this.on("success", function(file, data){
						data = window.JSON.parse(file.xhr.responseText);
						$(".dropzone_uploaded[data-hash='"+dropzonehash+"']").val(data.message).change();
						this.removeFile(file);
						enableAllButtons();
					});

					this.on("queuecomplete", function(){
						this.removeAllFiles();
						enableAllButtons();
						afterFinishUpload();
					});
					this.on("error", function(file, err, xhr){
						error_handling(err);
						this.removeAllFiles();
						enableAllButtons();
					});
				}
			});		
		}
		
	});	


	$(".mydropzone-multiple").each(function(){
		var ajaxurl = $(this).data("target");
		var dropzonehash = $(this).attr('data-hash');
		var maxsize = $(this).attr('upload-limit');
		if(maxsize.length == 0){
			maxsize = 5;
		}

		if($(this).find('.dz-default').length == 0){
			$(this).dropzone({
				url : ajaxurl,
				acceptedFiles : 'image/*',
				maxFilesize : maxsize,
				sending : function(file, xhr, formData){
					formData.append("_token", window.CSRF_TOKEN);
					disableAllButtons();
				},
				init : function(){
					this.on("success", function(file, data){
						data = window.JSON.parse(file.xhr.responseText);
						oldval = $(".dropzone_uploaded[data-hash='"+dropzonehash+"']").val();
						if(oldval.length > 0){
							newval = oldval + '|' + data.message;
						}
						else{
							newval = data.message;
						}

						$(".dropzone_uploaded[data-hash='"+dropzonehash+"']").val(newval).change();
						enableAllButtons();
					});

					this.on("queuecomplete", function(){
						this.removeAllFiles();
						enableAllButtons();
						afterFinishUpload();
					});

					this.on("error", function(file, err, xhr){
						enableAllButtons();
						this.removeAllFiles();
						error_handling(err);
					});
				}
			});		
		}
		
	});	


}

function disableAllButtons(){
	$("button, a").attr('disabled', 'disabled').addClass('disabled').addClass('only-this');
}

function enableAllButtons(strict){
	if(strict){
		$(".only-this").removeAttr('disabled').removeClass('disabled').removeClass('only-this');
	}
	else{
		$("button, a").removeAttr('disabled').removeClass('disabled');
	}
}

function afterFinishUpload(){
	$('#media-tab #select-uploaded-tab').tab('show');
	openPage(1, true);
}

function goToUpload(){
	$("#media-tab #manual-tab").tab('show');
}

</script>
@endpush