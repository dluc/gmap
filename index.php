<?php

// get your key at https://code.google.com/apis/console
$googleAPIKey = "AIzaSyAKAgj2Hww8CMGu5ALnDs_a1cd2v6jXhCk";

$points = array();
if (isset($_POST['points'])) {
    $input = trim($_POST['points']);
    // force "," CSV separator
    $input = str_replace(';', ',', $input);
    $rows = explode("\n", $input);
    foreach($rows as $row) {
        $cols = explode(',', $row);
        if (count($cols) == 2) {
            $lat = trim($cols[0]);
            $long = trim($cols[1]);
            if (! is_numeric($lat) || ! is_numeric($long)) continue;
            $points[] = array((float) $lat, (float) $long);
        }
    }
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB" lang="en-GB">
<head>
<meta charset="utf-8" />
<title>Show points on Google Map using Latitude/Longitude coordinates</title>
<script
    src="//maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $googleAPIKey ?>"
    type="text/javascript"></script>
<script type="text/javascript">

    function initialize() {
        if (GBrowserIsCompatible()) {
            var map = new GMap2(document.getElementById("map_canvas"));
            map.setCenter(new GLatLng(0, 0), 2);
            map.setUIToDefault();
    
<?php if (count($points)): ?>
            // Create our "tiny" marker icon
            var blueIcon = new GIcon(G_DEFAULT_ICON);
            blueIcon.image = "http://gmaps-samples.googlecode.com/svn/trunk/markers/red/blank.png";
    
            // Set up our GMarkerOptions object
            markerOptions = { icon:blueIcon };
            var points = [];
    
    <?php foreach($points as $point): ?>
            var latlng = new GLatLng(<?php echo $point[0]?>, <?php echo $point[1]?>);
            map.addOverlay(new GMarker(latlng, markerOptions));
            points.push(latlng);
    <?php endforeach ?>
    
            var polyline = new GPolyline(points, "#3300FF", 5, 1);
            map.addOverlay(polyline);
<?php endif ?>
        }
    }

</script>
</head>
<body onload="initialize()" onunload="GUnload()" style="margin: 0">
    <div id="map_canvas" style="width: 100%; height: 500px"></div>
    <br />
    <div align="center">
        <form method="post">
            Enter points coordinates (Latidue,Longitude)
            <br />
            <textarea name="points" cols="40" rows="10"><?php
                echo htmlentities($_POST['points'])
            ?></textarea>
            <br />
            <input type="submit" value="show on map">
        </form>
    </div>
</body>
</html>

