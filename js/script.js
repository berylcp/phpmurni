$(document).ready(function () {
  // Hilangkan tombol cari
  $("#tombol-cari").hide();

  // Buat event ketika keyword ditulis
  $("#keyword").on("keyup", function () {
    // Munculkan icon loading
    $(".loader").show();

    $.get("ajax/mahasiswa.php?keyword=" + $("#keyword").val(), function (data) {
      $("#container").html(data);
      $(".loader").hide();
    });
    // ajax menggunakan load
    // $("#container").load("ajax/mahasiswa.php?keyword=" + $("#keyword").val());
  });
});
