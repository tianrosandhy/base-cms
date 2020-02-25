var removedDiv;
//hide loader
$(window).on('load', function(){
	setTimeout(function(){
		hideLoader();
	}, 400);
});

$(function(){
	$("body").on("click", ".delete-button", function(e){
		e.preventDefault();
		delete_url = $(this).attr('href');
		delete_url = delete_url || $(this).attr('data-target');
		removedDiv = $(this).closest('.close-target');
		if($(this).attr('data-callback')){
			deletePrompt(delete_url, $(this).attr('data-callback'));
		}
		else{
			deletePrompt(delete_url);
		}
	});

	$(".site-sidebar li.active").closest('li:not(.active)').addClass('active show').find('.collapse').addClass('show active'); 
	$(".site-sidebar li.active").closest('li:not(.active)').addClass('active show').find('.collapse').addClass('show active'); 
	
	

	$(document).on('click', '.closer', function(e){
		e.preventDefault();
		$(this).closest('.form-group').find('input, select').val('').trigger('change').trigger('change.select2');
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

	


	//tagsinput blur action
	$(document).on('blur', 'input + .bootstrap-tagsinput input', function() {
		$(this).closest('.bootstrap-tagsinput').prev('input').tagsinput('add', $(this).val());
		$(this).val('');
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


	//form show loading after submit
	$(document).on('submit', "form[with-loader]", function(){
		showLoader();
	});


	initPlugin();

});

//loader show/hide helper
function showLoader(){
	$("#page-loader").fadeIn(300);
}
function hideLoader(){
	$("#page-loader").fadeOut(300);
}

//loader show/hide alias name only
var showLoading = showLoader;
var hideLoading = hideLoader;


function initPlugin(){
	//init select2
	$(".select2").each(function(){
		if($(this).attr('maxlength')){
			$(this).select2({
				maximumSelectionLength : $(this).attr('maxlength')
			});
		}
		else{
			$(this).select2();
		}
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
}



function toggleSuccess(reload){
	$(".success-handle").addClass('active');
	if(typeof tb_data != 'undefined'){
		if(tb_data && typeof reload == 'undefined'){
			tb_data.ajax.reload();
		}
	}
	setTimeout(function(){
		$(".success-handle").removeClass('active');
	}, 2500);
}


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
	hideLoading();
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


function error_handling(resp){
	if(resp.responseJSON){ //kalo berbentuk xhr object, translate ke json dulu
		resp = resp.responseJSON;
	}

	if(resp.errors){
		$.each(resp.errors, function(k, v){
			swal('error', [v[0]]);
		});
	}
	else if(resp.type && resp.message){
		swal('error', [resp.message]);
	}
	else{
		swal('error', ['Sorry, we cannot process your last request']);
	}
}



function deletePrompt(url, callback){
	dtcl = callback || '';

	output = '<p>Are you sure? Once deleted, you will not be able to recover the data</p><button class="btn btn-primary" data-dismiss="modal">Cancel</button> <button class="btn btn-danger" onclick="ajaxUrlProcess(\''+url+'\' '+ (dtcl ? ',\''+dtcl+'\'' : '') +')">Yes, Delete</button>';
	swal('Delete Confirmation', [output]);
}

function ajaxUrlProcess(url, callback, ajax_type){
	ajax_type = ajax_type || 'POST';
	cll = function(){};
	if(callback){
		cll = callback;
	}

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
				var fn = window[cll];
				console.log(fn);
				if(typeof fn == 'function'){
					fn();
				}

				if(removedDiv != 'undefined'){
					removedDiv.fadeOut(300);
					setTimeout(function(){
						removedDiv.remove();
					}, 300);
				}

				if(typeof tb_data != 'undefined'){
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

function makeId(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}
