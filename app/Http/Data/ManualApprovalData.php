<?php


namespace App\Http\Data;


class ManualApprovalData
{
    protected $files;
    protected $comments;

    public function __construct($files, $comments)
    {
        $this->files = $files;
        $this->comments = $comments;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getComments()
    {
        return $this->comments;
    }
}
