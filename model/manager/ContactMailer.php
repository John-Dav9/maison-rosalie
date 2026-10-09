<?php

declare(strict_types=1);

namespace model\manager;

use Closure;
use Throwable;
use model\ContactText;

require_once __DIR__ . '/../ContactText.php';

/** Envoie les notifications du formulaire en texte brut via le transport mail configuré par PHP. */
final class ContactMailer
{
    private Closure $transport;

    /** Le transport injectable permet de tester le message sans envoyer de vrai courriel. */
    public function __construct(?callable $transport = null)
    {
        $this->transport = $transport !== null
            ? Closure::fromCallable($transport)
            : static fn(string $to, string $subject, string $body, string $headers): bool => @mail($to, $subject, $body, $headers);
    }

    /** Envoie un message de contact nettoyé à l'adresse fixe de réception. */
    public function send(array $contact, string $recipient): bool
    {
        foreach (['name', 'email', 'message'] as $required) {
            if (!isset($contact[$required]) || !is_string($contact[$required])) {
                return false;
            }
        }
        if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        try {
            $name = ContactText::plainText($contact['name'], true);
            $email = trim($contact['email']);
            $subjectText = isset($contact['subject']) && is_string($contact['subject'])
                ? ContactText::plainText($contact['subject'], true)
                : '';
            $message = ContactText::plainText($contact['message']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }

            $subject = 'Nouveau message du formulaire de contact';
            $body = "Nouveau message reçu depuis le formulaire de contact Maison Rosalie\n\n"
                . "Nom : {$name}\n"
                . "Adresse e-mail : {$email}\n"
                . 'Sujet : ' . ($subjectText !== '' ? $subjectText : '(aucun)') . "\n\n"
                . "Message :\n{$message}\n";
            $headers = "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8";

            $from = getenv('CONTACT_FROM_EMAIL');
            if (is_string($from) && filter_var($from, FILTER_VALIDATE_EMAIL)) {
                $headers .= "\r\nFrom: Maison Rosalie <{$from}>";
            }

            return (bool) ($this->transport)($recipient, $subject, $body, $headers);
        } catch (Throwable $e) {
            error_log('Échec d’envoi de la notification de contact : ' . $e->getMessage());
            return false;
        }
    }
}
