<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
    <style>
        .footer {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: normal;
            font-style: normal;
            font-stretch: normal;
            line-height: normal;
            letter-spacing: normal;
            color: #001737;
        }

        .footer__explications {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: normal;
            font-style:
                normal;
            font-stretch: normal;
            line-height: normal;
            letter-spacing: normal;
            color: #bbb;
            text-align: center;
            padding-top: 15px;
        }

        .app-name {
            width: 175px;
            height: 20px;
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 500;
            font-style: normal;
            font-stretch: normal;
            line-height: 1.11;
            letter-spacing: normal;
            text-align: center;
            color: #001737;
            padding-bottom: 22px;
            border-bottom: 1px dashed #ddd;
        }

        .app-description {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 500;
            font-style: normal;
            font-stretch: normal;
            line-height: normal;
            letter-spacing: normal;
            color: #bbb;
            float:right;
            margin-top: 10px;
        }

        .email-body {
            border-radius: 10px;
            background: #fff;
            padding: 30px 60px 20px 60px;
            margin-top: 10px;
            display: block;
        }

        .email-body p {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        .social-link a {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color:#007cc3;
            text-decoration: underline;
            margin-right: 5px;
        }

        .social-link a:visited {
            color:#007cc3;
        }

        .button {
            font-family: 'Poppins', sans-serif;
            background-color: #007cc3;
            border: none;color: #fff;
            padding: 10px 18px;
            border-radius: 4px;
            display: inline-block;
            margin-left: 10px;
            margin-bottom: 20px;
            margin-top: 15px;
            text-transform: capitalize;
            text-decoration: none;
        }

        .link {
            display: block;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 500;
            font-style: normal;
            font-stretch: normal;
            line-height: 1.71;
            letter-spacing: normal;
            color: #007cc3;
            margin-bottom: 15px;
        }

        .footer-content {
            color: #bbbbbb !important;
            line-height: 1.5em;
            margin-top: 20px;
            text-align: left;
            font-size: 12px !important;
        }
    </style>
</head>
<body>

<table style="background:#f3f3f3; width:100%;height: 100%;" cellpadding="0" cellspacing="0" border="0">
    <tbody>
    <tr>
        <td style="padding: 50px;">
            <table style="width: 550px;height: 100%;margin: 0 auto" cellpadding="0" cellspacing="0" border="0">
                <tbody>
                {!! $header ?? '' !!}

                <!-- Email Body -->
                <tr>
                    <td class="email-body">
                        {!! $slot !!}

                        {!! $subcopy ?? '' !!}
                    </td>
                </tr>
                </tbody>
            </table>

            <table style="width: 270px;height: 100%;margin: 20px auto 0 auto;" cellpadding="0" cellspacing="0" border="0">
                <tbody>
                <tr>
                    <td>
                        <table style="float:left;margin-right:15px;" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td class="social-link">
                                    <a href="https://laravelshopper.com">Website</a>
                                    <span>·</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table style="float:left;margin-right:15px;" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td class="social-link">
                                    <a href="https://github.com/laravel-shopper/framework">Github</a>
                                    <span>·</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table style="float:left;margin-right:15px;" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td class="social-link">
                                    <a href="https://twitter.com/laravelshopper">Twitter</a>
                                    <span>·</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table style="float:left;" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td class="social-link">
                                    <a href="https://laravelcm.slack.com">Slack</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <table style="margin: 20px auto 10px auto;" cellpadding="0" cellspacing="0" border="0">
                <tbody>
                {!! $footer ?? '' !!}
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
