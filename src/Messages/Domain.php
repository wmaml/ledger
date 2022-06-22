<?php

namespace Abivia\Ledger\Messages;

use Abivia\Ledger\Exceptions\Breaker;
use Abivia\Ledger\Helpers\Merge;

class Domain extends Message
{
    use HasCodes, HasNames;

    protected static array $copyable = [
        'code',
        'extra',
        ['revision', self::OP_DELETE | self::OP_UPDATE],
        ['toCode', self::OP_UPDATE],
    ];

    /**
     * @var string The Currency code that is used in journal entries by default.
     */
    public string $currencyDefault;

    /**
     * @var string An arbitrary string for use by the application.
     */
    public string $extra;

    /**
     * @var string The revision hash code for the Domain.
     */
    public string $revision;

    /**
     * @var bool Set true when the Domain has separate journals.
     */
    public bool $subJournals;

    /**
     * @inheritdoc
     */
    public static function fromArray(array $data, int $opFlags = self::OP_ADD): self
    {
        $domain = new static();
        $domain->copy($data, $opFlags);
        $domain->loadNames($data, $opFlags);
        $domain->subJournals = $data['subJournals'] ?? false;
        if (isset($data['currency'])) {
            $domain->currencyDefault = $data['currency'];
        }
        if ($opFlags & self::F_VALIDATE) {
            $domain->validate($opFlags);
        }

        return $domain;
    }

    /**
     * @inheritdoc
     */
    public function validate(int $opFlags = 0): self
    {
        $errors = $this->validateCodes($opFlags);
        if (isset($this->currencyDefault)) {
            if ($this->currencyDefault === '') {
                $errors[] = 'Currency code cannot be empty';
            } else {
                $this->currencyDefault = strtoupper($this->currencyDefault);
            }
        }
        if ($opFlags & self::OP_ADD && count($this->names) === 0) {
            $errors[] = 'A non-empty names property is required';
        }
        if ($opFlags & self::OP_UPDATE) {
            if (!isset($this->revision)) {
                $errors[] = 'A revision code is required';
            }
        }
        try {
            foreach ($this->names as $name) {
                $name->validate($opFlags);
            }
        } catch (Breaker $exception) {
            Merge::arrays($errors, $exception->getErrors());
        }
        if (count($errors)) {
            throw Breaker::withCode(Breaker::BAD_REQUEST, $errors);
        }
        return $this;
    }

}
