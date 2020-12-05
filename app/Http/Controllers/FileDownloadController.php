<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileDownloadController extends Controller
{
    public function __invoke(Request $request, File $file)
    {
        abort_unless($request->user()->isAuthorizedToViewFile($file), 403);

        return Storage::download($file->path, $file->name);
    }
}
