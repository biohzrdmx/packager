<?php
	# Get the base folder
	$base_dir = dirname(__FILE__);
	$message = false;

	/**
	 * Recursively add folders and files to the zip file
	 * @param string $root Base folder (for proper path-building inside the zip)
	 * @param string $dir  Folder to add
	 * @param object $zip  ZipArchive object
	 */
	function addFolder($root, $dir, &$zip){
		# Open specified folder
		$handle = opendir($dir);
		while( ($item = readdir($handle) ) !== FALSE ) {
			# Build the absolute path for the current item
			$path = sprintf('%s/%s', $dir, $item);
			# Skip '.' and '..'
			if ($item == '.' || $item == '..') continue;
			# Skip the script itself
			if ($item == 'packager-zip.php') continue;
			# Build the relative path for this item
			$relpath = str_replace($root . '/', '', $path);
			if( is_dir($path) ) {
				# If it's a folder create an empty folder in the zip and add its files (recursive!)
				$zip->addEmptyDir( $relpath );
				addFolder($root, $path, $zip);
			} else{
				# If it's a file just add it to the zip
				$zip->addFromString( $relpath, file_get_contents($path));
			}
		}
		# Close current folder
		closedir($handle);
	}

	if ( isset( $_GET['dl'] ) ) {
		# Download the file
		$dl = isset($_GET['dl']) ? $_GET['dl'] : '';
		if ( strpos($dl, '..') ) {
			$message = 'Security check failed!';
		} else {
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"$dl\"");
			readfile( sprintf('%s/%s', $base_dir, $dl) );
			exit;
		}
	}

	if ($_POST) {
		try {
			# Check zip support
			if (! class_exists('ZipArchive') ) {
				throw new Exception("ZipArchive class not found.", 1);
			}
			# Create the zip file
			$package = isset($_POST['package']) ? $_POST['package'] : 'package.zip';
			$zipFile = sprintf('%s/%s', $base_dir, $package);
			$zipArchive = new ZipArchive();
			if (!$zipArchive->open($zipFile, ZIPARCHIVE::OVERWRITE))
			    die("Failed to create archive\n");
			# Add the files!
			addFolder($base_dir, $base_dir, $zipArchive);
			# Check result and close zip file
			if (!$zipArchive->status == ZIPARCHIVE::ER_OK) {
			    echo "Failed to write files to zip\n";
			} else {
				$message = '<p>The package has been created.</p><p><a href="?dl='.$package.'">Click here to download the file.</a>';
			}
			$zipArchive->close();
		} catch (Exception $e) {
			# Something went wrong
			$message = 'No ZipArchive support, aborting.';
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Packager &raquo; Zip</title>
	<style>
		body { font-family: sans-serif; background: #F9F9F9; color: #333333; font-size: 14px; }
		a { color: #21759B; text-decoration: none; }
		a:hover { color: #D54E21; }
		form { width: 700px; padding: 1em 2em; margin: 50px auto 0; border: 1px solid #DFDFDF; background: white; border-radius: 4px; }
		input[type=text] { display: block; margin: 0 0 5px; border: 1px solid #CCC; background: white; padding: 3px; border-radius: 3px; width: 400px; }
		.help-block { display: block; color: #777 }
		.control-group { margin: 15px 0; }
		.control-label { display: block; float: left; width: 150px; margin: 3px 0 0; }
		.controls { margin-left: 160px; }
		.checkbox { display: inline-block; *display: block; zoom: 1; padding: 0 0 0 16px; margin-bottom: 5px; }
		.checkbox input[type=checkbox] { float: left; margin: 2px 0 0 -16px; }
		.button {display: inline-block; text-decoration: none; font-size: 14px; margin: 0; padding: 5px 10px; cursor: pointer; border-width: 1px; border-style: solid; -webkit-border-radius: 3px; border-radius: 3px; white-space: nowrap; -webkit-box-sizing: border-box; -moz-box-sizing:    border-box; box-sizing:         border-box; background: #f3f3f3; background-image: -webkit-gradient(linear, left top, left bottom, from(#fefefe), to(#f4f4f4)); background-image: -webkit-linear-gradient(top, #fefefe, #f4f4f4); background-image:    -moz-linear-gradient(top, #fefefe, #f4f4f4); background-image:      -o-linear-gradient(top, #fefefe, #f4f4f4); background-image:   linear-gradient(to bottom, #fefefe, #f4f4f4); border-color: #bbb; color: #333; text-shadow: 0 1px 0 #fff; }
		.button:hover,
		.button:focus {background: #f3f3f3; background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#f3f3f3)); background-image: -webkit-linear-gradient(top, #fff, #f3f3f3); background-image:    -moz-linear-gradient(top, #fff, #f3f3f3); background-image:     -ms-linear-gradient(top, #fff, #f3f3f3); background-image:      -o-linear-gradient(top, #fff, #f3f3f3); background-image:   linear-gradient(to bottom, #fff, #f3f3f3); border-color: #999; color: #222; }
		.button:focus  {-webkit-box-shadow: 1px 1px 1px rgba(0,0,0,.2); box-shadow: 1px 1px 1px rgba(0,0,0,.2); }
		.button:active {outline: none; background: #eee; background-image: -webkit-gradient(linear, left top, left bottom, from(#f4f4f4), to(#fefefe)); background-image: -webkit-linear-gradient(top, #f4f4f4, #fefefe); background-image:    -moz-linear-gradient(top, #f4f4f4, #fefefe); background-image:     -ms-linear-gradient(top, #f4f4f4, #fefefe); background-image:      -o-linear-gradient(top, #f4f4f4, #fefefe); background-image:   linear-gradient(to bottom, #f4f4f4, #fefefe); border-color: #999; color: #333; text-shadow: 0 -1px 0 #fff; -webkit-box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 ); box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 ); }
	</style>
</head>
<body>
	<form id="form_pack" action="" method="post">
		<?php if ($message): ?>
		<p><?php echo $message ?></p>
		<?php else: ?>
		<div class="control-group">
			<label for="package" class="control-label">Archive name</label>
			<div class="controls">
				<input type="text" name="package" id="package" value="package.zip">
				<span class="help-block">The name of the zip file to be created, defaults to package.zip</span>
			</div>
		</div>
		<br>
		<div class="control-group">
			<button class="button">Pack now</button>
		</div>
		<?php endif; ?>
	</form>
	<script type="text/javascript">
		//
	</script>
</body>
</html>