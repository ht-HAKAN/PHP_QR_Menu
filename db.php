<?php
// Veritabani baglanti dosyasi 

$sunucu   = "127.0.0.1";   // XAMPP yerel sunucu
$kullanici = "root";        // Varsayilan XAMPP kullanici
$sifre     = "";            // XAMPP'te varsayilan sifre bos
$veritabani = "dostum_kafe"; // Bizim veritabanimiz

// Baglantiyi kur
$baglanti = mysqli_connect($sunucu, $kullanici, $sifre, $veritabani);

// Baglanti hatasi kontrolu
if (!$baglanti) {
    die("Veritabani baglanti hatasi: " . mysqli_connect_error());
}

// Turkce karakter destegi
mysqli_set_charset($baglanti, "utf8mb4");
?>

