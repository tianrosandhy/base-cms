<meta charset="UTF-8">
<meta content="width=device-width" name="viewport"/>
<title>{{ $title ?? 'New Message' }}</title>
<style>
	html, body{
		margin:0;
		padding:0;
	}
	body{
		background-color:{{ $theme['body_background'] }};
		font-size:15px;
		font-family:sans-serif;
	}

	#wrapper{
		max-width:{{ $theme['max_width'] }}px;
	}

	h1{
		font-size:32px;
		margin:0 auto;
	}
	h2{
		font-family:sans-serif;
		font-weight:700;
		text-transform:uppercase;
		font-size:17px;
		margin:0 auto;
	}

	.button{
		text-transform:uppercase;
		text-decoration:none;
		font-size:18px;
		font-weight:bold;
		display:inline-block;
		border-radius:5px;
		background:{{ $theme['primary_color'] }};
		color : {{ $theme['text_color_light'] }};
		padding:.75em 1.75em;
	}
</style>