<?php

$results = array(
    "Domino's Pizza|615 Caliente Dr|Sunnyvale|(408) 732-3030|4|http://local.yahoo.com/details?id=21335892&amp;stx=pizza&amp;csz=Sunnyvale+CA&amp;ed=17rS8q160Sy5Htog0TF1m1atNv01Iz7ySeGEMtCL4dJsf1ku0nhziD2qN5XcnHlJtDS4IydIbA--",
    "Pizza Depot|919 E Duane Ave|Sunnyvale|(408) 245-7760|3.5|http://local.yahoo.com/details?id=21332021&amp;stx=pizza&amp;csz=Sunnyvale+CA&amp;ed=6tiAL6160Sx1XVIEu1zIWPu6fD8rJDV4.offJLNUTb1Ri2Q.R5oLTYvDCz8YmzivI7Bz0gfrpw--",
    "Pizza Hut|464 N Mathilda Ave|Sunnyvale|(408) 735-1900|2.5|http://local.yahoo.com/details?id=21340811&amp;stx=pizza&amp;csz=Sunnyvale+CA&amp;ed=syVWvq160Szz0Q60Q8N7uetWGoUIbThLIdulmQLubJ29CuU7wpxDvDxrLF4md791a4jW7kNRr9eSVQ--",
    "Giovannis Pizzeria|1127 N Lawrence Expy|Sunnyvale|(408) 734-4221|4.5|http://local.yahoo.com/details?id=21341983&amp;stx=pizza&amp;csz=Sunnyvale+CA&amp;ed=kYc.Ba160SxZddWADEWWMRsGo0KgZ6X22_QAgTZxq3OdfrVCfCdLU9mvvJeybt8XpDhMC58HjElJAiWi",
    "Round Table Pizza|415 N Mary Ave|Sunnyvale|(408) 733-1365|5|http://local.yahoo.com/details?id=21329046&amp;stx=pizza&amp;csz=Sunnyvale+CA&amp;ed=OkhHFa160Sx2UXqpaqXDZAGyyKWrCO9wfqP24Mur1nNB2pqgQsi3DQxeSEK_Uj9fxQN4zNax",
    "Vitos Famous Pizza|1155 Reed Ave|Sunnyvale|(408) 246-8800|4.5|http://local.yahoo.com/details?id=21332026&amp;stx=pizza&amp;csz=Sunnyvale+CA&amp;ed=QTqeMK160Sx0Mril0Jnu_RK0RF4vTvEspLb2s60hJTic2.RapYE82B6edOm18LAox7KOqkw-",
    "Round Table Pizza|101 Town And Countr|Sunnyvale|(408) 736-2242|3|http://local.yahoo.com/details?id=21340803&amp;stx=pizza&amp;csz=Sunnyvale+CA&amp;ed=SiKr2K160SwJxDEvf_eAwROvFMpfCIqsVX3dSyYtvj6HomUPpdS92g9AIoaoZNtg.WNSGcT4hpk1JxxT",
    "Round Table Pizza|860 Old San Francisco Rd|Sunnyvale|(408) 245-9000|3|http://local.yahoo.com/details?id=21340791&amp;stx=pizza&amp;csz=Sunnyvale+CA&amp;ed=NF3MAq160SzKHt2S1yk7tJKtIMvbW44yNlckp8Y5veL7w8DWvagJYLH2tGehl1cPcLGbR4kzMTi4cf1U7iP6YA--",
    "Domino's Pizza|992 W El Camino Real|Sunnyvale|(408) 736-3666|4|http://local.yahoo.com/details?id=21341882&amp;stx=pizza&amp;csz=Sunnyvale+CA&amp;ed=_tzLZq160SyF.4GddMA07QiACQkYc84nqI0j3hvsAcfMemwlBCiezUltSm8_ppCH1Bo8SlklBj1QhuRp",
    "Little Caesars Pizza|1039 Sunnyvale Saratoga Rd|Sunnyvale|(408) 245-0607|3|http://local.yahoo.com/details?id=21330174&amp;stx=pizza&amp;csz=Sunnyvale+CA&amp;ed=89myuK160Syd6uoWQ5fTb6uLid70P.ucvPaBKA92m7bc1aVSW5LGmRbGsSIqT8U5e2eA4Ki4nQHVAAhh5.SVNIAQ"
);

header('Content-type: text/plain');
for ($i = 0; $i < count($results); $i++)
    print "$results[$i]\n";

?>
