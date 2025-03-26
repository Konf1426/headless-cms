<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Api\Action\UploadAction;
use App\Repository\UploadRepository;
use App\Doctrine\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['upload:read']],
    denormalizationContext: ['groups' => ['upload:write']],
    operations: [
        new Post(
            uriTemplate: '/uploads',
            controller: UploadAction::class,         // ✅ Action custom
            deserialize: false,                      // ✅ Pas de désérialisation auto (multipart)
            normalizationContext: ['groups' => ['upload:read']],
            // security: "is_granted('IS_AUTHENTICATED_FULLY')"
        )
    ]
)]
class Upload
{
    use UuidTrait;

    #[ORM\Column(length: 255)]
    #[Groups(['upload:read', 'upload:write', 'content:read'])]
    private ?string $filename = null;

    #[ORM\Column(length: 255)]
    #[Groups(['upload:read', 'upload:write', 'content:read'])]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    #[Groups(['upload:read', 'upload:write'])]
    private ?string $mimeType = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['upload:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[Assert\NotNull(groups: ['upload:write'])]
    #[Groups(['upload:write'])]
    public ?File $file = null;

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
}
