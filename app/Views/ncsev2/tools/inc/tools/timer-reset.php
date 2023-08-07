<script type="text/javascript">
    function timer() {
        //get the mins of the current time


        var countDownDate = new Date().getTime();

        var hour = new Date().getHours();
        var mins = new Date().getMinutes();
        var secs = new Date().getSeconds();

        var jam = countDownDate - hour;
        var menit = countDownDate - mins;
        var detik = countDownDate - secs;


        // Perhitungan waktu untuk hari, jam, menit dan detik
        var hours = Math.floor((jam % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((menit % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((detik % (1000 * 60)) / 1000);

        var menitmundur = <?php echo Wow::get("ayar/waktuReset"); ?> - minutes;
        var detikmundur = 60 - seconds;

        document.getElementById("menit").innerHTML = menitmundur;
        document.getElementById("detik").innerHTML = detikmundur;
    }

    setInterval(function() {
        timer();
    }, 1000);
</script>

<!-- Modal -->
<div class="modal fade" id="modal-timer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><i class="fas fa-info"></i> Ketentuan Reset Poin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Website ini gratis tidak ada biaya apapun untuk mendapatkan poin, jika poin kamu habis maka tunggu poin
                kembali
                di reset.<br />
                <div class="mt-3">
                    <span class="fw-bold">Berikut ketentuan poin reset [latest].:</span>
                    <ul>
                        <li>Poin di reset tiap <?php echo Wow::get("ayar/waktuReset") / 60; ?> Jam</li>
                        <li>Jam reset yaitu WIB. Contohnya, jam 12.00, <?php echo 12 + (Wow::get("ayar/waktuReset") / 60); ?>.00, dan seterusnya.</li>
                    </ul>
                </div>

                <span class="fw-bold d-block">Contoh:</span>
                Jika kamu menggunakan tools auto followers di jam 14.02 dan poinnya sudah habis, maka poin akan kembali
                ada di jam <?php echo 14 + (Wow::get("ayar/waktuReset") / 60); ?>.00.

                <span class="fw-bold d-block mt-3 text-danger">Catatan:</span>
                Poin tidak diakumulasi, jadi jika tidak menggunakannya tiap <?php echo Wow::get("ayar/waktuReset") / 60; ?> Jam poin kamu tidak akan bertambah dan akan
                tetap sesuai standarnya.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Saya Mengerti.</button>
            </div>
        </div>
    </div>
</div>