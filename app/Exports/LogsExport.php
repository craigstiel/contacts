<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LogsExport implements FromView, ShouldAutoSize
{
    public $logs;

    public function __construct($logs) {
        $this->logs = $logs;
    }

    public function view(): View
    {
        return view('exports.logs', [
            'logs' => $this->logs
        ]);
    }
}
