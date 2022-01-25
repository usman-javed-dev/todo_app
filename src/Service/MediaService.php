<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaService
{
  private $targetDirectory;
  private $logger;

  public function __construct($targetDirectory, LoggerInterface $logger)
  {
    $this->targetDirectory = $targetDirectory;
    $this->logger = $logger;
  }

  public function upload(UploadedFile $file)
  {
    $fileName = uniqid() . ".{$file->guessExtension()}";

    try {
      $file->move($this->getTargetDirectory(), $fileName);
    } catch (FileException $e) {
      $this->logger->error($e);
    }

    return $fileName;
  }

  public function getTargetDirectory()
  {
    return $this->targetDirectory;
  }

  public function remove(?string $path)
  {
    if ($path) {
      $filesystem = new Filesystem();

      $filePath = "{$this->getTargetDirectory()}/{$path}";

      if ($filesystem->exists($filePath)) {
        try {
          $filesystem->remove($filePath);
        } catch (IOExceptionInterface $e) {
          $this->logger->error("An error occurred while creating your directory at " . $e->getPath());
        }
      }
    }
  }
}
