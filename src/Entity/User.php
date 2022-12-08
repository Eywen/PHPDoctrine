<?php

/**
 * src/Entity/User.php
 *
 * @category Entities
 * @license https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

namespace MiW\Results\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Stringable;

/**
 * User
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
#[ORM\Entity, ORM\Table(name: "users")]
#[ORM\UniqueConstraint(name: "IDX_UNIQ_USERNAME", columns: [ "username" ])]
#[ORM\UniqueConstraint(name: "IDX_UNIQ_EMAIL", columns: [ "email" ])]
class User implements JsonSerializable, Stringable
{
    #[ORM\Column(
        name: "id",
        type: "integer",
        nullable: false
    )]
    #[ORM\Id, ORM\GeneratedValue(strategy: "IDENTITY")]
    protected int $id;

    #[ORM\Column(
        name: "username",
        type: "string",
        length: 32,
        unique: true,
        nullable: false
    )]
    protected string $username;

    #[ORM\Column(
        name: "email",
        type: "string",
        length: 60,
        unique: true,
        nullable: false
    )]
    protected string $email;

    #[ORM\Column(
        name: "enabled",
        type: "boolean",
        nullable: false,
        options: [ "default" => false ]
    )]
    protected bool $enabled;

    #[ORM\Column(
        name: "admin",
        type: "boolean",
        nullable: true,
        options: [ "default" => false ]
    )]
    protected bool $isAdmin;

    #[ORM\Column(
        name: "password",
        type: "string",
        length: 60,
        nullable: false
    )]
    protected string $password;

    /**
     * User constructor.
     *
     * @param string $username username
     * @param string $email    email
     * @param string $password password
     * @param bool   $enabled  enabled
     * @param bool   $isAdmin  isAdmin
     */
    public function __construct(
        string $username = '',
        string $email = '',
        string $password = '',
        bool $enabled = false,
        bool $isAdmin = false
    ) {
        $this->id       = 0;
        $this->username = $username;
        $this->email    = $email;
        $this->setPassword($password);
        $this->enabled  = $enabled;
        $this->isAdmin  = $isAdmin;
    }

    /**
     * @return int User id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Get user e-mail
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set user e-mail
     *
     * @param string $email email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return void
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     * @return void
     */
    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * Set password
     *
     * @param string $password password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = strval(password_hash($password, PASSWORD_DEFAULT));
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Verifies that the given hash matches the user password.
     *
     * @param string $password password
     * @return boolean
     */
    public function validatePassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Representation of User as string
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            '[%s: %3d - %20s - %30s - %1d - %1d]',
            basename(self::class),
            $this->id,
            utf8_encode($this->username),
            utf8_encode($this->email),
            $this->enabled,
            $this->isAdmin
        );
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'            => $this->id,
            'username'      => utf8_encode($this->username),
            'email'         => utf8_encode($this->email),
            'enabled'       => $this->enabled,
            'admin'         => $this->isAdmin
        ];
    }
}
