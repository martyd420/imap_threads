<?php

namespace martyd420\imap_threads;

class ImapMessage
{

	private function __construct(
		private int $id,
        private ?string $from,
		private ?string $to,
		private ?string $subject,
		private string $message,
		private array $parents,
	) {}


	public static function createFromImapData(int $id, string $from, string $to, string $subject, string $message, array $parents): self
	{
		return new self($id, $from, $to, $subject, $message, $parents);
	}


	/**
	 * @return ImapMessage[]
	 */
	public function getParentMessageIds(): array
	{
		return $this->parents;
	}


	public function getId(): int
	{
		return $this->id;
	}

	public function getFrom(): ?string
	{
		return $this->from;
	}

	public function getTo(): ?string
	{
		return $this->to;
	}

	public function getSubject(): ?string
	{
		return $this->subject;
	}

	public function getMessage(): string
	{
		return $this->message;
	}

}