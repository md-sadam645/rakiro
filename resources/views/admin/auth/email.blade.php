<!DOCTYPE>
<html >
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <style>
 
      html,
      body,
      a,
      span,
      div[style*="margin: 16px 0"] {
        border: 0 !important;
        margin: 0 !important;
        outline: 0 !important;
        padding: 0 !important;
        text-decoration: none !important;
      }
      a,
      span,
      td,
      th {
        -webkit-font-smoothing: antialiased !important;
        -moz-osx-font-smoothing: grayscale !important;
      }
 
      span.st-Delink a {
        color: #525f7f !important;
        text-decoration: none !important;
      }
      /** Modifier: preheader */
      span.st-Delink.st-Delink--preheader a {
        color: white !important;
        text-decoration: none !important;
      }
      /** */
      /** Modifier: title */
      span.st-Delink.st-Delink--title a {
        color: #32325d !important;
        text-decoration: none !important;
      }
      /** */
      /** Modifier: footer */
      span.st-Delink.st-Delink--footer a {
        color: #8898aa !important;
        text-decoration: none !important;
      }
      /** */
      /**
  * # Header
**/
      table.st-Header td.st-Header-background div.st-Header-area {
        height: 76px !important;
        width: 600px !important;
        background-repeat: no-repeat !important;
        background-size: 600px 76px !important;
      }
      table.st-Header td.st-Header-logo div.st-Header-area {
        height: 21px !important;
        width: 49px !important;
        background-repeat: no-repeat !important;
        background-size: 49px 21px !important;
      }
      table.st-Header td.st-Header-logo.st-Header-logo--atlasAzlo div.st-Header-area {
        height: 21px !important;
        width: 216px !important;
        background-repeat: no-repeat !important;
        background-size: 216px 21px !important;
      }

**/
      @media (-webkit-min-device-pixel-ratio: 1.25),
      (min-resolution: 120dpi),
      all and (max-width: 768px) {
     
        /** Modifier: mobile */
        body[override] div.st-Target.st-Target--mobile img {
          display: none !important;
          margin: 0 !important;
          max-height: 0 !important;
          min-height: 0 !important;
          mso-hide: all !important;
          padding: 0 !important;
          font-size: 0 !important;
          line-height: 0 !important;
        }
      
        /* body[override] table.st-Header td.st-Header-background div.st-Header-area {
          background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-background.png') !important;
        }
        /** Modifier: white */
        /* body[override] table.st-Header.st-Header--white td.st-Header-background div.st-Header-area {
          background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-background--white.png') !important;
        } */
        /** */
        /** Modifier: simplified */
        /* body[override] table.st-Header.st-Header--simplified td.st-Header-logo div.st-Header-area {
          background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-logo.png') !important;
        } */
        /** */
        /** Modifier: simplified + atlasAzlo */
        /* body[override] table.st-Header.st-Header--simplified td.st-Header-logo.st-Header-logo--atlasAzlo div.st-Header-area {
          background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2018-05-02/header/Header-logo--atlasAzlo.png') !important;
        } */ 
        /** */
      }
      /**
  * # Mobile
  * - This affects emails views in clients less than 600px wide.
**/
      @media all and (max-width: 600px) {
        /**
    * # Wrapper
  **/
        body[override] table.st-Wrapper,
        body[override] table.st-Width.st-Width--mobile {
          min-width: 100% !important;
          width: 100% !important;
        }
        /**
    * # Spacer
  **/
        /** Modifier: gutter */
        body[override] td.st-Spacer.st-Spacer--gutter {
          width: 32px !important;
        }
        /** */
        /** Modifier: kill */
        body[override] td.st-Spacer.st-Spacer--kill {
          width: 0 !important;
        }
        /** */
        /** Modifier: emailEnd */
        body[override] td.st-Spacer.st-Spacer--emailEnd {
          height: 32px !important;
        }
        /** */
        /**
    * # Font
  **/
        /** Modifier: title */
        body[override] td.st-Font.st-Font--title,
        body[override] td.st-Font.st-Font--title span,
        body[override] td.st-Font.st-Font--title a {
          font-size: 28px !important;
          line-height: 36px !important;
        }
        /** */
        /** Modifier: header */
        body[override] td.st-Font.st-Font--header,
        body[override] td.st-Font.st-Font--header span,
        body[override] td.st-Font.st-Font--header a {
          font-size: 24px !important;
          line-height: 32px !important;
        }
        /** */
        /** Modifier: body */
        body[override] td.st-Font.st-Font--body,
        body[override] td.st-Font.st-Font--body span,
        body[override] td.st-Font.st-Font--body a {
          font-size: 18px !important;
          line-height: 28px !important;
        }
        /** */
        /** Modifier: caption */
        body[override] td.st-Font.st-Font--caption,
        body[override] td.st-Font.st-Font--caption span,
        body[override] td.st-Font.st-Font--caption a {
          font-size: 14px !important;
          line-height: 20px !important;
        }
        /** */
        /**
    * # Header
  **/
        body[override] table.st-Header td.st-Header-background div.st-Header-area {
          margin: 0 auto !important;
          width: auto !important;
          background-position: 0 0 !important;
        }
        /* body[override] table.st-Header td.st-Header-background div.st-Header-area {
          background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-background--mobile.png') !important;
        } */
        /** Modifier: white */
        /* body[override] table.st-Header.st-Header--white td.st-Header-background div.st-Header-area {
          background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-background--white--mobile.png') !important;
        } */
        /** */
        /** Modifier: simplified */
        body[override] table.st-Header.st-Header--simplified td.st-Header-logo {
          width: auto !important;
        }
        body[override] table.st-Header.st-Header--simplified td.st-Header-spacing {
          width: 0 !important;
        }
        /* body[override] table.st-Header.st-Header--simplified td.st-Header-logo div.st-Header-area {
          margin: 0 auto !important;
          background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-logo.png') !important;
        } */
        /* body[override] table.st-Header.st-Header--simplified td.st-Header-logo.st-Header-logo--atlasAzlo div.st-Header-area {
          margin: 0 auto !important;
          background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2018-05-02/header/Header-logo--atlasAzlo.png') !important;
        } */
        /** */
        /**
    * # Divider
  **/
        body[override] table.st-Divider td.st-Spacer.st-Spacer--gutter,
        body[override] tr.st-Divider td.st-Spacer.st-Spacer--gutter {
          background-color: #e6ebf1;
        }
        /**
    * # Blocks
  **/
        body[override] table.st-Blocks table.st-Blocks-inner {
          border-radius: 0 !important;
        }
        body[override] table.st-Blocks table.st-Blocks-inner table.st-Blocks-item td.st-Blocks-item-cell {
          display: block !important;
        }
        /**
    * # Button
  **/
        body[override] table.st-Button {
          margin: 0 auto !important;
          width: 100% !important;
        }
        body[override] table.st-Button td.st-Button-area,
        body[override] table.st-Button td.st-Button-area a.st-Button-link,
        body[override] table.st-Button td.st-Button-area span.st-Button-internal {
          height: 44px !important;
          line-height: 44px !important;
          font-size: 18px !important;
          vertical-align: middle !important;
        }
      }
      @media (-webkit-min-device-pixel-ratio: 1.25),
      (min-resolution: 120dpi),
      all and (max-width: 768px) {
        /**
    * # mobile image
   **/
        body[override] div.st-Target.st-Target--mobile img {
          display: none !important;
          margin: 0 !important;
          max-height: 0 !important;
          min-height: 0 !important;
          mso-hide: all !important;
          padding: 0 !important;
          font-size: 0 !important;
          line-height: 0 !important;
        }
        /**
    * # document-list-item image
   **/
        /* body[override] div.st-Icon.st-Icon--document {
          background-image: url('https://stripe-images.s3.amazonaws.com/notifications/icons/document--16--regular.png') !important;
        } */
      }
    </style>
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
  </head>
  <body class="st-Email" bgcolor="f6f9fc" style="border: 0; margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; min-width: 100%; width: 100%;" override="fix">

    <!-- Background -->
    <table class="st-Background" bgcolor="f6f9fc" border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 0; margin: 0; padding: 0;">
      <tbody>
        <tr>
          <td style="border: 0; margin: 0; padding: 0;">

            <!-- Wrapper -->
            <table class="st-Wrapper" align="center" bgcolor="ffffff" border="0" cellpadding="0" cellspacing="0" width="600" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; margin: 0 auto; min-width: 600px;">
              <tbody>
                <tr>
                  <td style="border: 0; margin: 0; padding: 0;">
                    <table class="st-Header st-Header--simplified st-Width st-Width--mobile" border="0" cellpadding="0" cellspacing="0" width="600" style="min-width: 600px;">
                      <tbody>
                        <tr>
                          <td class="st-Spacer st-Spacer--divider" colspan="4" height="19" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr style="border:1px solid green;">
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          <td class="st-Header-logo" align="left" height="21" width="100" style="margin: 0; padding: 0;">
                            <center>
                            <div class="st-Header-area st-Target st-Target--mobile">
                              
                              <a style="border: 0; margin: 0; padding: 0; text-decoration: none;font-weight:bold;font-size:25px;" href="#">
                                {{-- <img border="0" class="st-Header-source" height="21" width="49" style="border: 0; margin: 0; padding: 0; color: #6772e5; display: block; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif; font-size: 12px; font-weight: normal;" src="{{url('/assest/images/ada_new_logo2.png')}}"> --}}
                                Rakiro
                              </a>
                              
                            </div>
                          </center>
                          </td>
                          <td class="st-Header-spacing" width="423" style="border: 0; margin: 0; padding: 0;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--divider" colspan="4" height="19" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr class="st-Divider">
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          <td bgcolor="#e6ebf1" colspan="2" height="1" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--divider" colspan="4" height="32" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="st-Preheader st-Width st-Width--mobile" border="0" cellpadding="0" cellspacing="0" width="600" style="min-width: 600px;">
                      <tbody>
                        <tr>
                          <td align="center" height="0" style="border: 0; margin: 0; padding: 0; color: #ffffff; display: none !important; font-size: 1px; line-height: 1px; max-height: 0; max-width: 0; mso-hide: all !important; opacity: 0; overflow: hidden; visibility: hidden;">
                            <span class="st-Delink st-Delink--preheader" style="color: #ffffff; text-decoration: none;">
                                

          <!-- Prevents elements showing up in email client preheader text -->
    
          <!-- /Prevents elements showing up in email client preheader text -->
        </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="st-Copy st-Width st-Width--mobile" border="0" cellpadding="0" cellspacing="0" width="600" style="min-width: 600px;">
                      <tbody>
                        <tr>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          {{-- <td class="st-Font st-Font--body" style="border: 0; margin: 0; padding: 0; color: #525F7f !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif; font-size: 16px; line-height: 24px;">
                            Hello,
                          </td> --}}
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--stacked" colspan="3" height="12" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="st-Copy st-Width st-Width--mobile" border="0" cellpadding="0" cellspacing="0" width="600" style="min-width: 600px;">
                      <tbody>
                        <tr>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          <td class="st-Font st-Font--body" style="border: 0; margin: 0; padding: 0; color: #525F7f !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif; font-size: 16px; line-height: 24px;">
                            @php
                                echo str_replace('"'," ",$msg);
                            @endphp
                          </td>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--stacked" colspan="3" height="12" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="st-Copy st-Width st-Width--mobile" border="0" cellpadding="0" cellspacing="0" width="600" style="min-width: 600px;">
                      <tbody>
                        <tr>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          {{-- <td class="st-Font st-Font--body" style="border: 0; margin: 0; padding: 0; color: #525F7f !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif; font-size: 16px; line-height: 24px;">
                            To reset your password, click on the button bellow :
                          </td> --}}
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--stacked" colspan="3" height="12" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="st-Copy st-Width st-Width--mobile" border="0" cellpadding="0" cellspacing="0" width="600" style="min-width: 600px;">
                      <tbody>
                        <tr>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          <td class="st-Font st-Font--body" style="border: 0; margin: 0; padding: 0; color: #525F7f; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif; font-size: 16px; line-height: 24px;">
                            
                            <!-- Button & Modifier: fullWidth -->
                            <table class="st-Button st-Button--fullWidth" border="0" cellpadding="0" cellspacing="0" width="100%">
                              <tbody>
                                <tr>
                                  <td align="center" class="st-Button-area" height="38" valign="middle" style="border: 0; margin: 0; padding: 0; background-color: #666ee8; border-radius: 5px; text-align: center;">
                                    <a class="st-Button-link" style="border: 0; margin: 0; padding: 0; color: #ffffff; display: block; height: 38px; text-align: center; text-decoration: none;"  @if(!empty($attachment)) href="{{url($attachment)}}" @endif>
                                      <span class="st-Button-internal" style="border: 0; margin: 0; padding: 0; color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif; font-size: 16px; font-weight: bold; height: 38px; line-height: 38px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: middle; white-space: nowrap; width: 100%;">
                                        Click<span style="border: 0; margin: 0; padding: 0; color: #666ee8; font-size: 12px; text-decoration: none;">‑</span>Here<span style="border: 0; margin: 0; padding: 0; color: #666ee8; font-size: 12px; text-decoration: none;">‑</span>To View Attachment </span>
                                    </a>
                                  </td>
                                </tr>
                              </tbody>
                            </table> <!-- /Button & Modifier: fullWidth -->
                          </td>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--stacked" colspan="3" height="12" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="st-Copy st-Width st-Width--mobile" border="0" cellpadding="0" cellspacing="0" width="600" style="min-width: 600px;">
                      <tbody>
                        <tr>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          {{-- <td class="st-Font st-Font--body" style="border: 0; margin: 0; padding: 0; color: #525F7f !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif; font-size: 16px; line-height: 24px;">
                            or copy and paste the URL into your browser :
                          </td> --}}
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--stacked" colspan="3" height="12" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="st-Copy st-Width st-Width--mobile" border="0" cellpadding="0" cellspacing="0" width="600" style="min-width: 600px;">
                      <tbody>
                        <tr>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          <td class="st-Font st-Font--body" style="border: 0; margin: 0; padding: 0; color: #525F7f !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif; font-size: 16px; line-height: 24px;">
                             {{-- <a style="border: 0; margin: 0; padding: 0; color: #556cd6 !important; text-decoration: none;" href="{{ 'http://127.0.0.1:8000/reset-password/'}}{{$token }}">{{ "http://127.0.0.1:8000/reset-password/"}}{{$token }}</a> --}}
                          </td>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--stacked" colspan="3" height="12" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="st-Copy st-Width st-Width--mobile" border="0" cellpadding="0" cellspacing="0" width="600" style="min-width: 600px;">
                      <tbody>
                        <tr>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          {{-- <td class="st-Font st-Font--body" style="border: 0; margin: 0; padding: 0; color: #525F7f !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif; font-size: 16px; line-height: 24px;">
                            
                          </td> --}}
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--stacked" colspan="3" height="12" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="st-Footer st-Width st-Width--mobile" border="0" cellpadding="0" cellspacing="0" width="600" style="min-width: 600px;">
                      <tbody>
                        <tr>
                          <td class="st-Spacer st-Spacer--divider" colspan="3" height="20" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr class="st-Divider">
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          <td bgcolor="#e6ebf1" colspan="2" height="1" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--divider" colspan="3" height="31" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                          <td class="st-Font st-Font--caption" style="border: 0; margin: 0; padding: 0; color: #8898aa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Ubuntu, sans-serif; font-size: 12px; line-height: 16px;">
                           
                          </td>
                          <td class="st-Spacer st-Spacer--gutter" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width="64">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="st-Spacer st-Spacer--emailEnd" colspan="3" height="64" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
                            <div class="st-Spacer st-Spacer--filler"> </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table> <!-- /Wrapper -->
          </td>
        </tr>
        <tr>
          <td class="st-Spacer st-Spacer--emailEnd" height="64" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
            <div class="st-Spacer st-Spacer--filler">&nbsp;</div>
          </td>
        </tr>
      </tbody>
    </table> <!-- /Background -->
  </body>
</html>