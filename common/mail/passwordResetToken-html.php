<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user box\entities\user\User */
/* @var $subject */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://w=
    ww.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <title>Confirmation code</title>
    <style>
        /* CLIENT-SPECIFIC STYLES */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        .hidden {
            display: none !important;
            visibility: hidden !important;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* ANDROID MARGIN HACK */
        body { margin:0 !important; }
        div[style*="margin: 16px 0"] { margin:0 !important; }

        @media only screen and (max-width: 639px) {
            body, #body {
                min-width: 320px !important;
            }
            table.wrapper {
                width: 100% !important;
                min-width: 320px !important;
            }
            table.wrapper > tbody > tr > td {
                border-left: 0 !important;
                border-right: 0 !important;
                border-radius: 0 !important;
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
        }
    </style>
    <style>body {
            -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;
        }
        img {
            -ms-interpolation-mode: bicubic;
        }
        body {
            margin: 0 !important;
        }
    </style></head>
<body style="text-align: center; min-width: 640px; width: 100%; height:100%; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; margin: 0; padding: 0;" bgcolor="#fafafa">
<table border="0" cellpadding="0" cellspacing="0" id="body" style="text-align: center; min-width: 640px; width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 0; padding: 0;" bgcolor="#fafafa">
    <tbody>
    <tr class="line">
        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; height: 4px; font-size: 4px; line-height: 4px; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" bgcolor="#6b4fbb"></td>
    </tr>
    <tr class="header">
        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 13px; line-height: 1.6; color: #5c5c5c; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 25px 0;">
            <div alt="Watch Vault" src="http://static.watchvaultapp.com/empty/icon.png" width="80" height="80"
                 style="-ms-interpolation-mode: bicubic;  background-repeat: no-repeat; background: url('http://static.watchvaultapp.com/empty/icon.png'); background-position: center;background-size: 80px 80px; margin: 0 auto;"/>
        </td>
    </tr>
    <tr>
        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
            <table border="0" cellpadding="0" cellspacing="0" class="wrapper" style="width: 640px; border-collapse: separate; border-spacing: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin: 0 auto;">
                <tbody>
                <tr>
                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; border-radius: 3px; overflow: hidden; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 18px 25px; border: 1px solid #ededed;" align="left" bgcolor="#ffffff">
                        <table border="0" cellpadding="0" cellspacing="0" class="content" style="width: 100%; border-collapse: separate; border-spacing: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                            <tr>
                                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; color: #333333; font-size: 15px; font-weight: 400; line-height: 1.4; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 15px 5px;" align="center">
                                    <div id="content">
                                        <h1 style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; color: #333333; font-size: 18px; font-weight: 400; line-height: 1.4; margin: 0; padding: 0;" align="center">You have requested to change your Watch Vault password.</h1>
                                        <p>Here is your confirmation code.</p>
                                        <div id="cta" style="font-weight: 500; font-size: 18px;">
                                            <?= Html::encode($user->password_reset_token) ?>
                                        </div>
                                        <p>If you believe you received this mail by mistake, please ignore it or <a href="http://watchvault.org/contact.html" style="color: #3777b0; text-decoration: none; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">contact us</a>.</p>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr class="footer">
        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 13px; line-height: 1.6; color: #5c5c5c; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 25px 0;">
            <!--<img alt="Watch Vault" height="30" src="http://static.watchvaultapp.com/empty/icon.png" style="display: block; -ms-interpolation-mode: bicubic; margin: 0 auto 1em;" width="30" />-->
            <div>
                You received this email because of your account on <a style="color: #3777b0; text-decoration: none; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" href="http://watchvault.org">watchvault.org</a>. <a style="color: #3777b0; text-decoration: none; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" href="http://watchvault.org">Manage all notifications.</a>
            </div>
        </td>
    </tr>
    <tr>
        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 13px; line-height: 1.6; color: #5c5c5c; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
            <div>
                Watch Vault on social media.
            </div>
            <div>
                <a style="color: #3777b0; text-decoration: none; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" href="https://www.instagram.com/Watch__Vault/">Instagram</a>
                <a style="color: #3777b0; text-decoration: none; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" href="https://twitter.com/Watch__Vault">Twitter</a>
            </div>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>

