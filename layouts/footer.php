    </div>
</div>

<footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
        <div class="row">
            <div class="col-sm my-1">
                <p class="m-0">Barbershop Booking &copy; <?= date('Y') ?></p>
            </div>
        </div>
    </div>
</footer>

<script src="<?= $assetPath ?>/js/plugins/popper.min.js"></script>
<script src="<?= $assetPath ?>/js/plugins/simplebar.min.js"></script>
<script src="<?= $assetPath ?>/js/plugins/bootstrap.min.js"></script>
<script src="<?= $assetPath ?>/js/fonts/custom-font.js"></script>
<script src="<?= $assetPath ?>/js/pcoded.js"></script>
<script src="<?= $assetPath ?>/js/plugins/feather.min.js"></script>
<?php if ($useApexChart ?? false) : ?>
    <script src="<?= $assetPath ?>/js/plugins/apexcharts.min.js"></script>
<?php endif; ?>
<?php if ($useDataTable ?? false) : ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="<?= $assetPath ?>/js/plugins/jquery.dataTables.min.js"></script>
    <script src="<?= $assetPath ?>/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="<?= $assetPath ?>/js/plugins/dataTables.responsive.min.js"></script>
    <script src="<?= $assetPath ?>/js/plugins/responsive.bootstrap5.min.js"></script>
    <script>
        $(function() {
            $('.datatable').each(function() {
                const noSortColumns = [];

                $(this).find('thead th').each(function(index) {
                    if ($(this).hasClass('no-sort')) {
                        noSortColumns.push(index);
                    }
                });

                $(this).DataTable({
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50],
                    responsive: true,
                    autoWidth: false,
                    order: [],
                    dom:
                        "<'row mb-3'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'f>>" +
                        "rt" +
                        "<'row mt-3 align-items-center'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4 text-center'i><'col-sm-12 col-md-4'p>>",
                    columnDefs: [
                        {
                            targets: noSortColumns,
                            orderable: false
                        }
                    ],
                    language: {
                        search: 'Cari:',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                        infoEmpty: 'Tidak ada data',
                        infoFiltered: '(difilter dari _MAX_ total data)',
                        zeroRecords: 'Data tidak ditemukan',
                        emptyTable: 'Belum ada data',
                        paginate: {
                            first: 'Pertama',
                            last: 'Terakhir',
                            next: 'Selanjutnya',
                            previous: 'Sebelumnya'
                        }
                    }
                });
            });
        });
    </script>
<?php endif; ?>
<?php if (isset($extraScripts)) : ?>
    <?= $extraScripts ?>
<?php endif; ?>
<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
</body>

</html>
