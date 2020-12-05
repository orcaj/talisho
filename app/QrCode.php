<?php

namespace App;

use App\Project;
use App\Enum\QrCodeType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCode extends Model
{
    protected $fillable = [
        'link',
        'type',
        'slug',
        'description'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function resolve(Project $project = null)
    {
        if ($this->type === QrCodeType::INTERNAL) {
            return route($this->link, ['project'=>$project]);
        } else {
            return $this->link;
        }
    }

    public function generate(Project $project = null)
    {
        $baseQrCode = QrCodeGenerator::format('png')->size(200)->margin(2);

        if ($this->type === QrCodeType::INTERNAL) {
            $qrCode = $baseQrCode->generate(url("/qr-codes/$this->slug/$project->id"));
            $path = "project/$project->id/qr_codes/$this->slug.png";
        } else {
            $qrCode = $baseQrCode->generate(url("/qr-codes/$this->slug"));
            $path = "public-qr-codes/$this->slug.png";
        }

        Storage::put($path, $qrCode);

        return Storage::url($path);
    }
}
