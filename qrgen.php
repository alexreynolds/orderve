<!-- Standard QR code framework created by Kazuhiko Arase and licensed by MIT -->
<script type="text/javascript" src="qrcode.js"></script>
<!-- Used to generate the QR canvas drawing -->
<script type="text/javascript" src="qrcanvas.js"></script>

<?php

// Ensures the user inputted a URL before proceeding
if (isset($_POST['url']))
{
	$url = $_POST['url'];
	$errcorr = $_POST['level'];
	$style = $_POST['style'];
	$color = $_POST['colordark'];
	$divname = $_POST['divname'];


echo "<script>";
echo "drawqr(4," . $divname . "," . $url . ", ". $errcorr . ");";
echo "</script>";
// drawqr(4, "qr_spot", "http://qrproject.cs.vassar.edu", "L");

}

?>