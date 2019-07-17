<!-- Empty Space Only -->
<br><br>

<!-- Full Bleed Background Section : BEGIN -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #004b8d;">
    <tr>
        <td valign="top">
            <div style="max-width: 600px; margin: auto;" class="email-container">
                <!--[if mso]>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600">
                <tr>
                <td>
                <![endif]-->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 20px; text-align: center; font-family: sans-serif; font-size: 80%; line-height: 20px; color: #ffffff;">
                            @if(isset($reason))
                            <p style="margin: 0;">{!! $reason !!}</p>
                            @else
                            <p style="margin: 0;">You received this email because you are registered as member in {{ setting('site.title') }}.</p>
                            @endif

                            @if(isset($unsubscribeUrl))
                            <p style="margin:0;"><a href="{{ $unsubscribeUrl }}" style="color:#000;"><unsubscribe>Click to Unsubsribe</unsubscribe></a></p>
                            @endif
                        </td>
                    </tr>
                </table>
                <!--[if mso]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </div>
        </td>
    </tr>
</table>
<!-- Full Bleed Background Section : END -->