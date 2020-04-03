@extends ('main::master')

@section ('content')
<div class="header-box">
    <h2 class="display-4 mb-3">{{ $title }}</h2>

    @include ('main::inc.lang-switcher', [
        'model' => model('themes'),
        'reload' => false
    ])

    <div class="padd">
        Active Theme : <strong>{{ $active_theme->getName() }}</strong> <a href="{{ route('admin.themes.index') }}" class="badge badge-info"><i class="fa fa-refresh"></i> Change</a>
    </div>    
</div>

<div class="content-box">
    <form action="" method="post">
        {{ csrf_field() }}
        @include ('themes::partials.theme-option-tab')
        @if($theme_option)
        <div class="save-buttons stick">
            <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-save"></i> Save Theme Option</button>
        </div>
        @endif
    </form>
</div>
@stop

@push ('script')
{!! MediaInstance::assets() !!}

<script>
    $(function(){
        initThemePlugin();

        $(".language-switcher .btn-lang-static.active").click();

        $(document).on('click', ".btn-add-row", function(e){
            e.preventDefault();
            container = $(this).closest('.array-container');
            max = parseInt(container.attr('data-max'));
            current_item_count = container.find('.array-wrapper .array-item').length;
            if(current_item_count < max){
                if($(".select-icon").hasClass('select2-hidden-accessible')){
                    $(".select-icon").select2('destroy');
                }
                if($(".theme-select2").hasClass('select2-hidden-accessible')){
                    $(".theme-select2").select2('destroy');
                }

                //process add 
                cloned = container.find('.array-wrapper .array-item').first().clone(true);
                container.find('.array-wrapper').append(cloned);
                last_item = container.find('.array-wrapper .array-item').last();
                last_item.find('input, textarea, select').val(null).trigger('change');

                //themeoption clone image
                grab_hash = last_item.find('[data-hash]').last().attr('data-hash');
                if(grab_hash){
                    hash_label = grab_hash.replace('#', '');
                    new_hash = makeId(30);
                    last_item.find('input' + grab_hash).attr('id', new_hash);
                    last_item.find('[data-target]').attr('data-target', '#' + new_hash);
                    last_item.find('[data-hash]').attr('data-hash', '#'+new_hash);
                    last_item.find('.image-closer').click();
                }
                initThemePlugin();
            }
            arrayMonitor();
        });

        $(document).on('click', '.array-item .custom-closer', function(){
            console.log($(this).closest('.array-container').find('.array-item').length);
            if($(this).closest('.array-container').find('.array-item').length > 1){
                $(this).closest('.array-item').remove();
            }
            else{
                $(this).closest('.array-item').find('input, textarea, select').val(null).trigger('change');
            }
            arrayMonitor();
        });

    });

    function initThemePlugin(){
        $(".select-icon:not(.select2)").select2({
            templateSelection : formatIcons,
            templateResult : formatIcons,
        });


        $(".theme-select2:not(.select-icon)").select2();

        arrayMonitor();
    }

    function arrayMonitor(){
        $(".array-container").each(function(){
            max = parseInt($(this).attr('data-max'));
            current_item_count = $(this).find('.array-wrapper .array-item').length;
            if(current_item_count >= max){
                $(this).find('.btn-add-row').hide();
            }
            else{
                $(this).find('.btn-add-row').show();
            }
        });        
    }

    function formatIcons(icon){
        return $('<span><i class="fa fa-fw '+ $(icon.element).data('icon') +'"></i> '+ icon.text +'</span>');
    }
</script>
@endpush