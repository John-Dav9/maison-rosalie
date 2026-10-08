<?php

namespace model\mapping;


use model\AbstractMapping;
use Exception;

class UserMapping extends AbstractMapping
{
    protected ?int $user_id = null;
    protected ?string $user_name = null;
    protected ?string $user_real_name = null;
    protected ?string $user_mail = null;
    protected ?string $user_pwd = null;
    protected ?string $user_date_inscription = null;
    protected ?int $user_role_id = 0;

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): void
    {
        if ($user_id <= 0) throw new Exception("user_id doit être un entier positif");
        $this->user_id = $user_id;
    }

    public function getUserLogin(): ?string
    {
        return $this->user_name;
    }

    public function setUserLogin(?string $user_login): void
    {
        $user_login = strip_tags(trim($user_login));
        if (empty($user_login))
            throw new Exception("Login non valide");
        if (strlen($user_login) < 3 || strlen($user_login) > 45)
            throw new Exception("Le login doit faire entre 3 et 45 caractères");
        // Ne peut pas commencer par un chiffre, ni contenir des espaces ou des caractères spéciaux
        if (preg_match('/^[a-zA-Z][a-zA-Z0-9]{2,45}$/', $user_login)) {
            $this->user_name = $user_login;
        } else {
            throw new Exception("Votre username doit faire de 3 à 30 caractères, commencer par une lettre et ne contenir que des lettres et des chiffres non accentués");
        }
    }

    public function getUserRealName(): ?string
    {
        return $this->user_real_name;
    }

    public function setUserRealName(?string $user_real_name): void
    {
        if ($user_real_name === null || trim($user_real_name) === '') {
            throw new Exception("Le nom de la personne est obligatoire");
        }

        $user_real_name = trim(strip_tags($user_real_name));
        if (strlen($user_real_name) > 100) {
            throw new Exception("Le nom ne doit pas dépasser 100 caractères");
        }

        $this->user_real_name = $user_real_name;
    }

    public function getUserPwd(): ?string
    {
        return $this->user_pwd;
    }

    public function setUserPwd(string $user_pwd): void
    {
        $this->user_pwd = $user_pwd;
    }

    public function getUserMail(): ?string
    {
        return $this->user_mail;
    }

    public function setUserMail(?string $user_mail): void
    {
        if (filter_var($user_mail, FILTER_VALIDATE_EMAIL)) {
            $this->user_mail = $user_mail;
        } else {
            throw new Exception("Votre email n'est pas valide");
        }
    }


    public function getUserDateInscription(): ?string
    {
        return $this->user_date_inscription;
    }

    public function setUserDateInscription(?string $user_date_inscription): void
    {
        if (is_null($user_date_inscription)) return;
        $date = date('Y-m-d H:i:s', strtotime($user_date_inscription));
        if (!$date) throw new Exception("La date d'inscription n'est pas au bon format");
        $this->user_date_inscription = $date;
    }


    public function getUserRoleId(): ?int
    {
        return $this->user_role_id;
    }

    public function setUserRoleId(int $user_role_id): void
    {
        if ($user_role_id < 0 || $user_role_id > 255) {
            throw new Exception("role_id doit être compris entre 0 et 255");
        }
        $this->user_role_id = $user_role_id;
    }
}
