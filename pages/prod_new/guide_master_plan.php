<?php
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); exit; }
include 'guide_master_plan_isi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panduan Master Plan</title>
  <link rel="icon" type="image/jpeg" href="../../include/icon2.jpg">
  <link rel="stylesheet" href="../../fontawesome/css/font-awesome.css">
  <style>
    :root { --ink:#1f2937; --muted:#6b7280; --line:#e5e7eb; --soft:#f8fafc; --accent:#0e7490; --accent-soft:#ecfeff; --mark:#e11d48; }
    * { box-sizing:border-box; }
    body { margin:0; background:#eef1f5; color:var(--ink); font:15px/1.65 "Segoe UI", Arial, sans-serif; }
    .topbar { position:sticky; top:0; z-index:10; background:#111827; color:#fff; box-shadow:0 2px 8px rgba(0,0,0,.2); }
    .topbar .wrap { display:flex; align-items:center; gap:12px; padding:12px 24px; }
    .topbar .judul { font-weight:700; font-size:16px; }
    .topbar .judul small { display:block; font-weight:400; font-size:12px; color:#9ca3af; }
    .topbar .aksi { margin-left:auto; display:flex; gap:8px; }
    .btn { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:6px; font-size:13px; font-weight:600;
      text-decoration:none; border:1px solid transparent; cursor:pointer; }
    .btn-pdf { background:#dc2626; color:#fff; }
    .btn-pdf:hover { background:#b91c1c; }
    .btn-ghost { background:transparent; color:#e5e7eb; border-color:#4b5563; }
    .btn-ghost:hover { background:#1f2937; }
    .wrap { max-width:1040px; margin:0 auto; }
    main.wrap { background:#fff; margin:24px auto 48px; padding:32px 44px 44px; border-radius:10px; box-shadow:0 1px 3px rgba(0,0,0,.06); }
    .cover h1 { margin:0 0 4px; font-size:28px; }
    .cover p { margin:0; color:var(--muted); }
    .cover { border-bottom:3px solid var(--accent); padding-bottom:16px; margin-bottom:24px; }
    h2 { font-size:21px; margin:40px 0 12px; padding-bottom:6px; border-bottom:1px solid var(--line); scroll-margin-top:80px; }
    h3 { font-size:16px; margin:26px 0 8px; color:#111827; }
    a { color:var(--accent); }
    .toc { background:var(--soft); border:1px solid var(--line); border-radius:8px; padding:14px 22px; }
    .toc-title { font-weight:700; margin-bottom:4px; }
    .toc ol { margin:0; padding-left:20px; columns:2; }
    .figure { margin:16px 0 20px; text-align:center; }
    .figure img { max-width:100%; border:1px solid #d1d5db; border-radius:6px; box-shadow:0 2px 10px rgba(15,23,42,.08); }
    .caption { font-size:13px; color:var(--muted); margin-top:6px; font-style:italic; }
    table.tbl { width:100%; border-collapse:collapse; margin:12px 0 18px; font-size:14px; }
    table.tbl th { background:#f1f5f9; text-align:left; padding:8px 10px; border:1px solid var(--line); font-size:13px; }
    table.tbl td { padding:7px 10px; border:1px solid var(--line); vertical-align:top; }
    table.tbl td.c { text-align:center; }
    .num { display:inline-block; min-width:22px; height:22px; line-height:22px; border-radius:11px; background:var(--mark); color:#fff;
      font-weight:700; font-size:12px; text-align:center; padding:0 4px; }
    .red { color:#dc2626; font-weight:700; }
    .note, .warn, .formula { border-radius:8px; padding:12px 16px; margin:14px 0; }
    .note { background:var(--accent-soft); border-left:4px solid var(--accent); }
    .warn { background:#fff7ed; border-left:4px solid #ea580c; }
    .formula { background:#f0fdf4; border:1px solid #bbf7d0; }
    .formula-title { font-weight:700; color:#166534; }
    .formula-main { font-size:17px; font-weight:700; margin:4px 0 6px; }
    @media (max-width:720px) { main.wrap { padding:20px; margin:12px; } .toc ol { columns:1; } .topbar .judul small { display:none; } }
  </style>
</head>
<body>
  <div class="topbar">
    <div class="wrap">
      <div class="judul">Panduan Master Plan<small>Modul Produksi &middot; untuk user baru</small></div>
      <div class="aksi">
        <a class="btn btn-pdf" href="guide_master_plan_pdf.php"><i class="fa fa-file-pdf-o"></i> Download PDF</a>
        <a class="btn btn-ghost" href="../prod_new/?mod=master_plan_h"><i class="fa fa-arrow-left"></i> Kembali</a>
      </div>
    </div>
  </div>
  <main class="wrap">
    <div class="cover">
      <h1>Panduan Penggunaan Menu Master Plan</h1>
      <p>Modul Produksi &middot; Versi 1.0 &middot; Oktober 2026</p>
    </div>
    <?php echo guide_master_plan_isi('guide/img/'); ?>
  </main>
</body>
</html>
