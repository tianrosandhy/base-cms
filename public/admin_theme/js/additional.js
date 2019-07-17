var removedDiv;
//hide loader
$(window).on('load', function(){
	setTimeout(function(){
		$("#page-loader").fadeOut();
	}, 400);
});

$(function(){
	$("body").on("click", ".delete-button", function(e){
		e.preventDefault();
		delete_url = $(this).attr('href');
		delete_url = delete_url || $(this).attr('data-target');
		removedDiv = $(this).closest('.close-target');
		deletePrompt(delete_url);
	});

	$(".site-sidebar li.active").closest('li:not(.active)').addClass('active show').find('.collapse').addClass('show active'); 
	$(".site-sidebar li.active").closest('li:not(.active)').addClass('active show').find('.collapse').addClass('show active'); 
	
	//richtext management
	loadTinyMce();

	//slug management
	if($("[data-slug]").length){
		$("[data-slug]").each(function(){
			var target = $(this).attr('data-slug');
			$('body').on('keyup', "#input-"+target, function(){
				current = $(this).val();
				tch = $(this).attr('id').split('-');
				sisa = tch.splice(1);
				tg = sisa.join('-');
				$("[data-slug='"+tg+"']").val(convertToSlug(current));
			});
		});
	}

	$(document).on('click', '.closer', function(e){
		e.preventDefault();
		$(this).prev('input, select').val('').trigger('change');
	});

	//switchery init
	$('body').on('change', ".js-switch", function(){
		$.ajax({
			url : $(this).attr('data-ajax'),
			type : 'POST',
			dataType : 'json',
			data : {
				_token : window.CSRF_TOKEN,
				id : $(this).attr('data-id'),
				field : $(this).attr('name'),
				value : $(this).is(':checked') ? 1 : 0
			},
			error : function(resp){
				error_handling(resp);
			}
		});
	});

	//init select2
	$(".select2").select2();


	//tagsinput blur action
	$('input + .bootstrap-tagsinput input').on('blur', function() {
		$(this).closest('.bootstrap-tagsinput').prev('input').tagsinput('add', $(this).val());
		$(this).val('');
	});

	//datepicker
	$("[data-datepicker]").each(function(){
		$(this).datetimepicker({
			viewMode : 'years',
			format : 'YYYY-MM-DD'
		});
	});
	
	//datepicker
	$("[data-datetimepicker]").each(function(){
		$(this).datetimepicker({
			viewMode : 'years',
			format : 'YYYY-MM-DD HH:mm:ss'
		});
	});
	

    //timepicker
    $('[data-timepicker]').timepicker({
        timeFormat: 'HH:mm',
        interval : 5,
        startTime: '06:00',
        dynamic: false,
        dropdown: false,
        scrollbar: true
    });
	

    //textarea counter
	$("[data-textarea]").each(function(){
		ml = $(this).attr('maxlength');
		if(typeof ml != 'undefined'){
			$(this).next('.feedback').html('<strong>'+ml+'</strong> characters remaining');
		}
	});

	$(document).on('change keyup', "[data-textarea]", function(){
		slength = $(this).val().length;
		limit = parseInt($(this).attr('maxlength') || 300);
		remaining = limit - slength;
		$(this).next('.feedback').html('<strong>'+remaining + '</strong> characters remaining');
	});
    



	//lang switcher
	$("body").on('click', '.btn-lang-switcher', function(){
		lang = $(this).attr('data-lang');
		reload = $(this).hasClass('reload');
		$.ajax({
			url : BASE_URL + '/lang/' + lang,
			type : 'POST',
			dataType : 'json',
			data : {
				_token : window.CSRF_TOKEN
			},
			success : function(resp){
				if(reload){
					location.reload();
				}
			}
		});
	});

});

function swal(type, messages){
	$("#alertModal .modal-header h5").html(type);
	out = '';
	$(messages).each(function(ky, msg){
		if(type == 'error'){
			type = 'danger';
		}
		out += '<div class="alert alert-'+type+' text-center">'+msg+'</div>';
	});
	$("#alertModal .alert-modal-content").html(out);
	$("#alertModal").modal();
}

//init slugify
function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-')
        ;
}

//init richtext
function loadTinyMce(){
	tinymce.init({
		selector : 'textarea[data-tinymce]',
		height : 400, 
		theme : 'modern',
		plugins : 'searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help code',
		toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat code',
		image_advtab: true,
//		images_upload_url : BASE_URL + '/api/store-images',
		relative_urls : false,
		remove_script_host : false,
		convert_urls : true,

		images_upload_handler: function (blobInfo, success, failure) {
			var xhr, formData;

			xhr = new XMLHttpRequest();
			xhr.withCredentials = false;
			xhr.open('POST', BASE_URL + '/api/store-tinymce');
			xhr.setRequestHeader('X-CSRF-TOKEN', window.CSRF_TOKEN);

			xhr.onload = function() {
			  var json;
			  if (xhr.status != 200) {
			    failure('HTTP Error: ' + xhr.status);
			    return;
			  }
			  json = JSON.parse(xhr.responseText);
			  if (!json || typeof json.location != 'string') {
			    failure('Invalid JSON: ' + xhr.responseText);
			    return;
			  }
			  success(json.location);
			};

			formData = new FormData();
			formData.append('file', blobInfo.blob(), blobInfo.filename());

			xhr.send(formData);
		}

	});
}

function error_handling(resp){
	if(resp.errors){
		$.each(resp.errors, function(k, v){
			swal('error', [v[0]]);
		});
	}

	if(resp.responseJSON){ //kalo berbentuk xhr object, translate ke json dulu
		resp = resp.responseJSON;
	}

	if(resp.type && resp.message){
		swal('error', [resp.message]);
	}
	else{
		swal('error', ['Sorry, we cannot process your last request']);
	}
}



function deletePrompt(url){
	output = '<p>Are you sure? Once deleted, you will not be able to recover the data</p><button class="btn btn-primary" data-dismiss="modal">Cancel</button> <button class="btn btn-danger" onclick="ajaxUrlProcess(\''+url+'\')">Yes, Delete</button>';
	swal('Delete Confirmation', [output]);
}

function ajaxUrlProcess(url, ajax_type){
	ajax_type = ajax_type || 'POST';

	$.ajax({
		url : url,
		type : ajax_type,
		dataType : 'json',
		data : {
			_token : window.CSRF_TOKEN
		},
		success : function(resp){
			if(resp.type == 'success'){
				swal('success', [resp.message]);
				if(removedDiv != undefined){
					removedDiv.fadeOut(300);
					setTimeout(function(){
						removedDiv.remove();
					}, 300);
				}
				if(typeof tb_data != undefined){
					tb_data.ajax.reload();
				}
			}
			else if(resp.type == 'error'){
				swal('error', [resp.message]);
			}
		},
		error : function(resp){
			error_handling(resp);
		}
	});
}

function defaultModal(title, content){
	title = title || '';
	content = content || '';

	$("#defaultModal .default-modal-title").html(title);
	$("#defaultModal .default-modal-content").html(content);
	$("#defaultModal").modal('show');
}