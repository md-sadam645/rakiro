
<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Rakiro Sending Email</title>
    <meta name="description" content="New Account Email Template.">
    <link rel="shortcut icon" href="{{url('images/logo/rlogo.png')}}">
    <style type="text/css">
        a:hover {text-decoration: underline !important;}
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #fff;" leftmargin="0">
    <!-- 100% body table -->
    <table cellspacing="0" border="0" cellpadding="0" width="100%"
        style="background-color: #fff; @import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
        <tr>
            <td>
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color: #fff;">
                    {{-- <tr>
                        <td style="text-align:center;">
                            <a href="https://www.rakiro.net/" title="logo" target="_blank">
                            <img width="150" src="https://www.rakiro.net/template/front_assets/images/logo/logo.png" title="logo" alt="logo">
                          </a>
                        </td>
                    </tr> --}}
                    <tr>
                        <td>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #fff;">
                              
                                <tr>
                                    <td>
                                        <div style="background-color: #fff;font-size:15px; margin:8px 0 0; line-height:24px;">
                                            @php
                                                echo str_replace('"'," ",$msg);
                                            @endphp
                                        </div>
                                    </td>
                                </tr>
                               
                            </table>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td style="text-align:center;">
                            <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong><a href="https://www.rakiro.net/">www.rakiro.net</a></strong> </p>
                        </td>
                    </tr> --}}
                </table>
            </td>
        </tr>
    </table>
    <!--/100% body table-->
</body>

</html>