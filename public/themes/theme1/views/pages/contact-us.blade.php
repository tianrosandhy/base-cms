<section id="contact" class="py-5">
	<div class="container clearfix">

		<div class="row clear-bottommargin">

			<div class="col-lg-4 bottommargin clearfix">
				<div class="feature-box fbox-center fbox-bg fbox-plain">
					<div class="fbox-icon">
						<a href="#"><i class="icon-map-marker2"></i></a>
					</div>
					<h3>Our Store<span class="subtitle">Melbourne, Australia</span></h3>
				</div>
			</div>

			<div class="col-lg-4 bottommargin clearfix">
				<div class="feature-box fbox-center fbox-bg fbox-plain">
					<div class="fbox-icon">
						<a href="#"><i class="icon-phone3"></i></a>
					</div>
					<h3>Speak to Us<span class="subtitle">(123) 456 7890</span></h3>
				</div>
			</div>

			<div class="col-lg-4 bottommargin clearfix">
				<div class="feature-box fbox-center fbox-bg fbox-plain">
					<div class="fbox-icon">
						<a href="#"><i class="icon-whatsapp"></i></a>
					</div>
					<h3>Chat Our<span class="subtitle">Whatsapp</span></h3>
				</div>
			</div>


		</div><!-- Contact Info End -->

		<div class="clear py-5"></div>

		<div class="col_half">
			<div class="fancy-title title-dotted-border">
				<h3>Contact Us</h3>
			</div>

			<div class="form-widget customjs">

				<div class="form-result"></div>

				<form class="nobottommargin" name="contact-form" action="{{ route('front.send-contact') }}" method="post">
					{{ csrf_field() }}
					<div class="form-process"></div>

					<div class="col_full">
						<label for="template-contactform-name">Full Name <small>*</small></label>
						<input type="text" id="template-contactform-name" name="full_name" value="{{ old('full_name') }}" class="sm-form-control required" />
					</div>

					<div class="col_half">
						<label for="template-contactform-email">Email <small>*</small></label>
						<input type="email" id="template-contactform-email" name="email" value="{{ old('email') }}" class="required email sm-form-control" />
					</div>

					<div class="col_half col_last">
						<label for="template-contactform-phone">Phone</label>
						<input type="text" id="template-contactform-phone" name="phone" value="{{ old('phone') }}" class="sm-form-control" />
					</div>

					<div class="clear"></div>

					<div class="col_full">
						<label for="template-contactform-message">Message <small>*</small></label>
						<textarea class="required sm-form-control" id="template-contactform-message" name="message" rows="6" cols="30">{{ old('message') }}</textarea>
					</div>

					<div class="col_full">
						@if(env('RECAPTCHA_SITE_KEY'))
						<div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
						@endif
					</div>

					<div class="col_full">
						<button name="submit" type="submit" id="submit-button" tabindex="5" value="Submit" class="button button-3d nomargin">Send Message</button>
					</div>
				</form>
			</div>

		</div><!-- Contact Form End -->

		@if(setting('social.google_map'))
		<div class="col_half col_last py-5">
			{!! setting('social.google_map') !!}
		</div>
		@endif

		<div class="clear"></div>

	</div>
</section>