<?php 
session_start();
require 'baglan.php';

$mesaj = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mail = $_POST['mail'];
    $sifre = $_POST['sifre'];
    $giris_turu = $_POST['giris_turu'];

    $stmt = $db->prepare("SELECT * FROM kullanici_giris WHERE mail = ? AND sifre = ?");
    $stmt->execute([$mail, $sifre]);
    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($kullanici) {
        if ($kullanici['supervisor'] == 1 && $giris_turu == 'admin') {
            $_SESSION['admin'] = $kullanici;
            $_SESSION['giris_turu'] = 'admin';
            header("Location: adminpanel.php?mesaj=basarili");
            exit;
        } elseif ($kullanici['onay'] == 1 && $giris_turu == 'calisan') {
            $_SESSION['calisan'] = $kullanici;
            $_SESSION['giris_turu'] = 'calisan';
            header("Location: arackayit.php?mesaj=basarili");
            exit;
        } else {
            $mesaj = 'Giriş türü veya yetki hatalı.';
        }
    } else {
        $mesaj = 'Hatalı e-mail ya da şifre girdiniz.';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Otopark Giriş</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f8f9fa;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 0;
            transform: translateY(-40px); /* Ekranda formu yukarı kaydırır */
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.9); /* Yarı saydam beyaz */
            padding: 35px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
        }

        .form-select, .form-control {
            border-radius: 10px;
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h3 class="text-center mb-4">Otopark Giriş</h3>

    <?php if (!empty($mesaj)): ?>
        <div class="alert alert-danger text-center">
            <?= $mesaj ?>
        </div>
    <?php elseif (isset($_GET['mesaj']) && $_GET['mesaj'] == 'cikis'): ?>
        <div class="alert alert-success text-center">Çıkış başarılı.</div>
    <?php elseif (isset($_GET['mesaj']) && $_GET['mesaj'] == 'basarili'): ?>
        <div class="alert alert-success text-center">Başarılı giriş!</div>
    <?php endif; ?>

    <?php
    if (isset($_GET['durum']) && $_GET['durum'] == 'cikis') {
        echo '
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <strong>Çıkış başarılı!</strong> Oturumunuz sonlandırıldı.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
        </div>';
    }
    ?>

    <form method="POST">
        <div class="mb-3">
            <label for="mail" class="form-label">E-Posta</label>
            <input type="email" name="mail" id="mail" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="sifre" class="form-label">Şifre</label>
            <input type="password" name="sifre" id="sifre" class="form-control" required>
        </div>
        <div class="mb-4">
            <label for="giris_turu" class="form-label">Giriş Türü</label>
            <select name="giris_turu" id="giris_turu" class="form-select" required>
                <option value="">Seçiniz</option>
                <option value="admin">Admin</option>
                <option value="calisan">Çalışan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
    </form>
</div>

</body>
</html>