@extends ('main::master')

@section ('content')
<h2>{{ $title }}</h2>

@include ('main::inc.lang-switcher', [
    'model' => app(config('model.themes')),
    'reload' => false
])

<div class="padd">
    Active Theme : <strong>{{ $active_theme->getName() }}</strong> <a href="{{ route('admin.themes.index') }}" class="badge badge-info"><i class="fa fa-refresh"></i> Change</a>
</div>

<form action="" method="post">
    {{ csrf_field() }}
    @include ('themes::partials.theme-option-tab')
    @if($theme_option)
    <div class="padd">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Theme Option</button>
    </div>
    @endif
</form>
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

                //process add 
                cloned = container.find('.array-wrapper .array-item').first().clone(true);
                container.find('.array-wrapper').append(cloned);
                last_item = container.find('.array-wrapper .array-item').last();
                last_item.find('input, textarea, select').val(null).trigger('change');
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
        $(".select-icon").select2({
            templateSelection : formatIcons,
            templateResult : formatIcons,
        });

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