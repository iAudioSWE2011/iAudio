<?php

echo '
    <div id="navi">
      <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/Player">Player</a></li>
        <li><a href="/Index?navi=Verwaltung">Verwaltung</a></li>';

if($_SESSION["navi"] == "Verwaltung")
	echo '
      <ul id="naviklein">
        <li><a href="/Music">Musik</a></li>
        <li><a href="/Playlist">Playlisten</a></li>
      </ul>';


echo '
        <li><a href="/Settings">Einstellungen</a></li>
        <li><a href="/Logout">Logout</a></li>
        <li><a href="/Impressum">Impressum</a></li>
      </ul>
    
    </div>';

?>
