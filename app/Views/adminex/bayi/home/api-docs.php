<?php
    /**
     * Created by PhpStorm.
     * User: Cihan
     * Date: 22.06.2018
     * Time: 10:36
     */
?>
    <div class="well api well-float">
        <div class="center-big-content-block">
            <h2 class="m-b-md">API Documentation</h2>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td class="width-40">HTTP Method</td>
                    <td>POST</td>
                </tr>
                <tr>
                    <td>API URL</td>
                    <td>http<?php echo Wow::get("project/onlyHttps") ? 's' : ''; ?>://<?php echo $_SERVER['SERVER_NAME'];
                            echo empty($baseUrl) ? '' : $baseUrl; ?>/api/v2
                    </td>
                </tr>
                <tr>
                    <td>Response format</td>
                    <td>JSON</td>
                </tr>
                </tbody>
            </table>
            <hr/>
            <h4 class="m-t-md"><strong>Service List</strong></h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="width-40">Parameter</th>
                    <th>Explanation</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>key</td>
                    <td>API key</td>
                </tr>
                <tr>
                    <td>action</td>
                    <td>services</td>
                </tr>
                </tbody>
            </table>

            <p><strong>Sample Return</strong></p>
            <pre>[
   {
        "service": "1",
        "name": "Instagram Followers",
        "type": "Default",
        "category": "Followers",
        "rate": "0.40",
        "min": "1",
        "max": 10000
    },
    {
        "service": "2",
        "name": "Instagram Like",
        "type": "Default",
        "category": "Like",
        "rate": "0.09",
        "min": "1",
        "max": 10000
    }
]
</pre>
            <hr/>
            <h4 class="m-t-md"><strong>Adding order</strong></h4>
            <p>
            </p>
            <form class="form-inline">
                <div class="form-group">
                    <select class="form-control input-sm" id="service_type">
                        <option value="0">Default</option>
                        <option value="2">Custom Comments</option>
                        <option value="100">Subscriptions</option>
                    </select>
                </div>
            </form>
            <p></p>
            <div id="type_0" style="display: none;">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="width-40">Parameters</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>key</td>
                        <td>API key</td>
                    </tr>
                    <tr>
                        <td>action</td>
                        <td>add</td>
                    </tr>
                    <tr>
                        <td>service</td>
                        <td>Service ID</td>
                    </tr>
                    <tr>
                        <td>link</td>
                        <td>Link of the page</td>
                    </tr>
                    <tr>
                        <td>quantity</td>
                        <td>How many?</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div id="type_2" style="display: none;">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="width-40">Parameter</th>
                        <th>Explanation</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>key</td>
                        <td>API key</td>
                    </tr>
                    <tr>
                        <td>action</td>
                        <td>add</td>
                    </tr>
                    <tr>
                        <td>service</td>
                        <td>Servis ID</td>
                    </tr>
                    <tr>
                        <td>link</td>
                        <td>Link of the page</td>
                    </tr>
                    <tr>
                        <td>comments</td>
                        <td>Send comments separated by \n.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div id="type_100" style="display: block;">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="width-40">Parameter</th>
                        <th>Açıklama</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>key</td>
                        <td>API key</td>
                    </tr>
                    <tr>
                        <td>action</td>
                        <td>add</td>
                    </tr>
                    <tr>
                        <td>service</td>
                        <td>Servis ID</td>
                    </tr>
                    <tr>
                        <td>username</td>
                        <td>Username</td>
                    </tr>
                    <tr>
                        <td>min</td>
                        <td>Min. jumlah</td>
                    </tr>
                    <tr>
                        <td>max</td>
                        <td>Max. jumlah</td>
                    </tr>
                    <tr>
                        <td>runs</td>
                        <td>How many submissions will it make at once.</td>
                    </tr>
                    <tr>
                        <td>delay</td>
                        <td>It will run every few minutes.</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <p><strong>Sample Return</strong></p>
            <pre>{
    "order": 23501
}
</pre>
            <hr/>
            <h4 class="m-t-md"><strong>Order status</strong></h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="width-40">Parameter</th>
                    <th>Explanation</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>key</td>
                    <td>API key</td>
                </tr>
                <tr>
                    <td>action</td>
                    <td>status</td>
                </tr>
                <tr>
                    <td>order</td>
                    <td>Order ID</td>
                </tr>
                </tbody>
            </table>

            <p><strong>Sample Return</strong></p>
            <pre>{
    "charge": "0.27819",
    "start_count": "3572",
    "status": "Partial",
    "remains": "157",
    "currency": "IDR"
}
</pre>
            <hr/>
            <h4 class="m-t-md"><strong>Order status</strong></h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="width-40">Parameter</th>
                    <th>Explanation</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>key</td>
                    <td>API key</td>
                </tr>
                <tr>
                    <td>action</td>
                    <td>status</td>
                </tr>
                <tr>
                    <td>orders</td>
                    <td>Send the order IDs separated by commas.</td>
                </tr>
                </tbody>
            </table>

            <p><strong>Sample Return</strong></p>
            <pre>{
    "1": {
        "charge": "0.27819",
        "start_count": "3572",
        "status": "Partial",
        "remains": "157",
        "currency": "IDR"
    },
    "100": {
        "charge": "1.44219",
        "start_count": "234",
        "status": "In progress",
        "remains": "10",
        "currency": "IDR"
    }
}
</pre>
            <hr/>
            <h4 class="m-t-md"><strong>User Balance</strong></h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="width-40">Parameter</th>
                    <th>Explanation</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>key</td>
                    <td>API key</td>
                </tr>
                <tr>
                    <td>action</td>
                    <td>balance</td>
                </tr>
                </tbody>
            </table>

            <p><strong>Sample Return</strong></p>
            <pre>{
    "balance": "100.492",
    "currency": "IDR"
}
</pre>
        </div>
    </div>


<?php $this->section("section_scripts");
    $this->parent(); ?>
    <script>
        $("#service_type").length || $('div[id^="type_"]').show(), $("#service_type").change(function() {
            $("div[id^='type_']").hide();
            var e = $("#service_type").val();
            $("#type_" + e).show()
        }), $("#service_type").trigger("change");
    </script>
<?php $this->endSection(); ?>