<?php
use SLiMS\DB;

if (isset($_POST['simpan'])) {
    $statement = DB::getInstance()->prepare(<<<SQL
    insert into `usul_buku`
        set 
            `identitas` = ?,
            `nama` = ?,
            `alamat` = ?,
            `judul` = ?,
            `pengarang` = ?,
            `penerbit` = ?,
            `tahunterbit` = ?,
            `created_at` = now()
    SQL);
    
    $statement->execute([
        $_POST['identitas'],
        $_POST['nama'],
        $_POST['alamat'],
        $_POST['judul'],
        $_POST['pengarang'],
        $_POST['penerbit'],
        $_POST['tahunterbit'],
    ]);
}
?>
<form action="<?= SWB ?>?p=usul_buku" method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">NIM/NIK/No Identitas</label>
    <input type="text" name="identitas" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Nama</label>
    <input type="text" name="nama" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Alamat</label>
    <input type="text" name="alamat" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Judul Buku</label>
    <input type="text" name="judul" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    <small id="emailHelp" class="form-text text-muted">Judul buku yang hendak di usulkan</small>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Pengarang Buku</label>
    <input type="text" name="pengarang" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    <small id="emailHelp" class="form-text text-muted">Nama pengarang dipisahkan dengan tanda koma</small>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Penerbit</label>
    <input type="text" name="penerbit" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    <small id="emailHelp" class="form-text text-muted">Nama Penerbit Buku</small>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Tahun Terbit</label>
    <input type="text" name="tahunterbit" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <button type="submit" name="simpan" class="btn btn-primary">Submit</button>
</form>