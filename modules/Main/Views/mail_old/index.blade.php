@extends ('main::mail.partials.template')
@section ('content')
<?php
if(!isset($title))
$title = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorem, cumque.';

if(!isset($content))
$content = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil dolor nemo, corrupti iure temporibus magnam nobis officiis beatae, optio aperiam mollitia labore dolore omnis explicabo harum porro? Alias et, molestias quae doloribus illo odio aut error possimus rerum quaerat velit praesentium recusandae ea ab amet consequatur asperiores veritatis optio, dolores.';

if(!isset($button)){
$button = [
    [
        'label' => 'Go to Our Site',
        'url' => url('/')
    ],
];
}
?>
<!-- 1 Column Text + Button : BEGIN -->
<tr>
    <td style="background-color: #ffffff;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td style="padding: 20px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                    <h1 style="margin: 0 0 10px; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">{{ $title }}</h1>
                    <p style="margin: 0 0 10px;">{!! $content !!}</p>
                </td>
            </tr>
            @if(isset($button))
            <tr>
                <td style="padding: 0 20px 20px;">
                    <!-- Button : BEGIN -->
                    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: auto;">
                        <tr>
                            <td class="button-td" style="border-radius: 4px;">
                                @foreach($button as $btn)
                                 <a class="button-a button-a-primary" href="{{ $btn['url'] }}" style="background: #222222; border: 1px solid #000000; font-family: sans-serif; font-size: 15px; line-height: 15px; text-decoration: none; padding: 13px 17px; border-radius: 4px;"><span class="button-link" style="color:#ffffff">{{ $btn['label'] }}</span></a>
                                 @endforeach
                            </td>
                        </tr>
                    </table>
                    <!-- Button : END -->
                </td>
            </tr>
            @endif

        </table>
    </td>
</tr>
<!-- 1 Column Text + Button : END -->
@stop