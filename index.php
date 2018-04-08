<?php
include("config.php"); //załadowanie konfiguracji skryptu
include("function.php"); //załadowanie klasy functions
$get_offer = new functions; //utworzenie instancji obiektu na bazie klasy functions

//while(){
$link = $get_offer->get_content($address,$initial_sequence,$final_sequence); //pobranie linku do oferty
echo $link; //wyświetlenie linku 



$address = $get_offer->get_address($link); //pobranie adresu oferty z linku
$tresc = $get_offer->get_content($address,$initial_sequence_offer_olx,$final_sequence_offer_olx); //załadowanie treści oferty do zmiennej
echo $tresc;//wyświetlenie treści oferty
//}
?>