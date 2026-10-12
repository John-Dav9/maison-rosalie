<?php
$escapeMessaging = static fn($value): string => htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$hasSelectedMessage = $selectedContact instanceof \model\mapping\ContactMapping;
?>
<main class="min-h-screen bg-background px-4 pb-16 pt-32 text-on-background sm:px-6">
    <section class="mx-auto max-w-7xl overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm">
        <header class="bg-[#2F1B14] px-6 py-10 text-center text-surface sm:px-10">
            <p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-[#fed488]">Espace réservé</p>
            <h1 class="font-headline-lg text-4xl tracking-tight">Messagerie</h1>
            <p class="mx-auto mt-3 max-w-2xl text-surface-variant">Messages reçus via le formulaire de contact.</p>
        </header>

        <div class="space-y-6 p-5 sm:p-8">
            <?php if (is_array($messagingFeedback) && isset($messagingFeedback['message'])): ?>
                <div class="rounded-lg border px-4 py-3 <?= ($messagingFeedback['type'] ?? '') === 'error' ? 'border-red-700 bg-red-50 text-red-800' : 'border-green-700 bg-green-50 text-green-800' ?>" role="status" aria-live="polite">
                    <?= $escapeMessaging($messagingFeedback['message']) ?>
                </div>
            <?php endif; ?>

            <div class="grid gap-6 lg:grid-cols-[minmax(18rem,0.85fr)_minmax(0,1.5fr)]">
                <section aria-labelledby="message-list-heading" class="space-y-3">
                    <div class="flex items-center justify-between border-b border-[#C9A45C] pb-2">
                        <h2 id="message-list-heading" class="text-2xl font-bold font-['Playfair_Display']">Messages reçus</h2>
                        <span class="rounded-full bg-[#FBF5E6] px-3 py-1 text-sm font-semibold"><?= count($adminContacts) ?></span>
                    </div>

                    <?php if ($adminContacts === []): ?>
                        <p class="rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-5 text-on-surface-variant">Aucun message de contact pour le moment.</p>
                    <?php else: ?>
                        <ul class="space-y-2">
                            <?php foreach ($adminContacts as $contactItem): ?>
                                <?php $itemId = (int) $contactItem->getId(); ?>
                                <li>
                                    <a href="./?pages=admin&amp;action=messagerie&amp;contact_id=<?= $itemId ?>"
                                        class="block rounded-lg border p-4 transition hover:border-[#C9A45C] hover:bg-[#FBF5E6] <?= $selectedContactId === $itemId ? 'border-[#C9A45C] bg-[#FBF5E6] ring-1 ring-[#C9A45C]' : 'border-[#E5C98A] bg-white' ?>">
                                        <div class="flex items-start justify-between gap-3">
                                            <h3 class="font-semibold"><?= $escapeMessaging($contactItem->getName()) ?></h3>
                                            <time class="shrink-0 text-xs text-on-surface-variant"><?= $escapeMessaging($contactItem->getCreatedAt()) ?></time>
                                        </div>
                                        <p class="mt-1 text-sm text-on-surface-variant"><?= $escapeMessaging($contactItem->getSubject() ?: 'Sans sujet') ?></p>
                                        <p class="mt-2 line-clamp-2 text-sm text-on-surface-variant"><?= $escapeMessaging($contactItem->getMessage()) ?></p>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </section>

                <section aria-labelledby="message-detail-heading" class="min-w-0">
                    <?php if (!$hasSelectedMessage): ?>
                        <div class="flex min-h-64 items-center justify-center rounded-lg border border-dashed border-[#C9A45C] bg-[#FBF5E6] p-8 text-center text-on-surface-variant">
                            <?php if ($selectedContactId > 0): ?>
                                <p>Ce message n’existe plus ou n’a pas pu être chargé. Choisissez un autre message dans la liste.</p>
                            <?php else: ?>
                                <p>Sélectionnez un message dans la liste pour le consulter et y répondre.</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <article class="space-y-5 rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-5 sm:p-7">
                            <header class="border-b border-[#E5C98A] pb-4">
                                <p class="text-sm text-on-surface-variant">Message reçu le <?= $escapeMessaging($selectedContact->getCreatedAt()) ?></p>
                                <h2 id="message-detail-heading" class="mt-1 text-2xl font-bold font-['Playfair_Display']"><?= $escapeMessaging($selectedContact->getSubject() ?: 'Sans sujet') ?></h2>
                                <p class="mt-3 font-semibold"><?= $escapeMessaging($selectedContact->getName()) ?></p>
                                <a class="break-all text-sm text-secondary hover:underline" href="mailto:<?= $escapeMessaging($selectedContact->getEmail()) ?>"><?= $escapeMessaging($selectedContact->getEmail()) ?></a>
                            </header>

                            <div class="whitespace-pre-wrap break-words rounded-md bg-white p-4 leading-relaxed"><?= $escapeMessaging($selectedContact->getMessage()) ?></div>

                            <form action="./?pages=admin&amp;action=messagerie&amp;contact_id=<?= (int) $selectedContact->getId() ?>" method="post" class="space-y-3">
                                <input type="hidden" name="csrf_token" value="<?= $escapeMessaging($recipeFormCsrfToken) ?>">
                                <input type="hidden" name="admin_action" value="reply_contact">
                                <input type="hidden" name="contact_id" value="<?= (int) $selectedContact->getId() ?>">
                                <label for="contactReply" class="block text-lg font-semibold font-['Playfair_Display']">Votre réponse</label>
                                <textarea id="contactReply" name="reply" rows="7" maxlength="10000" required
                                    class="w-full rounded-md border border-[#C9A45C] bg-white p-3 focus:outline-none focus:ring-2 focus:ring-[#C9A45C]"
                                    placeholder="Écrivez votre réponse…"><?= $escapeMessaging($replyDraft) ?></textarea>
                                <p class="text-xs text-on-surface-variant">La réponse sera envoyée par e-mail à l’adresse de l’expéditeur.</p>
                                <button type="submit" class="rounded bg-[#2F1B14] px-5 py-3 font-semibold text-white transition hover:bg-secondary">Envoyer la réponse</button>
                            </form>

                            <form action="./?pages=admin&amp;action=messagerie" method="post" data-delete-contact-form data-contact-name="<?= $escapeMessaging($selectedContact->getName()) ?>">
                                <input type="hidden" name="csrf_token" value="<?= $escapeMessaging($recipeFormCsrfToken) ?>">
                                <input type="hidden" name="admin_action" value="delete_contact">
                                <input type="hidden" name="contact_id" value="<?= (int) $selectedContact->getId() ?>">
                                <button type="submit" class="rounded border border-red-700 px-4 py-2 font-semibold text-red-800 transition hover:bg-red-700 hover:text-white">Supprimer ce message</button>
                            </form>
                        </article>
                    <?php endif; ?>
                </section>
            </div>

            <a class="inline-flex font-semibold text-secondary hover:underline" href="./?pages=admin">Retour à l’administration</a>
        </div>
    </section>
</main>
<?php require BASE_URL . '/view/publicView/inc/footer.php'; ?>
<script src="./js/scripts.js?v=<?= filemtime(BASE_URL . '/public/js/scripts.js') ?>"></script>
<script>
// Confirme explicitement la suppression irréversible avant son envoi en POST.
document.querySelectorAll('[data-delete-contact-form]').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        const name = form.dataset.contactName || 'ce message';
        if (!window.confirm(`Supprimer définitivement le message de « ${name} » de la base de données ?`)) {
            event.preventDefault();
        }
    });
});
</script>
</body>
</html>
