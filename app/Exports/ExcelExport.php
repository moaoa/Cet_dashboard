<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelExport implements FromCollection
{

    protected $report;
    protected $headers = ['g1', 'g2', 'g3'];

    public function __construct($report, $headers)
    {
        $this->report = $report;
        $this->headers = $headers;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        return collect($this->report->toArray())->prepend($this->headers);
    }

    public function headings(): array
    {
        return $this->headers;
    }
}
