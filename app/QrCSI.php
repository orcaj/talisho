<?php

namespace App;

use App\CSI;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCSI extends Model
{
    protected $table = 'qr_csis';

    protected $fillable = [
        'csi_id',
        'name'
    ];

    public function csi()
    {
        return $this->belongsTo(CSI::class);
    }

    public function generate(Project $project)
    {
        $csiCode = $this->csi->code;

        $baseQrCode = QrCodeGenerator::format('png')->size(200)->margin(2);

        $qrCode = $baseQrCode->generate(url(route('projects.csi', ['project'=>$project, 'csi'=>$this->csi])));

        $path = "project/$project->id/qr_codes/$this->name.png";

        Storage::put($path, $qrCode);

        return Storage::url($path);
    }
}
