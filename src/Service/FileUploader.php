<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $extension = $file->guessExtension();

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$extension;

        $extensionImages = ['png', 'jpeg', 'jpg', 'bmp', 'gif'];

        if (in_array($extension, $extensionImages)) {
            $targetDir = $this->getTargetDirectory().'images/';
        } else {
            $targetDir = $this->getTargetDirectory().'data/';
        }

        try {
            $file->move($targetDir, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }


    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
