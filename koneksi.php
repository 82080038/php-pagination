<?php
   // Start Konfigurasi Koneksi Database

   $dbname     = 'db_artikel';
   $dbusername = 'root';
   $dbpassword = '';

   // End Konfigurasi Koneksi Database


   $dbh = new PDO("mysql:host=localhost;dbname=$dbname", $dbusername, $dbpassword);
   
?>