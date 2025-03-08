<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h4 class="title">Config</h4>
                <hr>

<div class="container mt-5">
    <h2>Configure Longitude and Latitude</h2>

    <!-- Alerts for success/error -->
    <div id="alert-container"></div>

    <form id="configForm">
        <div class="form-group">
            <label for="longitude">Longitude:</label>
            <input type="text" name="longitude" id="longitude" value="<?= isset($longitude) ? $longitude : '' ?>" class="form-control" placeholder="Enter longitude">
        </div>

        <div class="form-group">
            <label for="latitude">Latitude:</label>
            <input type="text" name="latitude" id="latitude" value="<?= isset($latitude) ? $latitude : '' ?>" class="form-control" placeholder="Enter latitude">
        </div>

        <button type="submit" id="save-btn" class="btn btn-primary">Save Settings</button>
    </form>
</div>
            </div>
        </div>
    </div>
</div>

<script data-main="<?php echo base_url() ?>assets/js/main/main-config"
    src="<?php echo base_url() ?>assets/js/require.js"></script>