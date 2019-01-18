<?php

$num_results = (! empty($_POST['num_results'])) ? $_POST['num_results'] : 20;
$delta = (! empty($_POST['delta'])) ? $_POST['delta'] : 24;
$reduce_brightness = (isset($_POST['reduce_brightness'])) ? $_POST['reduce_brightness'] : 1;
$reduce_gradients = (isset($_POST['reduce_gradients'])) ? $_POST['reduce_gradients'] : 1;

include_once(__DIR__."\inc\colors.inc.php");

$ex=new GetMostCommonColors();

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title>Image Color Extraction</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="wrap">
<form action="index.php" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Upload Your Own Image for processing</legend>
<div>
    <label>File: <input type="file" name="imgFile" /></label>
</div>
<div>
    <label>Number of colors: <input type="text" name="num_results" value="<?=$num_results?>" /></label>
</div>
<div>
    <label>delta: <input type="text" name="delta" value="<?=$delta?>" /></label>
</div>
<div>
    <label>Reduce brightness: <input type="radio" name="reduce_brightness" value="1" <?php if ($reduce_brightness) {?>checked="checked"<?php } ?> /> Yes <input type="radio" name="reduce_brightness" value="0" <?php if (! $reduce_brightness) {?>checked="checked"<?php } ?> /> No</label>
</div>
<div>
    <label>Reduce gradients: <input type="radio" name="reduce_gradients" value="1" <?php if ($reduce_gradients) {?>checked="checked"<?php } ?> /> Yes <input type="radio" name="reduce_gradients" value="0" <?php if (! $reduce_gradients) {?>checked="checked"<?php } ?> /> No </label>
</div>
<div>
    <input type="submit" name="action" value="Process" />
</div>
</fieldset>
</form>
<?php
// was any file uploaded?
if ( isset( $_FILES['imgFile']['tmp_name'] ) && strlen( $_FILES['imgFile']['tmp_name'] ) > 0 )
{
	// move image to a writable directory
	if (! move_uploaded_file($_FILES['imgFile']['tmp_name'], 'images/'.$_FILES['imgFile']['name']))
	{
		die("Error moving uploaded file to images directory");
	}

	$colors=$ex->Get_Color( 'images/'.$_FILES['imgFile']['name'], $num_results, $reduce_brightness, $reduce_gradients, $delta);
	if(!$colors) throw new Exception("Error Processing Request for colors", 1);
?>
<table>
<tr><td>Color</td><td>Color Code</td><td>Percentage</td><td rowspan="<?php echo (($num_results > 0)?($num_results+1):22500);?>"><img src="<?='images/'.$_FILES['imgFile']['name']?>" alt="test image" /></td></tr>
<?php
foreach ( $colors as $hex => $count )
{
	if ( $count > 0 )
	{
		echo "<tr><td style=\"background-color:#".$hex.";\"></td><td>".$hex."</td><td>$count</td></tr>";
	}
}
?>
</table>
<br />
<?php
}
?>

<?php
// $colors = $ex->Get_Color("2.jpg", $num_results, $reduce_brightness, $reduce_gradients, $delta);
//  foreach ($colors as $key => $value) {
//      echo "<div style=\"background-color:#".$key.";\">
//      '$key'
//      </div>";
// //  }
?>

</div>
</body>
</html>
