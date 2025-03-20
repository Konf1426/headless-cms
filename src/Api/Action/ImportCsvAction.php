<?php

declare(strict_types=1);

namespace App\Api\Action;

use App\Entity\Content;
use App\Entity\Upload;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
readonly class ImportCsvAction
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        #[Autowire(param: 'kernel.project_dir')]
        private string $uploadDir,
    ) {
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function __invoke(Request $request): bool
    {
        $file = $request->files->get('file');
        if (!$file instanceof UploadedFile || $file->getClientOriginalExtension() !== 'csv') {
            throw new BadRequestHttpException('Invalid file type. Only CSV files are allowed.');
        }

        $fileContent = file($file->getPathname());
        if (!$fileContent) {
            throw new BadRequestHttpException('Failed to read the file.');
        }

        /** @var User $user */
        $user = $this->security->getUser();
        $csv = array_map('str_getcsv', $fileContent);
        array_shift($csv);
        foreach ($csv as $row) {
            if ($row[1] && $file = @file_get_contents($row[1])) {
                $headersFile = @get_headers($row[1], true);
                $contentDisposition = isset($headersFile['Content-Disposition']) ?
                    explode('=', $headersFile['Content-Disposition'])[1] : null;
                $filename = $contentDisposition ? str_replace(
                    '"',
                    '',
                    $contentDisposition
                ) : basename($row[1]);
                $mimeType = $headersFile['Content-Type'] ?? null;
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                $path = uniqid() . "." . $extension;
                $pathUpload = $this->uploadDir . '/public/upload/' . $path;
                file_put_contents($pathUpload, $file);

                $cover = new Upload();
                $cover->filename = $filename;
                $cover->path = '/upload/' . $path;
                $cover->mimeType = $mimeType;
                $this->em->persist($cover);
            }
            $content = new Content();
            $content->title = (string) $row[0];
            $content->cover = $cover ?? null;
            $content->metaTitle = $row[2];
            $content->metaDescription = $row[3];
            $content->content = $row[4];
            $tags = explode('|', (string) $row[5]);
            $content->tags = $tags;
            $content->author = $user;
            $this->em->persist($content);
        }
        $this->em->flush();
        return true;
    }
}
