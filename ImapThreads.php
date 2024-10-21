<?php

namespace martyd420\imap_threads;

use IMAP\Connection;

class ImapThreads
{
	private Connection $imapStream;


	public function __construct($mailbox, $login, $password)
	{
		$imap = imap_open($mailbox, $login, $password);

		if ($imap === false) {
			throw new \Exception('Cannot connect to IMAP server: ' . imap_last_error());
		}

		$this->imapStream = $imap;
	}


	public function listUnreadEmailsWithParents(): array {
		$emails = imap_search($this->imapStream, 'UNSEEN');
		$messages = [];

		if ($emails) {
			foreach ($emails as $emailNumber) {
				$body 		= imap_fetchbody($this->imapStream, $emailNumber, 1);
				$overview	= imap_fetch_overview($this->imapStream, $emailNumber, 0)[0];
				$subject 	= $overview->subject ?? null;
				$from 		= $overview->from ?? null;
				$to 		= $overview->to ?? null;


				$parents = [];
				$thread = imap_thread($this->imapStream);
				if (isset($thread['num'][$emailNumber])) {
					$parent_id = $thread['num'][$emailNumber];
					$parents[] = $parent_id; // Jednoduchý seznam ID rodičů
				}

				$messages[] = ImapMessage::createFromImapData($emailNumber, $from, $to, $subject, $body, $parents);
			}
		}

		imap_close($this->imapStream);
		return $messages;
	}


	function showEmailWithParents(int $emailNumber): ?ImapMessage {
		$overview = imap_fetch_overview($this->imapStream, $emailNumber, 0)[0];
		$subject = $overview->subject ?? null;
		$from = $overview->from ?? null;
		$to = $overview->to ?? null;
		$body = imap_fetchbody($this->imapStream, $emailNumber, 1);


		$parents = [];
		$thread = imap_thread($this->imapStream);
		if (isset($thread['num'][$emailNumber])) {
			$parentId = $thread['num'][$emailNumber];
			$parents[] = $parentId;
		}

		imap_close($this->imapStream);
		return ImapMessage::createFromImapData($emailNumber, $from, $to, $subject, $body, $parents);
	}

}