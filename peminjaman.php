<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$userId = $_SESSION['user'];
?><!DOCTYPE html><html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container2">
        <h1 class="judulP">PEMINJAMAN</h1><h2>Daftar Buku</h2>
    <ul>
        <?php
        $query = "SELECT * FROM daftar_buku WHERE stok > 0";
        $result = mysqli_query($koneksi, $query);

        while ($book = mysqli_fetch_assoc($result)) {
            echo "<li>
                    <strong>{$book['judul']}</strong><br>
                    <small>Stok: {$book['stok']}</small><br>
                    <small>Kategori: {$book['kategori_buku']}</small>
                    <form method='POST' style='display:inline; margin-left: 10px;'>
                        <input type='hidden' name='book_id' value='{$book['id_buku']}'>
                        <button type='submit' name='borrow'>Pinjam</button>
                    </form>
                  </li><br>";
        }
        ?>
    </ul>

    <h2>Daftar Buku yang Dipinjam</h2>
    <ul>
        <?php
        $queryId = mysqli_query($koneksi, "SELECT id FROM users WHERE name = '$userId'");
        $dataId = mysqli_fetch_assoc($queryId);
        $uid = $dataId['id'];

        $query = "SELECT br.id_buku, db.judul
                  FROM borrowings br
                  JOIN daftar_buku db ON br.id_buku = db.id_buku
                  WHERE br.id_user = $uid AND br.status = 'dipinjam'";
        $result = mysqli_query($koneksi, $query);

        while ($borrowed = mysqli_fetch_assoc($result)) {
            echo "<li>
                    {$borrowed['judul']}
                    <form method='POST' style='display:inline; margin-left:10px;'>
                        <input type='hidden' name='book_id' value='{$borrowed['id_buku']}'>
                        <button type='submit' name='return'>Kembalikan</button>
                    </form>
                  </li>";
        }
        ?>
    </ul>

    <a href="riwayat.php"><button>Riwayat Peminjaman</button></a>

    <form method="POST" action="./logout.php">
        <button type="submit" name="logout">Keluar</button>
    </form>
</div>

<?php
if (isset($_POST['borrow'])) {
    $bookId = $_POST['book_id'];

    $cekStok = mysqli_query($koneksi, "SELECT stok FROM daftar_buku WHERE id_buku = '$bookId'");
    $dataStok = mysqli_fetch_assoc($cekStok);

    if (isset($dataStok['stok']) && $dataStok['stok'] > 0) {
        $queryId = mysqli_query($koneksi, "SELECT id FROM users WHERE name = '$userId'");
        $dataId = mysqli_fetch_assoc($queryId);
        $uid = $dataId['id'];

        $query = "INSERT INTO borrowings (id_user, id_buku, tanggal_pinjam, status) 
                  VALUES ('$uid', '$bookId', NOW(), 'dipinjam')";
        mysqli_query($koneksi, $query);

        $query = "UPDATE daftar_buku SET stok = stok - 1 WHERE id_buku = '$bookId'";
        mysqli_query($koneksi, $query);

        echo "<script>alert('Buku berhasil dipinjam!'); window.location.href = 'peminjaman.php';</script>";
    } else {
        echo "<script>alert('Buku tidak tersedia!');</script>";
    }
}

if (isset($_POST['return'])) {
    $bookId = $_POST['book_id'];

    $queryId = mysqli_query($koneksi, "SELECT id FROM users WHERE name = '$userId'");
    $dataId = mysqli_fetch_assoc($queryId);
    $uid = $dataId['id'];

    mysqli_query($koneksi, "UPDATE borrowings SET status = 'dikembalikan', tanggal_kembali = NOW() 
                            WHERE id_user = '$uid' AND id_buku = '$bookId' AND status = 'dipinjam' LIMIT 1");

    mysqli_query($koneksi, "UPDATE daftar_buku SET stok = stok + 1 WHERE id_buku = '$bookId'");

    echo "<script>alert('Buku berhasil dikembalikan!'); window.location.href = 'peminjaman.php';</script>";
}
?>

</body>
</html>