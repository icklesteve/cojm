<?php
error_reporting( E_ERROR | E_WARNING | E_PARSE );
include "live/C4uconnect.php";
 if ($globalprefrow['forcehttps']>0) {
 if ($serversecure=='') {

echo '

<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="1;url='.$globalprefrow['httproots'].'/cojm/live/">
        <script type="text/javascript">
            window.location.href = "'.$globalprefrow['httproots'].'/cojm/live/"
        </script>
        <title>Page Redirection</title>
    </head>
    <body>
        If you are not redirected automatically, follow the <a href="'.$globalprefrow['httproots'].'/cojm/live/">link</a>
    </body>
</html>
';

//  header('Location: '.$globalprefrow['httproots'].'/cojm/live/'); exit(); 

 } else { header('Location: '.$globalprefrow['httproot'].'/cojm/live/'); exit(); }
  }
?>