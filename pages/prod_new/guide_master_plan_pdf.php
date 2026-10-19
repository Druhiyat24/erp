<?php
// sama seperti conn.php: notice/warning yang tercetak akan merusak file PDF
error_reporting(0);
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); exit; }
include 'guide_master_plan_isi.php';
include '../../mpdf57/mpdf.php';

// CSS sederhana: mPDF 5.7 tidak mendukung flex/grid
$css = '
  body { font-family: sans-serif; font-size: 10pt; color: #1f2937; line-height: 1.45; }
  h1 { font-size: 22pt; margin: 0 0 4pt 0; color: #111827; }
  h2 { font-size: 15pt; color: #0e7490; border-bottom: 1px solid #cbd5e1; padding-bottom: 3pt; margin: 14pt 0 6pt 0; }
  h3 { font-size: 11.5pt; margin: 10pt 0 4pt 0; color: #111827; }
  p { margin: 0 0 6pt 0; }
  a { color: #0e7490; text-decoration: none; }
  .toc { background: #f8fafc; border: 1px solid #e5e7eb; padding: 8pt 14pt; margin-bottom: 8pt; }
  .toc-title { font-weight: bold; }
  .toc ol { margin: 2pt 0 0 0; }
  .figure { text-align: center; margin: 6pt 0 10pt 0; }
  .figure img { width: 100%; border: 1px solid #cbd5e1; }
  .caption { font-size: 8.5pt; color: #6b7280; font-style: italic; margin-top: 3pt; }
  table.tbl { width: 100%; border-collapse: collapse; margin: 4pt 0 10pt 0; font-size: 9pt; }
  table.tbl th { background-color: #f1f5f9; border: 1px solid #cbd5e1; padding: 4pt 6pt; text-align: left; }
  table.tbl td { border: 1px solid #cbd5e1; padding: 4pt 6pt; vertical-align: top; }
  td.c { text-align: center; }
  .num { background-color: #e11d48; color: #ffffff; font-weight: bold; font-size: 8pt; padding: 1pt 4pt; }
  .red { color: #dc2626; font-weight: bold; }
  .note { background-color: #ecfeff; border-left: 3pt solid #0e7490; padding: 6pt 9pt; margin: 6pt 0; }
  .warn { background-color: #fff7ed; border-left: 3pt solid #ea580c; padding: 6pt 9pt; margin: 6pt 0; }
  .formula { background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 6pt 9pt; margin: 6pt 0; }
  .formula-title { font-weight: bold; color: #166534; }
  .formula-main { font-size: 12pt; font-weight: bold; margin: 2pt 0; }
  .cover { border-bottom: 2pt solid #0e7490; padding-bottom: 6pt; margin-bottom: 10pt; }
  .cover p { color: #6b7280; }
';

$mpdf = new mPDF('utf-8', 'A4', 10, '', 14, 14, 16, 18, 6, 8);
$mpdf->SetTitle('Panduan Master Plan');
$mpdf->SetAuthor('PT. Nirwana Alabare Garment');
$mpdf->SetHTMLFooter('<table width="100%" style="font-size:8pt;color:#6b7280;border-top:1px solid #e5e7eb;"><tr>
  <td>Panduan Master Plan &middot; Modul Produksi</td><td align="right">Halaman {PAGENO} dari {nbpg}</td></tr></table>');
$mpdf->WriteHTML($css, 1);
$mpdf->WriteHTML('<div class="cover"><h1>Panduan Penggunaan Menu Master Plan</h1><p>Modul Produksi &middot; Versi 1.0 &middot; Oktober 2026</p></div>'
  . guide_master_plan_isi(__DIR__ . '/guide/img/', true), 2);
$mpdf->Output('Panduan_Master_Plan.pdf', 'D');
