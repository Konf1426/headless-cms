<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Upload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class UploadProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private KernelInterface $kernel
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        if (!$data instanceof Upload || !$data->file) {
            return $data;
        }

        $uploadDir = $this->kernel->getProjectDir() . '/public/images';
        $originalName = pathinfo($data->file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
        $fileName = $safeName . '-' . uniqid() . '.' . $data->file->guessExtension();

        $data->file->move($uploadDir, $fileName);

        $data->setFilename($fileName);
        $data->setPath('/images/' . $fileName);
        $data->setMimeType($data->file->getMimeType());

        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }
}
