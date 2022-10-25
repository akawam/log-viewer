<?php

namespace Opcodes\LogViewer\Concerns;

use Carbon\Carbon;
use Opcodes\LogViewer\Level;

trait CanFilterIndex
{
    /**
     * @var int|null
     */
    protected $filterFrom;

    /**
     * @var int|null
     */
    protected $filterTo;

    /**
     * @var mixed[]|null
     */
    protected $filterLevels;

    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @var int|null
     */
    protected $skip;

    public function setQuery(string $query = null): self
    {
        if ($this->query !== $query) {
            $this->query = $query;

            $this->loadMetadata();
        }

        return $this;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @param int|\Carbon\Carbon $from
     * @param int|\Carbon\Carbon $to
     */
    public function forDateRange($from = null, $to = null): self
    {
        if ($from instanceof Carbon) {
            $from = $from->timestamp;
        }

        if ($to instanceof Carbon) {
            $to = $to->timestamp;
        }

        $this->filterFrom = $from;
        $this->filterTo = $to;

        return $this;
    }

    /**
     * @param string|mixed[] $levels
     */
    public function forLevels($levels = null): self
    {
        if (is_string($levels)) {
            $levels = [$levels];
        }

        if (is_array($levels)) {
            $this->filterLevels = array_map('strtolower', $levels);
        } else {
            $this->filterLevels = null;
        }

        return $this;
    }

    public function forLevel(string $level = null): self
    {
        return $this->forLevels($level);
    }

    public function getSelectedLevels(): ?array
    {
        return $this->filterLevels ?? Level::caseValues();
    }

    public function skip(int $skip = null): self
    {
        $this->skip = $skip;

        return $this;
    }

    public function getSkip(): ?int
    {
        return $this->skip;
    }

    public function limit(int $limit = null): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    protected function hasDateFilters(): bool
    {
        return isset($this->filterFrom)
            || isset($this->filterTo);
    }

    protected function hasFilters(): bool
    {
        return $this->hasDateFilters()
            || isset($this->filterLevels);
    }
}
