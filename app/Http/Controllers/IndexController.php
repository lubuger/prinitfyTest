<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Upload as Upload;
use Intervention\Image\ImageManagerStatic as Image;


class IndexController extends Controller
{

	public function index() {
		$files = Upload::all();

		return view('index',['files' => $files]);
	}

	public function uploadFile(Request $req) {
		$req->all();
	}

	public function editFile(Request $request) {

		$file = $request->file('photo');
		$name = $file->getClientOriginalName();
		$file->move(__DIR__.'/../../../public/uploads/',$name);

		return view('editor',['image' => 'uploads/'.$name]);
	}

	public function saveFile(Request $request) {

		$uploads = Upload::all();

		if(count($uploads) == 0) {
			$name = 1;
		} else {
			$name = $uploads[count($uploads) -1]->id;
			$name = $name + 1;
		}

		$input = $request->all();

	    $img = Image::make($input['path'])->crop(222, 259, 158, 79);

	    $img = $img->resize(4500,5250);

	    $img->save('/var/www/html/printify/public/uploads/'.$name.'.png',100);

	    Upload::create(['path' => 'uploads/'.$name.'.png']);

	    //We need to get new data!
		return \Redirect::route('index');
	}

	public function printFile() {

	}


}