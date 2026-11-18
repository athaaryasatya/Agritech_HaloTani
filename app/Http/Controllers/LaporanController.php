<?php

namespace App\Http\Controllers;

use App\Exports\KeluhanExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function exportExcel(Request $request)
    {
        abort_unless(auth()->check(), 403);

        $status = $request->query('status', 'semua');
        $nama   = 'riwayat-keluhan-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new KeluhanExport(auth()->id(), $status), $nama);
    }
}
