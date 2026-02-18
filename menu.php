<?php
// Dostum Kafe - Menu Sayfasi

// Kategoriler ve urunler veritabanindan cekilir
require 'db.php'; // DB baglantisi

// --- GET parametrelerini al ---
if (isset($_GET['kategori'])) {
    $secili_kategori = (int) $_GET['kategori']; // Secili kategori ID
} else {
    $secili_kategori = 0; // Hicbir kategori secili degil
}

if (isset($_GET['urun'])) {
    $secili_urun = (int) $_GET['urun']; // Secili urun ID
} else {
    $secili_urun = 0;
}

// --- Kategorileri cek ---
$kategori_sorgu = mysqli_query($baglanti, "SELECT * FROM kategoriler ORDER BY sira ASC");

// --- Secili kategorinin urunlerini cek ---
if ($secili_kategori > 0) {
    $urun_sorgu = mysqli_query($baglanti, 
        "SELECT * FROM urunler WHERE kategori_id = $secili_kategori AND aktif = 1 ORDER BY id ASC"
    );
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dostum Kafe | Menü</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="menu-body">

    <!-- Logo -->
    <div class="text-center pt-4 pb-2">
        <a href="index.php">
            <img src="dostum_images/DOSTUMKAFE_NOBG_logo.png" alt="Dostum Kafe Logo" class="menu-logo">
        </a>
    </div>

    <div class="container pb-5" style="max-width: 480px;">

        <?php if ($secili_kategori == 0) : ?>
            <!-- === TUM KATEGORILER === -->

            <div class="kategori-grid mt-4">
                <?php while ($kat = mysqli_fetch_assoc($kategori_sorgu)) : ?>
                    <a href="menu.php?kategori=<?php echo $kat['id']; ?>" class="kategori-kart text-decoration-none">
                        <?php if (!empty($kat['gorsel']) && file_exists($kat['gorsel'])) : ?>
                            <img src="<?php echo $kat['gorsel']; ?>" alt="<?php echo $kat['isim']; ?>" class="kategori-img">
                        <?php else : ?>
                            <div class="kategori-placeholder">
                                <span class="kategori-ikon-buyuk"><?php echo $kat['ikon']; ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="kategori-overlay">
                            <span class="kategori-isim-text"><?php echo $kat['isim']; ?></span>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>

        <?php else : ?>
            <!-- === SECILI KATEGORI: URUNLER === -->

            <?php
            // Kategori adini cek
            $kat_isim_sorgu = mysqli_query($baglanti, "SELECT isim FROM kategoriler WHERE id = $secili_kategori");
            $kat_bilgi = mysqli_fetch_assoc($kat_isim_sorgu);

            // Gecersiz kategori kontrolu
            if (!$kat_bilgi) {
                header("Location: menu.php");
                exit;
            }
            ?>

            <!-- Kategori ust bar -->
            <div class="kategori-ust-bar">
                <span class="kategori-ust-baslik"><?php echo $kat_bilgi['isim']; ?></span>
                <a href="menu.php" class="kategori-ust-kapat text-decoration-none">✕</a>
            </div>

            <!-- Urun kartlari -->
            <div class="urun-kart-listesi">
                <?php if (mysqli_num_rows($urun_sorgu) > 0) : ?>
                    <?php while ($urun = mysqli_fetch_assoc($urun_sorgu)) : ?>
                        
                        <div class="urun-kart-item">
                            <?php if (!empty($urun['gorsel']) && file_exists($urun['gorsel'])) : ?>
                                <img src="<?php echo $urun['gorsel']; ?>" alt="<?php echo $urun['isim']; ?>" class="urun-kart-img">
                            <?php else : ?>
                                <div class="urun-kart-img-placeholder"></div>
                            <?php endif; ?>
                            
                            <div class="urun-kart-bilgi">
                                <h3 class="urun-kart-baslik"><?php echo $urun['isim']; ?></h3>
                                <p class="urun-kart-aciklama"><?php echo $urun['aciklama']; ?></p>
                                <p class="urun-kart-fiyat"><?php echo number_format($urun['fiyat'], 2); ?> ₺</p>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else : ?>
                    <p class="text-center text-muted mt-4">Bu kategoride henüz ürün yok.</p>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    </div>

</body>
</html>
<?php mysqli_close($baglanti); ?>
