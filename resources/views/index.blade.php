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
			{!! Form::open(['route' => 'editFile','files' => true]) !!}
			    Select image to upload:
			    <input type="file" name="photo" id="fileToUpload">
			    <input type="submit" value="Upload Image" name="submit">
			{!! Form::close() !!}
		</div>



		<table>
			@foreach($files as $file)
			<tr>
				<td>
					<img class="thumb" src="{{ $file->path }}">
				</td>
				<td>
					<a href="#">Print file</a>
				</td>
			</tr>
			@endforeach
		</table>
	</body>
</html>