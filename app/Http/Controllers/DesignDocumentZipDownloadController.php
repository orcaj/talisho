<?php

namespace App\Http\Controllers;

use App\DesignDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipStream\ZipStream;

class DesignDocumentZipDownloadController extends Controller
{
    public function __invoke(Request $request, DesignDocument $designDocument)
    {
        $this->authorize('view', $designDocument->project);

        $zipName = Str::kebab($designDocument->name) . '.zip';
        $headers = [
            'Content-Disposition' => "attachment; filename=\"{$zipName}\"",
            'Content-Type' => 'application/octet-stream',
        ];

        return new StreamedResponse(function () use ($designDocument, $headers, $zipName) {
            return $this->getZipStream($designDocument, $zipName);
        },
            200,
            $headers
        );
    }

    protected function getZipStream(DesignDocument $designDocument, string $zipName): ZipStream
    {
        $zip = new ZipStream($zipName);

        foreach ($designDocument->files as $file) {
            $stream = Storage::readStream($file->path);
            $zip->addFileFromStream($file->name, $stream);

            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        $zip->finish();

        return $zip;
    }
}
