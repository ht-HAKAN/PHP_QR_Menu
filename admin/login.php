<?php
session_start();

// Zaten giris yapilmissa panele yonlendir
if (isset($_SESSION['admin_giris'])) {
    header("Location: panel.php");
    exit;
}

// Form gonderildi mi kontrolu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../db.php'; // Ust klasordeki db.php
    
    $kullanici = mysqli_real_escape_string($baglanti, $_POST['kullanici_adi']);
    $sifre = $_POST['sifre'];
    
    // Veritabanindan admin bilgisini cek
    $sorgu = mysqli_query($baglanti, "SELECT * FROM admin_kullanicilar WHERE kullanici_adi = '$kullanici'");
    
    if (mysqli_num_rows($sorgu) == 1) {
        $admin = mysqli_fetch_assoc($sorgu);
        
        // Sifre kontrol
        if (password_verify($sifre, $admin['sifre'])) {
            // Giris basarili
            $_SESSION['admin_giris'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_kullanici'] = $admin['kullanici_adi'];
            header("Location: panel.php");
            exit;
        } else {
            $hata = "Kullanıcı adı veya şifre hatalı!";
        }
    } else {
        $hata = "Kullanıcı adı veya şifre hatalı!";
    }
    
    mysqli_close($baglanti);
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Giriş | Dostum Kafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="login-body">

    <!-- Ana container -->
    <div class="login-container">
        <!-- Sol - Logo -->
        <div class="logo-section">
            <img src="../dostum_images/DOSTUMKAFE_NOBG_logo.png" alt="Dostum Kafe Logo">
        </div>

        <!-- Sag - Form -->
        <div class="form-section">
            <div class="form-card">
                <h2>Admin Girişi</h2>
                <p>Yönetim paneline hoş geldiniz</p>

                <?php if (isset($hata)) : ?>
                    <div class="alert"><?php echo $hata; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <input type="text" 
                               name="kullanici_adi" 
                               class="form-control" 
                               placeholder="Kullanıcı Adı" 
                               required 
                               autocomplete="username">
                    </div>
                    <div class="mb-4">
                        <input type="password" 
                               name="sifre" 
                               class="form-control" 
                               placeholder="Şifre" 
                               required 
                               autocomplete="current-password">
                    </div>
                    <button type="submit" class="btn-login">Giriş Yap</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
