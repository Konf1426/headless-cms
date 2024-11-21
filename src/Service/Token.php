<?php

declare(strict_types=1);

namespace App\Service;

use DateTime;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Throwable;

final readonly class Token
{
    public function __construct(
        #[Autowire(param: 'kernel.secret')]
        private string $secret,
    ) {
    }

    /**
     * @param string $email
     * @param DateTime $expire
     * @return string
     */
    public function generateTokenForUser(string $email, DateTime $expire = new DateTime('+4 hours')): string
    {
        $encoded = json_encode([
            'email' => $email,
            'expire' => $expire->getTimestamp(),
        ]);

        return base64_encode((string) json_encode([
            $encoded,
            $this->sign((string) $encoded)
        ]));
    }

    /**
     * @param string|null $token
     * @return string|null
     */
    public function decodeUserToken(?string $token): ?string
    {
        try {
            [$info, $sign] = json_decode(base64_decode((string) $token), true);

            if ($sign !== $this->sign($info)) {
                return null;
            }

            $info = json_decode($info, true);

            if ($info['expire'] < time()) {
                return null;
            }

            if (isset($info['email']) && filter_var($info['email'], FILTER_VALIDATE_EMAIL)) {
                return $info['email'];
            }

            return null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * @param string $encoded
     * @return string
     */
    private function sign(string $encoded): string
    {
        return hash('sha256', $encoded.'/'.$this->secret);
    }
}
