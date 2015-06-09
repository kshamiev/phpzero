<?php
if ( !isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != 'pi' || $_SERVER['PHP_AUTH_PW'] != 'pi' || true )
{
    header('WWW-Authenticate: Basic realm="Auth"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Auth Failed';
    exit;
}
phpinfo();
