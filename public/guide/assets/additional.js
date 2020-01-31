$(function(){
    $('[data-toggle="offcanvas"]').on("click", function() {
      $('.sidebar-offcanvas').toggleClass('active')
    });

    $(document).on('click', '.doc-ajax-load', function(e){
        e.preventDefault();
        target_page = $(this).attr('data-value');
        $(".ajax-load-content").html('');
        $.ajax({
            url : 'ajax.php?page='+target_page,
            dataType : 'html',
            success : function(resp){
                $(".ajax-load-content").html(resp);
                $("html, body").animate({
                    scrollTop : 0
                });
                Prism.highlightAll();
            },
            error : function(resp){
                $(".ajax-load-content").html('<div class="alert alert-warning">Oops, the documentation page is still not updated yet. </div>');
            }
        });
    });

    //auto klik link pertama
    $(".doc-ajax-load").first().click();

    $(".menu-item").on('click', function(e){
        target = $(this).attr('href');
        if(target.indexOf(window.CURRENT_URL) === 0 || target.indexOf(window.CURRENT_URL) > 0){
            hash = target.split('#');
            if(hash.length > 1 && $('#'+hash[1]).length > 0){
                e.preventDefault();
                //scroll to this div
                scrollTo = $("#"+hash[1]).offset().top - 70;
                $('html, body').stop().animate({
                    scrollTop : scrollTo
                }, 500);
            }
        }
    });
});