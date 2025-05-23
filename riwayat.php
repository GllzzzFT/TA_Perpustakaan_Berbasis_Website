<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$userId = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container2">
        <h1>RIWAYAT PEMINJAMAN</h1>
        <table>
            <tr>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
            <?php
            $queryId = mysqli_query($koneksi, "SELECT id FROM users WHERE name = '$userId'");
            $dataId = mysqli_fetch_assoc($queryId);
            $uid = $dataId['id'];

            $query = "SELECT db.judul, br.tanggal_pinjam, br.tanggal_kembali, br.status
                      FROM borrowings br
                      JOIN daftar_buku db ON br.id_buku = db.id_buku
                      WHERE br.id_user = '$uid'
                      ORDER BY br.tanggal_pinjam DESC";
            $result = mysqli_query($koneksi, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $statusClass = $row['status'] === 'dikembalikan' ? 'status-returned' : 'status-borrowed';
                $statusIcon = $row['status'] === 'dikembalikan' ? '✓' : '⏳';
                echo "<tr>
                        <td>{$row['judul']}</td>
                        <td>{$row['tanggal_pinjam']}</td>
                        <td>" . ($row['tanggal_kembali'] ?? '-') . "</td>
                        <td class='{$statusClass}'>{$statusIcon} {$row['status']}</td>
                      </tr>";
            }
            ?>
        </table>

        <form action="peminjaman.php" method="get">
            <button type="submit">Kembali ke Peminjaman</button>
        </form>
    </div>
</body>
</html>