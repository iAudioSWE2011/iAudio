<?php

echo '
    <div id="navi">
      <ul>
        <li><a href="">Home</a></li>
        <li><a href="">Player</a></li>
        <li><a href="">Verwaltung</a></li>';

if($_SESSION["navi"] == "Verwaltung")
	echo '
      <ul id="naviklein">
        <li><a href="">Musik</a></li>
        <li><a href="">Playlisten</a></li>
      </ul>';


echo '
        <li><a href="/Settings">Einstellungen</a></li>
        <li><a href="/Logout">Logout</a></li>
        <li><a href="/Impressum">Impressum</a></li>
      </ul>
    
    </div>';

?>
