<?php

use martyd420\imap_threads\ImapThreads;

include 'ImapMessage.php';
include 'ImapThreads.php';

$mailbox = '{imap.seznam.cz:993/imap/ssl}';
$login = 'emailova_adresa@seznam.cz';
$password = 'FCKGWRHQQ2YXRKT8TG6W2B7Q8';

try {
	$it = new ImapThreads($mailbox, $login, $password);
	$unread_emails = $it->listUnreadEmailsWithParents();
	var_dump($unread_emails);
} catch (Exception $e) {
	var_dump($e);
}

