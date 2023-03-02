<?php

function email_boton($tipo, $url){
    if($tipo == 'Activación de cuenta'){
        $boton = '¡Activar mi cuenta!';
    }
    elseif($tipo == 'Reinicio de contraseña'){
        $boton = '¡Reiniciar mi contraseña!';
    }
    
    return ('<tr bgcolor="#D8D8D8">
            <td align="center" colspan="2">
                <a href="'.$url.'" style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#009972;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;width:auto; width:auto;;border-top:1px solid #66C2AA;border-right:1px solid #66C2AA;border-bottom:1px solid #66C2AA;border-left:1px solid #66C2AA;padding-top:5px;padding-bottom:5px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">'.$boton.'</span></span></a><br><br>
            </td>
        </tr>

        <!-- linea separadora boton -->
        <tr style="vertical-align: top;" valign="top" bgcolor="#D8D8D8">
            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top" colspan="2">
                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #D6D3D3; width: 100%;" valign="top" width="100%">
                    <tbody>
                        <tr style="vertical-align: top;" valign="top">
                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <!-- no funciona el boton -->
        <tr bgcolor="BDBDBC">
            <td align="center" colspan="2">
                <br><span style="font-size: 18px;"><strong>¿No funciona el botón?</strong></span><br>
                <br><p style="font-size: 14px; line-height: 1.8; text-align: center; word-break: break-word; mso-line-height-alt: 25px; margin: 0;">Copia y pega este enlace en tu navegador.</p>
                <p style="font-size: 14px; line-height: 1.8;  text-align: center; word-break: break-word; mso-line-height-alt: 25px; margin: 0;"><u>'.$url.'</u></p><br>
            </td>
        </tr>');
}

?>