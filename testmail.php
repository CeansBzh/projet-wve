<?php

    ini_set( 'display_errors', 1 );

    error_reporting( E_ALL );

    $from = "ceans@voyages.com";

    $to = "ebriantais@orange.fr";

    $subject = "Vérification PHP mail";

    $message = "PHP mail marche";

    $headers = "From:" . $from;

    mail($to,$subject,$message, $headers);

    echo "L'email a été envoyé.";
?>
