<?php

namespace Opcodes\LogViewer;

use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PreferenceStore
{
    const COOKIE_KEY = 'log-viewer-preferences';

    /**
     * @var mixed[]
     */
    protected $preferences;

    /**
     * @var \Symfony\Component\HttpFoundation\Cookie
     */
    protected $queuedCookie;

    public function __construct(Request $request)
    {
        $this->preferences = json_decode($request->cookie(self::COOKIE_KEY, 'null'), true) ?? [];
    }

    /**
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (isset($this->preferences[$key])) {
            return $this->preferences[$key];
        }

        return $default;
    }

    /**
     * @param mixed $value
     */
    public function put(string $key, $value): void
    {
        if (! isset($this->preferences)) {
            $this->preferences = [];
        }

        $this->preferences[$key] = $value;

        $this->queuedCookie = \cookie(
            self::COOKIE_KEY,
            json_encode($this->preferences),
            CarbonInterval::year()->totalMinutes
        );

        if (Cookie::queued(self::COOKIE_KEY)) {
            Cookie::unqueue(self::COOKIE_KEY);
        }

        Cookie::queue($this->queuedCookie);
    }
}
