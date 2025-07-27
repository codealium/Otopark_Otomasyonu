<?php
require_once 'C:\wamp64\www\proje\otopark_otomasyonu\admin\tcpdf/tcpdf.php';

require 'baglan.php';

$arac_id = $_GET['id'];

$park_suresi_dakika = $_GET['park_suresi_dakika'];
$ucret = $_GET['ucret'];

$query = $db->prepare("SELECT * FROM arac_kayit WHERE arac_id = ?");
$query->execute([$arac_id]);
$arac = $query->fetch(PDO::FETCH_ASSOC);

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Araç Ücreti PDF');
$pdf->SetSubject('Araç Ücreti');
$pdf->SetKeywords('Araç, Ücret, PDF');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage();

// Türkçe karakterleri destekleyen bir font yüklüyoruz
$pdf->SetFont('dejavusans', '', 12);

$html = '
<h2>Müşteri ve Araç Bilgisi</h2>
<p><strong>Ad Soyad: </strong>'.$arac['adsoyad'].'</p>
<p><strong>Telefon: </strong>'.$arac['telefon'].'</p>
<p><strong>Plaka: </strong>'.$arac['arac_plaka'].'</p>
<p><strong>Giriş Tarihi: </strong>'.$arac['arac_giris_tarih'].'</p>
<p><strong>Çıkış Tarihi: </strong>'.$arac['arac_cikis_tarih'].'</p>

<!-- Tablo arası boşluk -->
<div style="height: 20px;"></div>

<h2>Ücret Bilgisi</h2>
<p><strong>Park Süresi: </strong>'.$park_suresi_dakika.' dakika</p>
<p><strong>Ücret: </strong>'.$ucret.' TL</p>
';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('arac_ucreti.pdf', 'I'); 
?>