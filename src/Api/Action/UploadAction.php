<?php

declare(strict_types=1);

namespace App\Api\Action;

use App\Entity\Upload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
readonly class UploadAction
{
    public function __construct(
        private EntityManagerInterface $em,
        #[Autowire(param: 'kernel.project_dir')]
        private string $projectDir,
    ) {
    }

    /**
     * @param Request $request
     * @return Upload
     */
    public function __invoke(Request $request): Upload
    {
        $file = $request->files->get('file');
        if (!$file instanceof UploadedFile) {
            throw new BadRequestHttpException('File is not valid');
        }
        if ($file->getSize() > 2 * 1024 * 1024) {
            throw new BadRequestHttpException('File is too large');
        }
        if (!in_array($file->getClientOriginalExtension(), ['png', 'jpeg', 'webp'])) {
            throw new BadRequestHttpException('File is not a valid image');
        }

        $path = uniqid() . "." . $file->getClientOriginalExtension();

        if (!file_exists($this->projectDir.'/public/upload')) {
            mkdir($this->projectDir.'/public/upload', 0777, true);
        }
        $file->move($this->projectDir.'/public/upload', $path);

        $upload = new Upload();
        $upload->setFilename($file->getClientOriginalName());
        $upload->setPath('/upload/' . $path);
        $upload->setMimeType($file->getClientMimeType());


        $this->em->persist($upload);
        $this->em->flush();

        return $upload;
    }
}
