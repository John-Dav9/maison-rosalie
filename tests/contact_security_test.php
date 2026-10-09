<?php
declare(strict_types=1);

require_once __DIR__ . '/../model/ContactText.php';
require_once __DIR__ . '/../model/manager/ContactMailer.php';
require_once __DIR__ . '/../model/ManagerInterface.php';
require_once __DIR__ . '/../model/AbstractMapping.php';
require_once __DIR__ . '/../model/mapping/contactMapping.php';
require_once __DIR__ . '/../model/manager/ContactManager.php';

use model\ContactText;
use model\manager\ContactMailer;
use model\manager\ContactManager;

function expect(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function test_contact_text_removes_markup_and_preserves_message_lines(): void
{
    $clean = ContactText::plainText("<p>Bonjour</p>\r\n&lt;script&gt;alert(1)&lt;/script&gt;\0");
    expect($clean === "Bonjour\nalert(1)", 'Les balises doivent être retirées et les retours à la ligne conservés.');
    expect(!str_contains($clean, '<script>'), 'Le texte nettoyé ne doit pas contenir de balise script.');
}

function test_contact_mail_is_plain_text_and_uses_fixed_headers(): void
{
    $captured = null;
    $mailer = new ContactMailer(static function (string $to, string $subject, string $body, string $headers) use (&$captured): bool {
        $captured = compact('to', 'subject', 'body', 'headers');
        return true;
    });

    $sent = $mailer->send([
        'name' => 'Camille',
        'email' => 'camille@example.com',
        'subject' => 'Question',
        'message' => "Bonjour\nMerci",
    ], 'maison.rosalie.chocolat@gmail.com');

    expect($sent === true, 'Le transport de test doit confirmer l’envoi.');
    expect($captured['to'] === 'maison.rosalie.chocolat@gmail.com', 'La notification doit cibler l’adresse configurée.');
    expect(str_contains($captured['headers'], 'Content-Type: text/plain; charset=UTF-8'), 'Le courriel doit être en texte brut UTF-8.');
    expect(str_contains($captured['body'], "Bonjour\nMerci"), 'Le message doit être présent avec ses retours à la ligne.');
    expect(!str_contains($captured['body'], '<html'), 'Le corps du courriel ne doit pas être du HTML.');
}

function test_contact_manager_stores_sanitized_plain_text_with_prepared_values(): void
{
    $pdo = new PDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('CREATE TABLE Contact (Id INTEGER PRIMARY KEY AUTOINCREMENT, Name TEXT NOT NULL, Email TEXT NOT NULL, Subject TEXT NULL, Message TEXT NOT NULL, Created_at TEXT NOT NULL)');
    $manager = new ContactManager($pdo);
    $attackText = "<script>alert(1)</script> Bonjour &lt;b&gt;Camille&lt;/b&gt;\n'); DROP TABLE Contact; --";

    $saved = $manager->addContact([
        'name' => '<b>Camille</b>',
        'email' => 'camille@example.com',
        'subject' => '<i>Question</i>',
        'message' => $attackText,
    ]);

    expect($saved !== false, 'Le message de test valide doit être enregistré.');
    $row = $pdo->query('SELECT Name, Subject, Message FROM Contact')->fetch(PDO::FETCH_ASSOC);
    expect($row['Name'] === 'Camille', 'Le nom doit être enregistré sans balises.');
    expect($row['Subject'] === 'Question', 'Le sujet doit être enregistré sans balises.');
    expect(!str_contains($row['Message'], '<'), 'Le message stocké ne doit pas contenir de balises HTML.');
    expect(str_contains($row['Message'], "alert(1) Bonjour Camille\n'); DROP TABLE Contact; --"), 'Le texte SQL-like doit rester une simple valeur texte.');
    expect((int) $pdo->query('SELECT COUNT(*) FROM Contact')->fetchColumn() === 1, 'Le texte reçu ne doit pas exécuter sa commande SQL apparente.');
}

test_contact_text_removes_markup_and_preserves_message_lines();
test_contact_mail_is_plain_text_and_uses_fixed_headers();
test_contact_manager_stores_sanitized_plain_text_with_prepared_values();
echo "contact security tests passed\n";
