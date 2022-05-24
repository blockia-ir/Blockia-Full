<?php
// $h=date("H")+0;
// $m=date("i")+0;
$m=$Jdf->jdate('i');
$h=$Jdf->jdate('H');
if((($m>=56 || $m<=8) && $h>=7) && false):?>
<!DOCTYPE html>
<html>
<head>
    <meta name="theme-color" content="#F7F7F7" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>خطای شلوغی</title>
</head>
<body>
    
    <center>
        <h2> به دلیل ترافیک  خیلی زیاد پنل سایت 10 دقیقه بسته خواهد بود </h2>
        <h2> این کار باعث می شود آزمون های شما با مشکلات کمتری برگزار بشوند </h2>
    </center>
    
</body>
</html>
<?php exit;endif ?>
