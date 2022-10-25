<?php

namespace Opcodes\LogViewer\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Opcodes\LogViewer\LogFile;

class LogFileDeleted
{
    use Dispatchable;
    /**
     * @var \Opcodes\LogViewer\LogFile
     */
    public $file;

    public function __construct(LogFile $file)
    {
        $this->file = $file;
    }
}
