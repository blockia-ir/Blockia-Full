<?php

$uploadPath=strstr($_SERVER['PHP_SELF'],"tinymce",true)."source";
	$folderToDay=date("Ymd");
	if (!file_exists("$uploadPath/$folderToDay")) {
		mkdir("$uploadPath/$folderToDay", 0755, true);
	}
mkdir($_SERVER['DOCUMENT_ROOT']."$uploadPath/$folderToDay", 0755, true);

file_put_contents("temp.txt","$uploadPath/$folderToDay");
echo $_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'];
echo "<br/>";
echo $_SERVER['PATH_INFO'];
echo "<br/>";
echo $_SERVER['HTTP_HOST'];
echo "<br/>";
echo $_SERVER['SERVER_ADDR'];
echo "<br/>";
echo $_SERVER['HTTP_USER_AGENT'];
echo "<br/>";
echo $_SERVER['REMOTE_PORT'];
echo "<br/>";

$indicesServer = array('PHP_SELF',
'argv',
'argc',
'GATEWAY_INTERFACE',
'SERVER_ADDR',
'SERVER_NAME',
'SERVER_SOFTWARE',
'SERVER_PROTOCOL',
'REQUEST_METHOD',
'REQUEST_TIME',
'REQUEST_TIME_FLOAT',
'QUERY_STRING',
'DOCUMENT_ROOT',
'HTTP_ACCEPT',
'HTTP_ACCEPT_CHARSET',
'HTTP_ACCEPT_ENCODING',
'HTTP_ACCEPT_LANGUAGE',
'HTTP_CONNECTION',
'HTTP_HOST',
'HTTP_REFERER',
'HTTP_USER_AGENT',
'HTTPS',
'REMOTE_ADDR',
'REMOTE_HOST',
'REMOTE_PORT',
'REMOTE_USER',
'REDIRECT_REMOTE_USER',
'SCRIPT_FILENAME',
'SERVER_ADMIN',
'SERVER_PORT',
'SERVER_SIGNATURE',
'PATH_TRANSLATED',
'SCRIPT_NAME',
'REQUEST_URI',
'PHP_AUTH_DIGEST',
'PHP_AUTH_USER',
'PHP_AUTH_PW',
'AUTH_TYPE',
'PATH_INFO',
'ORIG_PATH_INFO') ;

echo '<table cellpadding="10">' ;
foreach ($indicesServer as $arg) {
    if (isset($_SERVER[$arg])) {
        echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ;
    }
    else {
        echo '<tr><td>'.$arg.'</td><td>-</td></tr>' ;
    }
}
echo '</table>' ;