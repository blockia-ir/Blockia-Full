<?php require_once "blockPageLoader.php";
if(!admin)
{
    exit("error: you can not access this page");
}
?>
<!DOCTYPE html>
<html lang="fa">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>لیست ورود ها</title>
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
            <div id="search"  >
            <input type="text" id="id" data-type="text" class="form-control searchInput" placeholder="block_user_id"  />
            <input type="text" id="username" data-type="text" class="form-control searchInput" placeholder="username"  />
            <button type="button" id="btnSetFilter" class="btn btn-primary" >جستجو</button>
            </div>
            <script>
                var searchData="";
                 $("#btnSetFilter").click(function(){
                    searchData="";
                    $(".searchInput").each(function(){
                      if($(this).attr("id")!==undefined)
                      {
                        searchData+='"'+$(this).attr("id")+'":{"value":"'+$(this).val()+'","dataType":"'+$(this).attr("data-type")+'"},';
                      }
                    });
                    searchData=searchData.replace(/,\s*$/, "");
                    searchData="{"+searchData+"}";
                    $("#searchData").html(searchData);
                    $.post("listLoginsGrid.php",{searchData:searchData},function(data)
                        {
                          $("#listLoginsGrid").html(data);
                        }
                      );
                 });
            </script>
            <?php require_once "listLoginsGrid.php"; ?>
        </div>
        <?php require_once "footer.php"?>
    <body>
</html>
