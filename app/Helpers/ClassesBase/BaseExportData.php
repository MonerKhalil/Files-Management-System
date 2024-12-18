<?php

namespace App\Helpers\ClassesBase;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BaseExportData implements FromView ,ShouldAutoSize
{
    public function __construct(private $head,
                                private $body,
                                private $blade = "export-crud.export.xlsx",
                                private array $data = [],
                                private bool $isEmpty = false)
    {
    }

    /**
     * @return View
     * @author moner khalil
     */
    public function view(): View
    {
        return \view($this->blade,[
                "data" => [
                    "head" => $this->head,
                    "body" => $this->body,
                    "isEmpty" => $this->isEmpty,
                    "moreData" => $this->data,
                ],
            ]);
    }
}


