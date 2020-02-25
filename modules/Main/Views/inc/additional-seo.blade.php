<!-- SEO Section -->
<?php
$seo = false;
if(isset($data->seo)){
	$seo[def_lang()] = json_decode($data->seo, true);
	foreach(available_lang() as $lang){
		if(method_exists($data, 'outputTranslate')){
			$seo[$lang] = json_decode($data->outputTranslate('seo', $lang, true), true);
		}
		else{
			$seo[$lang] = [];
		}
	}
}
?>
<div style="margin-top:50px;">
	<h4>SEO Section</h4>

	<div class="row">
		<div class="col-md-4">
			<div class="form-group custom-form-group searchable">
				<label class="text-uppercase">SEO Image</label>
				<div align="center">
					{!! MediaInstance::input('seo_image', (isset($seo[def_lang()]['image']) ? $seo[def_lang()]['image'] : '')) !!}
				</div>
			</div>
		</div>	
		<div class="col-md-8">
			<div class="form-group custom-form-group searchable">
				<label class="text-uppercase">SEO Title</label>
				@foreach(available_lang(true) as $lang)
				<div class="input-language" data-lang="{{ $lang }}" {!! $lang <> def_lang() ? 'style="display:none;"' : '' !!}>
					<input type="text" class="form-control" name="seo_title[{{ $lang }}]" id="input-seo_title-{{ $lang }}" value="{{ old('seo_title.'.$lang, (isset($seo[$lang]['title']) ? $seo[$lang]['title'] : '')) }}">
				</div>
				@endforeach
			</div>
			<div class="form-group custom-form-group searchable">
				<label class="text-uppercase">SEO Keywords</label>
				@foreach(available_lang(true) as $lang)
				<div class="input-language" data-lang="{{ $lang }}" {!! $lang <> def_lang() ? 'style="display:none;"' : '' !!}>
					<input data-role="tagsinput" type="text" class="form-control" name="seo_keyword[{{ $lang }}]" id="input-seo_keyword-{{ $lang }}" value="{{ old('seo_keyword.'.$lang, (isset($seo[$lang]['keyword']) ? $seo[$lang]['keyword'] : '')) }}">
				</div>
				@endforeach
			</div>
			<div class="form-group custom-form-group searchable">
				<label class="text-uppercase">SEO Description</label>
				@foreach(available_lang(true) as $lang)
				<div class="input-language" data-lang="{{ $lang }}" {!! $lang <> def_lang() ? 'style="display:none;"' : '' !!}>
					<textarea name="seo_description[{{ $lang }}]" class="form-control" id="input-seo_description-{{ $lang }}">{!! old('seo_description.'.$lang, (isset($seo[$lang]['description']) ? $seo[$lang]['description'] : '')) !!}</textarea>
				</div>
				@endforeach
			</div>
			
		</div>

	</div>

</div>

