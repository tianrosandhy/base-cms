<section id="contact" class="py-5">
	<div class="container clearfix">

		<div class="clear py-5"></div>

		<div class="col_half">
			<div class="fancy-title title-dotted-border">
				<h3 class="my-5 display-header">Contact Us</h3>
			</div>

			<div class="form-widget customjs">

				<div class="form-result"></div>

				<form class="nobottommargin" name="contact-form" action="{{ route('front.send-contact') }}" method="post">
					{{ csrf_field() }}
					<div class="form-process"></div>

					<div class="form-group">
						<label>Full Name <small>*</small></label>
						<input type="text" id="template-contactform-name" name="full_name" value="{{ old('full_name') }}" class="form-control required" />
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Email <small>*</small></label>
								<input type="email" id="template-contactform-email" name="email" value="{{ old('email') }}" class="required email form-control" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Phone</label>
								<input maxlength="15" type="tel" id="template-contactform-phone" name="phone" value="{{ old('phone') }}" class="form-control" />
							</div>
						</div>
					</div>

					<div class="clear"></div>

					<div class="form-group">
						<label>Message <small>*</small></label>
						<textarea class="required form-control" id="template-contactform-message" name="message" rows="6" cols="30">{{ old('message') }}</textarea>
					</div>

					<div class="form-group">
						@if(env('RECAPTCHA_SITE_KEY'))
						<div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
						@endif
					</div>

					<div class="form-group">
						<button name="submit" type="submit" id="submit-button" tabindex="5" value="Submit" class="btn btn-primary">Send Message</button>
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