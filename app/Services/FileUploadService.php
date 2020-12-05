<?php


namespace App\Services;


use Illuminate\Http\UploadedFile;

class FileUploadService
{
    public function uploadFiles($files, $basePath)
    {
        return collect($files)->map(function ($file) use ($basePath) {
            return [
                'path' => $this->upload($basePath, $file),
                'name' => $file->getClientOriginalName()
            ];
        });
    }

    protected function upload($path, UploadedFile $file)
    {
        // returns path
        $nameWithTimestamp = now()->format("YmdHms") . '_' . $file->getClientOriginalName();
        return $file->storeAs($path, $nameWithTimestamp);
    }
}
