<div class="array-container" data-max="{{ isset($param->maximum_array) ? intval($param->maximum_array) : 100 }}">
    <div class="array-wrapper">
        <?php
        $stored_group = ThemesInstance::grab($group.'.'.$card_name.'.'.$field_name);
        //get group_count
        $n = 1;
        if(!empty($stored_group)){
            foreach($stored_group as $prm => $array_data){
                if(count($array_data) > $n){
                    $n = count($array_data);
                }
            }
        }
        ?>
        @for($i=0; $i<$n; $i++)
            @include ('themes::partials.array-item', [
                'stored_group' => $stored_group,
            ])  
        @endfor
    </div>
    <div class="padd">
        <a href="#" class="btn btn-info btn-sm btn-add-row"><i class="fa fa-plus"></i> Add Row</a>        
    </div>
</div>
