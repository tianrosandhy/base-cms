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
		$(".uploaded-holder[data-hash='"+hash+"']").html('<strong>Uploaded Image : </strong><div class="uploaded"><img src="'+ STORAGE_URL + '/' + val+'" style="height:100px;"><span class="remove-asset" data-hash="'+hash+'">&times;</span></div>');
	});

	$(document).on('change', '.listen_uploaded_image_multiple', function(){
		hash = $(this).attr('data-hash');
		val = $(this).val();
		imgs = val.split('|');

		htmlPaste = '';
		$.each(imgs, function(k, v){
			htmlPaste += '<div class="uploaded"><img src="'+ STORAGE_URL + '/' + v+'" style="height:100px;"><span class="remove-asset-multiple" data-hash="'+hash+'" data-value="'+v+'">&times;</span></div>';
		});

		$(".uploaded-holder[data-hash='"+hash+"']").html(htmlPaste);
	});

	$(document).on('change', '.listen_uploaded_file', function(){
		hash = $(this).attr('data-hash');
		val = window.JSON.parse($(this).val());

		$(".uploaded-holder[data-hash='"+hash+"']").html('<div class="uploaded"><span class="file-alias">'+val.filename+'</span><span class="remove-asset-file" data-hash="'+hash+'">&times;</span></div>');

	});


	$(document).on('change', '.listen_uploaded_file_multiple', function(){
		hash = $(this).attr('data-hash');
		val = $(this).val();
		imgs = val.split('|');

		htmlPaste = '';
		$.each(imgs, function(k, v){
			vv = window.JSON.parse(v);
			htmlPaste += '<div class="uploaded"><span class="file-alias">'+vv.filename+'</span><span class="remove-asset-file-multiple" data-hash="'+hash+'" data-value=\''+v+'\'>&times;</span></div>';
		});

		$(".uploaded-holder[data-hash='"+hash+"']").html(htmlPaste);
	});





	$(document).on('click', '.remove-asset-multiple', function(){
		hash = $(this).attr('data-hash');
		value = $(this).attr('data-value');

		instance = $(this).closest('.uploaded');

		//get new value after delete
		oldval = $(".listen_uploaded_image_multiple[data-hash='"+hash+"']").val();
		pch = oldval.split('|');
		newval = '';
		if(pch.length > 1){
			$.each(pch, function(k, v){
				if(v != value){
					newval += value + '|';
				}
			});
			newval = newval.substring(0, newval.length - 1);
		}

		if(value.length > 0){
			$.ajax({
				url : window.BASE_URL + '/api/remove-images',
				type : 'POST',
				dataType : 'json',
				data : {
					_token : window.CSRF_TOKEN,
					filename : value
				},
				success : function(resp){
					instance.fadeOut();
					$(".listen_uploaded_image_multiple[data-hash='"+hash+"']").val(newval);
					setTimeout(function(){
						instance.html('');
						instance.show();
					}, 350);
				},
				error : function(resp){
					error_handling(resp);
				}
			});
		}
		else{
			instance.fadeOut();
		}

	});

	$(document).on('click', '.remove-asset', function(){
		instance = $(this).closest('.uploaded-holder');
		hash = $(this).attr('data-hash');
		filename = $(".listen_uploaded_image[data-hash='"+hash+"']").val();
		$.ajax({
			url : window.BASE_URL + '/api/remove-images',
			type : 'POST',
			dataType : 'json',
			data : {
				_token : window.CSRF_TOKEN,
				filename : filename
			},
			success : function(resp){
				$(".listen_uploaded_image[data-hash='"+hash+"']").val('');
				instance.fadeOut(300);
				setTimeout(function(){
					instance.html('');
					instance.show();
				}, 350);
			},
			error : function(resp){
				error_handling(resp);
			}
		});
	});

	$(document).on('click', '.remove-asset-file', function(){
		instance = $(this).closest('.uploaded-holder');
		hash = $(this).attr('data-hash');
		data = $(".listen_uploaded_file[data-hash='"+hash+"']").val();
		$.ajax({
			url : window.BASE_URL + '/api/remove-files',
			type : 'POST',
			dataType : 'json',
			data : {
				_token : window.CSRF_TOKEN,
				data : data
			},
			success : function(resp){
				$(".listen_uploaded_file[data-hash='"+hash+"']").val('');
				instance.fadeOut(300);
				setTimeout(function(){
					instance.html('');
					instance.show();
				}, 350);
			},
			error : function(resp){
				error_handling(resp);
			}
		});
	});



	$(document).on('click', '.remove-asset-file-multiple', function(){
		instance = $(this).closest('.uploaded');
		hash = $(this).attr('data-hash');
		value = $(this).attr('data-value');

		//get new value after delete
		oldval = $(".listen_uploaded_file_multiple[data-hash='"+hash+"']").val();
		pch = oldval.split('|');
		newval = '';
		if(pch.length > 1){
			$.each(pch, function(k, v){
				if(v != value){
					newval += value + '|';
				}
			});
			newval = newval.substring(0, newval.length - 1);
		}

		$.ajax({
			url : window.BASE_URL + '/api/remove-files',
			type : 'POST',
			dataType : 'json',
			data : {
				_token : window.CSRF_TOKEN,
				data : value
			},
			success : function(resp){
				$(".listen_uploaded_file_multiple[data-hash='"+hash+"']").val(newval);
				instance.fadeOut(300);
				setTimeout(function(){
					instance.html('');
					instance.show();
				}, 350);
			},
			error : function(resp){
				error_handling(resp);
			}
		});
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
				},
				init : function(){
					this.on("success", function(file, data){
						$(".dropzone_uploaded[data-hash='"+dropzonehash+"']").val(data).change();
						this.removeFile(file);
					});
					this.on("addedfile", function() {
				      if (this.files[1]!=null){
				        this.removeFile(this.files[0]);
				      }
				    });

				    this.on("queuecomplete", function(){
						this.removeAllFiles();
					});
				    this.on("error", function(file, err, xhr){
						this.removeAllFiles();
						error_handling(err);
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
				},
				init : function(){
					this.on("success", function(file, data){
						this.removeFile(file);
						oldval = $(".dropzone_uploaded[data-hash='"+dropzonehash+"']").val();
						if(oldval.length > 0){
							newval = oldval + '|' + data;
						}
						else{
							newval = data;
						}

						$(".dropzone_uploaded[data-hash='"+dropzonehash+"']").val(newval).change();
					});

				    this.on("queuecomplete", function(){
						this.removeAllFiles();
					});
				    this.on("error", function(file, err, xhr){
						this.removeAllFiles();
						error_handling(err);
				    });
				}
			});		
		}
		
	});	



	$(".filedropzone").each(function(){
		var ajaxurl = $(this).data("target");
		var dropzonehash = $(this).attr('data-hash');
		var maxsize = $(this).attr('upload-limit');
		if(maxsize.length == 0){
			maxsize = 5;
		}

		if($(this).find('.dz-default').length == 0){
			acc = $(this).attr('accept');
			if(acc.length == 0){
				acc = '*';
			}
			$(this).dropzone({
				url : ajaxurl,
				acceptedFiles : acc,
				maxFilesize : parseInt(maxsize),
				sending : function(file, xhr, formData){
					formData.append("_token", window.CSRF_TOKEN);
				},
				init : function(){
					this.on("success", function(file, data){
						data = window.JSON.stringify(data);
						$(".dropzone_uploaded[data-hash='"+dropzonehash+"']").val(data).change();
						this.removeFile(file);
					});
					this.on("addedfile", function() {
				      if (this.files[1]!=null){
				        this.removeFile(this.files[0]);
				      }
				    });

				    this.on("queuecomplete", function(){
						this.removeAllFiles();
					});
				    this.on("error", function(file, err, xhr){
						this.removeAllFiles();
						error_handling(err);
				    });
				}
			});		
		}
		
	});	



	$(".filedropzone-multiple").each(function(){
		var ajaxurl = $(this).data("target");
		var dropzonehash = $(this).attr('data-hash');
		var maxsize = $(this).attr('upload-limit');
		if(maxsize.length == 0){
			maxsize = 5;
		}

		if($(this).find('.dz-default').length == 0){
			acc = $(this).attr('accept');
			if(acc.length == 0){
				acc = '*';
			}
			$(this).dropzone({
				url : ajaxurl,
				acceptedFiles : acc,
				maxFilesize : maxsize,
				sending : function(file, xhr, formData){
					formData.append("_token", window.CSRF_TOKEN);
				},
				init : function(){
					this.on("success", function(file, data){
						data = window.JSON.stringify(data);

						oldval = $(".dropzone_uploaded[data-hash='"+dropzonehash+"']").val();
						if(oldval.length > 0){
							newval = oldval + '|' + data;
						}
						else{
							newval = data;
						}

						$(".dropzone_uploaded[data-hash='"+dropzonehash+"']").val(newval).change();

						this.removeFile(file);

					});
					this.on("queuecomplete", function(){
						this.removeAllFiles();
					});
				    this.on("error", function(file, err, xhr){
						this.removeAllFiles();
						error_handling(err);
				    });
				}
			});		
		}
		
	});	

}

</script>
@endpush