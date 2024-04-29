<?php

namespace De\Idrinth\ConfigCheck\Data;

use De\Idrinth\ConfigCheck\Message;

class FileResult
{
    /**
     * @var Message[]
     */
    private array $messages = [];

    public function __construct(private string $path)
    {
    }

    public function addMessage(Message $message): void
    {
        $this->messages[] = $message;
    }

    public function getMessage(int $verbose = 0, bool $warningsAsErrors = false): string
    {
        if ($verbose < 1) {
            return '';
        }
        if (count($this->messages) === 0 && $verbose < 2) {
            return '';
        }
        $errors = $this->getErrorNum($warningsAsErrors);
        if ($errors === 0 && $verbose < 3) {
            return '';
        }
        $content = "\n[" . ($errors > 0 ? 'F' : 'X') . "] $this->path\n";
        foreach ($this->messages as $message) {
            $content .= $message->toString($verbose);
        }
        return $content;
    }

    public function __toString(): string
    {
        return $this->getMessage(1);
    }

    public function getErrorNum(bool $warningsAsErrors = false): int
    {
        $amount = 0;
        foreach ($this->messages as $message) {
            if ($message->isFailure($warningsAsErrors)) {
                $amount++;
            }
        }
        return $amount;
    }
}
