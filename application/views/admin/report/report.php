<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $title; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <style type="text/css">
        @page {
            size: A4;
            margin: 30px 50px;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
        }

        .section-title {
            font-size: 18px;
            margin: 20px 0 10px;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .footer .signature {
            text-align: center;
            width: 45%;
        }

        .footer .signature p {
            margin: 0;
            font-size: 14px;
        }

        .footer .signature .signature-line {
            border-top: 1px solid #000;
            margin-top: 80px;
            padding-top: 5px;
        }

        .footer .signature .date-place {
            margin-top: 6px;
            font-size: 14px;
        }

        .footer .signature .date-place p {
            margin: 0;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1><?php echo isset($title) ? $title : 'Default Title'; ?></h1>
        <p>Pengampu: <?php echo isset($pengampu) && !empty($pengampu) ? $pengampu->guru : 'Semua Pengampu'; ?></p>
        <p>Kelas: <?php echo isset($kode_kelas) && !empty($kode_kelas) ? $kode_kelas->kode_kelas : 'Semua Kelas'; ?> |
            Mata Pelajaran: <?php echo isset($mapel) && !empty($mapel) ? $mapel->name : 'Semua Mapel'; ?></p>
        <p>Periode:
            <?php echo isset($tanggal_start) && !empty($tanggal_start) ? $tanggal_start : 'Semua Data Sesudah'; ?>
            sampai <?php echo isset($tanggal_end) && !empty($tanggal_end) ? $tanggal_end : 'Semua Data Sebelum'; ?></p>
    </div>

    <?php if (!empty($attendance_data)): ?>
        <?php foreach ($attendance_data as $pengampu_name => $records): ?>
            <div class="section-title"><?php echo $pengampu_name; ?></div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo $record->nama_siswa; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($record->date_click_status)); ?></td>
                            <td><?php echo $record->kode_kelas_kode; ?></td>
                            <td><?php echo $record->mapel_name; ?></td>
                            <td><?php echo $record->status; ?></td>
                            <td><?php echo $record->keterangan; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No data available for the selected filters.</p>
    <?php endif; ?>

    <div class="footer">
        <div class="signature" style="float: left; width: 45%;">
            <br>
            <p>Tata Usaha:</p>
            <div class="signature-line"></div>
            <p>Name</p>
        </div>
        <div class="signature" style="float: right; width: 45%;">
            <div class="date-place">
                <p>_______, _______________</p>
            </div>
            <p>Kepala Sekolah:</p>
            <div class="signature-line"></div>
            <p>Name</p>
        </div>
    </div>

</body>

</html>