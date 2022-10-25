<?php

namespace Opcodes\LogViewer\Http\Controllers;

use Opcodes\LogViewer\Facades\LogViewer;
use Opcodes\LogViewer\LogFile;

class IsScanRequiredController
{
    public function __invoke()
    {
        $files = LogViewer::getFiles();
        $filesRequiringScans = $files->filter(function (LogFile $file)
        {
            return $file->logs()->requiresScan();
        });
        $totalFileSize = $filesRequiringScans->sum(function (LogFile $file)
        {
            return $file->logs()->numberOfNewBytes();
        });
        $estimatedSecondsToScan = 0;

        if ($filesRequiringScans->isNotEmpty()) {
            // Let's estimate the scan duration by sampling the speed of the first scan.
            // For more accurate results, let's scan a file that's more than 10 MB in size.
            $file = $filesRequiringScans->filter(function ($file)
            {
                return $file->sizeInMB() > 10;
            })->first();

            if (is_null($file)) {
                $file = $filesRequiringScans
                    ->sortByDesc(function ($file)
                    {
                        return $file->size();
                    })
                    ->filter(function ($file)
                    {
                        return $file->size() > 0;
                    })
                    ->first();
            }

            if (is_null($file)) {
                // Haven't found any files that are not empty. No scan required.
                return response()->json(['requires_scan' => false]);
            }

            $scanStart = microtime(true);
            $file->logs()->scan();
            $scanEnd = microtime(true);

            // because we already scanned it here, it won't need to be scanned later.
            $totalFileSize -= $file->size();

            $durationInMicroseconds = ($scanEnd - $scanStart) * 1000000;
            $microsecondsPerMB = $durationInMicroseconds / $file->sizeInMB() * 1.20; // 20% buffer just in case
            $totalFileSizeInMB = $totalFileSize / 1024 / 1024;

            $estimatedSecondsToScan = ceil($totalFileSizeInMB * $microsecondsPerMB / 1000000);
        }

        $estimatedTimeForHumans = \Carbon\CarbonInterval::seconds($estimatedSecondsToScan)->cascade()->forHumans();

        return response()->json([
            'requires_scan' => $filesRequiringScans->isNotEmpty(),
            'estimated_scan_seconds' => $estimatedSecondsToScan,
            'estimated_scan_human' => $estimatedTimeForHumans,
        ]);
    }
}
