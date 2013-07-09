<?php
if(get_magic_quotes_gpc())
        var_dump("Magic quotes are enabled");
    else
        echo var_dump("Magic quotes are disabled");

phpinfo();
?>
