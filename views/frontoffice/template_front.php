<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Espace Client</title>
    <link rel="stylesheet" type="text/css" href="views/css/style.css">
</head>
<body style="margin:0; padding:0; background-color:#FFFFFF; font-family:Arial, sans-serif;">

    <div id="header-container" style="border-bottom: 3px solid #1D9E75; padding: 20px; overflow: hidden;">
        
        <div id="header-right" style="float: right; margin-top: 12px;">
            <a href="index.php?module=projet&action=liste" style="background-color: #1D9E75; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                ⚙️ Connexion Admin
            </a>
        </div>

        <div id="header-left" style="float: left;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding-right: 15px;">
                        <img src="public/images/logo.jpeg" alt="Logo" style="width: 65px; height: auto; display: block;">
                    </td>
                    <td>
                        <h2 style="margin:0; color: #1D9E75; font-size: 24px; vertical-align: middle;">🚀 Nos Projets</h2>
                    </td>
                </tr>
            </table>
        </div>

    </div>

    <div id="main-content" style="padding: 20px; clear: both;">
        <?php echo $contenu; ?>
    </div>

</body>
</html>