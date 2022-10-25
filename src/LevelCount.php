<?php

namespace Opcodes\LogViewer;

class LevelCount
{
    /**
     * @var \Opcodes\LogViewer\Level
     */
    public $level;
    /**
     * @var int
     */
    public $count = 0;
    /**
     * @var bool
     */
    public $selected = false;

    public function __construct(Level $level, int $count = 0, bool $selected = false)
    {
        $this->level = $level;
        $this->count = $count;
        $this->selected = $selected;
    }
}
