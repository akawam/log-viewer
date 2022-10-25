<?php

namespace Opcodes\LogViewer;

class Level
{
    const Debug = 'debug';

    const Info = 'info';

    const Notice = 'notice';

    const Warning = 'warning';

    const Error = 'error';

    const Critical = 'critical';

    const Alert = 'alert';

    const Emergency = 'emergency';

    const Processing = 'processing';

    const Processed = 'processed';

    const Failed = 'failed';

    const None = '';

    /**
     * @var string
     */
    public $value;

    public function __construct(string $value = null)
    {
        $this->value = $value ?? self::None;
    }

    public static function cases(): array
    {
        return [
            self::Debug,
            self::Info,
            self::Notice,
            self::Warning,
            self::Error,
            self::Critical,
            self::Alert,
            self::Emergency,
            self::Processing,
            self::Processed,
            self::Failed,
            self::None,
        ];
    }

    public static function from(string $value = null): self
    {
        return new self($value);
    }

    public function getName(): string
    {
        if ($this->value === self::None) {
            return 'None';
        }
        return ucfirst($this->value);
    }

    public function getClass(): string
    {
        switch ($this->value) {
            case self::Processed :
                return 'success';
            case self::Debug:
            case self::Info:
            case self::Processing:
            case self::Notice:
                return 'info';
            case self::Warning:
            case self::Failed:
                return 'warning';
            case self::Error:
            case self::Critical:
            case self::Alert:
            case self::Emergency:
                return 'danger';
            default:
                return 'none';
        }
    }

    public static function caseValues(): array
    {
        return self::cases();
    }
}
