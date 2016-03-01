<html>
	<head>
		<title>main</title>
		<style>
			.thumb {
				height: 100px;
			}
		</style>
	</head>
	<body>
		<div class="file-upload">
			<?php echo Form::open(['route' => 'editFile','files' => true]); ?>

			    Select image to upload:
			    <input type="file" name="photo" id="fileToUpload">
			    <input type="submit" value="Upload Image" name="submit">
			<?php echo Form::close(); ?>

		</div>



		<table>
			<?php foreach($files as $file): ?>
			<tr>
				<td>
					<img class="thumb" src="<?php echo e($file->path); ?>">
				</td>
				<td>
					<a href="#">Print file</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	</body>
</html>