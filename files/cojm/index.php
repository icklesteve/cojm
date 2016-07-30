<?php

/*
    COJM Courier Online Operations Management
	index.php - Core /cojm directory, currently does a secure redirect if not https but will be updated v soon in v 2.1 to become main login page.
    Copyright (C) 2016 S.Young cojm.co.uk

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/





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