<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
    $this->set("title", "Export Wizard");
?>
    <div class="card">
        <div class="card-header">
            EXPORT WIZARD
        </div>
        <div class="card-body">
            <p>This tool allows you to export the number and gender of user data you requested as a single file. </p>
           
            <p>In data export, only active users who are not in the pool are referenced. </p>
            <hr/>
            <form method="post" id="formExportWizard">
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control" onchange="doWork()">
                        <option value="0">All</option>
                        <option value="1">Only men</option>
                        <option value="2">Only women</option>
                        <option value="3">Only Gender Unknown</option>
                    </select>
                </div>
                <hr/>
                <div id="AdetContainerDiv"></div>
                <hr/>
                <div class="mb-3">
                    <label class="form-label">Number of Data to be Exported</label>
                    <input type="number" value="" name="adet" class="form-control">
                    <span class="form-text">You can leave blank for all detected or write how many you want to transfer.</span>
                </div>
                <p>
                    <button type="submit" class="btn btn-primary">Export</button>
                </p>
            </form>
        </div>
    </div>

<?php $this->section("section_scripts");
    $this->parent(); ?>
    <script type="text/javascript">
        function doWork() {
            $('#AdetContainerDiv').html('<p><i class="fa fa-spinner fa-spin"></i> Exporting..</p>');
            $.ajax({type: 'POST', dataType: 'json', url: '?formType=sorgu', data: $('#formExportWizard').serialize()}).done(function(data) {
                $('#AdetContainerDiv').html('<p><strong>The number of determined data in accordance with the criteria: </strong> ' + data.adet + '</p>');
            });
        }
        doWork();
    </script>
<?php $this->endSection(); ?>