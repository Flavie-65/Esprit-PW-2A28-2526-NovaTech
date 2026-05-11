<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Gestion Tâches - Administration</title>
    <link rel="stylesheet" type="text/css" href="views/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="margin:0; padding:0; background-color:#FFFFFF; font-family:Arial, sans-serif;">

    <div id="admin-wrapper" style="display: flex; min-height: 100vh; width: 100%;">
        
        <div id="sidebar" style="width: 250px; background-color: #1D9E75; color: white; position: fixed; height: 100%; z-index: 100; box-shadow: 2px 0 5px rgba(0,0,0,0.05);">
            
            <div id="sidebar-logo" style="padding: 30px 20px; text-align: center;">
                <img src="public/images/logo.jpeg" alt="SmartSun Logo" style="width: 150px; height: auto; border-radius: 8px; background-color: rgba(255,255,255,0.9); padding: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            </div>

            <div id="sidebar-menu" style="margin-top: 20px;">
                
                <div class="menu-item" style="margin-bottom: 5px;">
                    <a href="index.php?module=projet&action=liste" 
                       style="display: block; padding: 15px 25px; color: white; text-decoration: none; font-weight: bold; border-left: 5px solid <?php echo ($module == 'projet' ? '#FFFFFF' : 'transparent'); ?>; background-color: <?php echo ($module == 'projet' ? 'rgba(255,255,255,0.15)' : 'transparent'); ?>;">
                       <i class="fas fa-folder" style="margin-right: 15px; width: 20px;"></i> Projets
                    </a>
                </div>

                <div class="menu-item" style="margin-bottom: 5px;">
                    <a href="index.php?module=tache&action=liste" 
                       style="display: block; padding: 15px 25px; color: white; text-decoration: none; font-weight: bold; border-left: 5px solid <?php echo ($module == 'tache' ? '#FFFFFF' : 'transparent'); ?>; background-color: <?php echo ($module == 'tache' ? 'rgba(255,255,255,0.15)' : 'transparent'); ?>;">
                       <i class="fas fa-tasks" style="margin-right: 15px; width: 20px;"></i> Tâches
                    </a>
                </div>

            </div>

            <div id="sidebar-footer" style="position: absolute; bottom: 20px; width: 100%; text-align: center;">
                <a href="index.php?module=projet&action=liste_public" style="color: #FFFFFF; font-size: 13px; text-decoration: none; background: rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 4px; display: inline-block;">
                    <i class="fas fa-eye"></i> Voir le site
                </a>
            </div>
        </div>

        <div id="main-content" style="margin-left: 250px; width: calc(100% - 250px); display: flex; flex-direction: column;">
            
            <div id="top-bar" style="background-color: #FFFFFF; padding: 25px 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-bottom: 1px solid #EAEAEA;">
                <div style="float: right; color: #888; font-size: 14px; padding-top: 5px;">
                    Connecté en tant qu'<strong>Administrateur</strong>
                </div>
                <h2 style="margin:0; color: #1D9E75; font-size: 20px; text-transform: uppercase; font-weight: bold; border: none; padding: 0;">Administration</h2>
            </div>

            <div id="dynamic-content" style="padding: 40px;">
                <?php echo $contenu; ?>
            </div>

        </div>
    </div>

    <script type="text/javascript" src="views/js/validation_projet.js"></script>
</body>
</html>