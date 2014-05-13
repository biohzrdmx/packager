<?php
	/**
	 * Packager » Unzip
	 *
	 * The easy way to deploy sites on your server
	 *
	 * You must have the ZipArchive extension enabled for this to work; if you don't have it
	 * enabled and can't enable it you may use the PclZip library from @link(http://www.phpconcept.net/pclzip, PhpConcept.net)
	 *
	 * THIS SOFTWARE IS DISTRIBUTED "AS IS". NO WARRANTY OF ANY KIND IS EXPRESSED OR IMPLIED.
	 * YOU USE AT YOUR OWN RISK. THE AUTHORS WILL NOT BE LIABLE FOR DATA LOSS, DAMAGES, LOSS OF
	 * PROFITS OR ANY OTHER KIND OF LOSS WHILE USING OR MISUSING THIS SOFTWARE.
	 *
	 * @version   1.0
	 * @author    biohzrdmx <github.com/biohzrdmx>
	 * @license   MIT
	 * @copyright (c) 2013 biohzrdmx
	 */

	$message = false;

	if ($_POST) {
		$wp_package = isset( $_POST['package'] ) ? $_POST['package'] : false;
		$delete = isset( $_POST['delete'] ) ? $_POST['delete'] : false;

		if (!$wp_package) die('Invalid package');

		$cur_dir = dirname(__FILE__);
		$zip_lib = sprintf('%s/pclzip.lib.php', $cur_dir);
		$zip_file = sprintf('%s/%s', $cur_dir, $wp_package);
		$out_path = $cur_dir;

		try {
			# Try with ZipArchive extension
			if (! class_exists('ZipArchive') ) {
				throw new Exception("ZipArchive class not found.", 1);
			}
			$zip = new ZipArchive;
			$res = $zip->open($zip_file);
			if ($res === TRUE) {
				$zip->extractTo($out_path);
				$zip->close();
				$message = "The package has been extracted.";
				if ($delete) {
					unlink($zip_file);
					$message = "The package has been extracted and deleted.";
				}
			} else {
				$message = "Error while extracting the package, aborting.";
			}
		} catch (Exception $e) {
			# Most-likely ZipArchive is not enabled, use the external class (if available)
			if ( file_exists($zip_lib) ) {
				include $zip_lib;
				$archive = new PclZip($zip_file);
				if ($archive->extract(PCLZIP_OPT_PATH, $out_path) == 0) {
					$message = "Error while extracting the package, aborting.";
				} else {
					$message = "The package has been extracted.";
					if ($delete) {
						unlink($zip_file);
						$message = "The package has been extracted and deleted.";
					}
				}
			} else {
				$message = 'No ZipArchive support and PclZip was not available, aborting.';
			}
		}

	}

	# Search for a ZIP package
	$package = '';
	$files = glob("*.zip", GLOB_BRACE);
	if ( count($files) > 0 ) {
		$package = $files[0];
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Packager &raquo; Unzip</title>
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
	<form id="form_extract" action="" method="post">
		<?php if ($message): ?>
		<p><?php echo $message ?></p>
		<?php else: ?>
		<div class="control-group">
			<label for="package" class="control-label">Package</label>
			<div class="controls">
				<input type="text" name="package" id="package" value="<?php echo $package ?>">
				<span class="help-block">The name of the zip file, for example my-site.zip</span>
			</div>
		</div>
		<div class="control-group">
			<label for="package" class="control-label">Options</label>
			<div class="controls">
				<label for="delete" class="checkbox"><input type="checkbox" name="delete" id="delete"> Delete package after extraction</label>
			</div>
		</div>
		<br>
		<div class="control-group">
			<button class="button">Unpack now</button>
		</div>
		<?php endif; ?>
	</form>
	<script type="text/javascript">

	</script>
</body>
</html>