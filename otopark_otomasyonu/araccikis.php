<?php
session_start();
require 'baglan.php';

if (!isset($_SESSION['admin']) && !isset($_SESSION['calisan'])) {
    header("Location: giris.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: parkedenarac.php");
    exit;
}

$id = $_GET['id'];
$arac = $db->prepare("SELECT * FROM arac_kayit WHERE arac_id = ?");
$arac->execute([$id]);
$arac = $arac->fetch(PDO::FETCH_ASSOC);

if (!$arac) {
    echo "<div class='alert alert-danger text-center mt-4'>Kayıt bulunamadı.</div>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cikis_saat = $_POST['cikis_saat'];

    $tarih = date("Y-m-d");
    $tamsaat = $tarih . " " . $cikis_saat . ":00";

    $guncelle = $db->prepare("UPDATE arac_kayit SET arac_cikis_tarih = ? WHERE arac_id = ?");
    $guncelle->execute([$tamsaat, $id]);

    header("Location: parkedenarac.php?mesaj=cikis_kaydedildi");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Araç Çıkışı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f3f5;
            font-family: 'Roboto', sans-serif;
        }
        .box {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="box">
    <h4>Araç Çıkışı</h4>
    <hr>
    <p><strong>Ad Soyad:</strong> <?= htmlspecialchars($arac['adsoyad']) ?></p>
    <p><strong>Plaka:</strong> <?= htmlspecialchars($arac['arac_plaka']) ?></p>
    <p><strong>Giriş Saati:</strong> <?= htmlspecialchars($arac['arac_giris_tarih']) ?></p>

    <form method="POST">
        <div class="mb-3">
            <label>Çıkış Saati</label>
            <input type="time" name="cikis_saat" class="form-control" required>
        </div>

        <div class="text-end">
            <a href="parkedenarac.php" class="btn btn-secondary">İptal</a>
            <button type="submit" class="btn btn-success">Çıkışı Kaydet</button>
        </div>
    </form>
</div>

</body>
</html>
