<?php

namespace model\manager;

use PDO;
use Throwable;
use model\ManagerInterface;
use model\mapping\ContactMapping;

/** Gère l'enregistrement et la consultation des messages du formulaire de contact. */
class ContactManager implements ManagerInterface
{
    protected PDO $connect;

    public function __construct(PDO $connect)
    {
        $this->connect = $connect;
    }

    /**
     * Enregistre les champs envoyés par le formulaire : name, email, subject et message.
     * Le sujet est facultatif; les limites suivent le formulaire et la table Contact.
     */
    public function addContact(array $data): ContactMapping|false
    {
        if (!isset($data['name'], $data['email'], $data['message'])
            || !is_string($data['name'])
            || !is_string($data['email'])
            || !is_string($data['message'])) {
            return false;
        }

        $subject = $data['subject'] ?? null;
        if ($subject !== null && !is_string($subject)) {
            return false;
        }

        try {
            // Le mapping nettoie les espaces et valide le format de l'adresse email.
            $contact = new ContactMapping([
                'name' => trim($data['name']),
                'email' => trim($data['email']),
                'subject' => $subject === null || trim($subject) === '' ? null : trim($subject),
                'message' => trim($data['message']),
            ]);

            $name = $contact->getName();
            $email = $contact->getEmail();
            $subject = $contact->getSubject();
            $message = $contact->getMessage();

            // Les limites correspondent aux colonnes et aux règles du formulaire.
            if ($name === null || strlen($name) < 2 || strlen($name) > 100
                || $email === null || strlen($email) > 255
                || ($subject !== null && strlen($subject) > 120)
                || $message === null || strlen($message) < 3 || strlen($message) > 500) {
                return false;
            }

            // La base complète automatiquement l'ID et la date d'envoi.
            $sql = "INSERT INTO `Contact` (`Name`, `Email`, `Subject`, `Message`, `Created_at`)
                    VALUES (:name, :email, :subject, :message, CURRENT_TIMESTAMP)";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
            ]);

            return new ContactMapping([
                'Id' => (int) $this->connect->lastInsertId(),
                'Name' => $name,
                'Email' => $email,
                'Subject' => $subject,
                'Message' => $message,
                'Created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (Throwable $e) {
            error_log('Échec d’enregistrement du message de contact : ' . $e->getMessage());
            return false;
        }
    }

    /** Retourne les messages reçus, du plus récent au plus ancien. */
    public function getAllContacts(): array
    {
        $sql = "SELECT `Id`, `Name`, `Email`, `Subject`, `Message`, `Created_at`
                FROM `Contact`
                ORDER BY `Created_at` DESC, `Id` DESC";

        try {
            $stmt = $this->connect->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return array_map(static fn(array $row): ContactMapping => new ContactMapping($row), $rows);
        } catch (Throwable $e) {
            error_log('Échec de récupération des messages de contact : ' . $e->getMessage());
            return [];
        }
    }

    /** Récupère un message de contact par son ID. */
    public function getContactById(int $contactId): ?ContactMapping
    {
        if ($contactId <= 0) {
            return null;
        }

        try {
            $stmt = $this->connect->prepare(
                "SELECT `Id`, `Name`, `Email`, `Subject`, `Message`, `Created_at`
                 FROM `Contact` WHERE `Id` = :id LIMIT 1"
            );
            $stmt->execute(['id' => $contactId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $row === false ? null : new ContactMapping($row);
        } catch (Throwable $e) {
            error_log('Échec de récupération du message de contact : ' . $e->getMessage());
            return null;
        }
    }
}
