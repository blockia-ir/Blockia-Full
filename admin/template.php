<?php require_once "blockPageLoader.php"; ?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <link rel="icon" type="image/png" href="../images/iconExam2.png" />
        <link rel="stylesheet" href="../lib/fonts/font.css"  />
        <link rel="stylesheet" href="../lib/font-awesome/css/fontawesome-all.min.css" >
        <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap-rtl.min.css" />
        <link rel="stylesheet" href="../css/panel.css" />
        <script src="../lib/js/jquery.min.js"></script>
        <script src="../lib/js/function.js"></script>
    </head>
    <body class="rtl" >
        <?php require_once "menu.php"?>
        <div class="container-fluid">
            <?=$Msg->show();?>
            
        </div>
        <?php require_once "footer.php"?>
    <body>
</html>
