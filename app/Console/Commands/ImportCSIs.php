<?php

namespace App\Console\Commands;

use App\CSI;
use App\CSIDivision;
use App\CSIQuickList;
use App\Enum\QuickListType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use League\Csv\Reader;

class ImportCSIs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csi:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read and import CSIs and CSI Divisions list into database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('csis')->truncate();
        DB::table('csi_divisions')->truncate();
        DB::table('csi_quick_lists')->truncate();
        Schema::enableForeignKeyConstraints();

        $csv = Reader::createFromPath(storage_path('app/csi_data_32520_formatted.csv'));
        $records = collect($csv->getRecords());

        $bar = $this->output->createProgressBar($records->count());

        $records->each(function ($record) use ($bar) {

            // 0 index is csi division code and 1 index is csi division name

            if ($record[0] !== '' && $record[1] !== '') {
                $this->createCSIDivision($record[0], $record[1]);
            }

            // 2nd column index 2 is csi code index 3 is csi name

            if ($record[2] !== '' && $record[3] !== '') {
                $this->associateQuickList($this->createCSI($record[2], $record[3]));
            }

            // 3rd column index 4 is csi code index 5 is csi name

            if ($record[4] !== '' && $record[5] !== '') {
                $this->associateQuickList($this->createCSI($record[4], $record[5]));
            }

            // 4th column index 6 is csi code index 7 is csi name

            if ($record[6] !== '' && $record[7] !== '') {
                $this->associateQuickList($this->createCSI($record[6], $record[7]));
            }

            $bar->advance();
        });

        $bar->finish();

        $this->info("\n Import Complete!");
    }

    protected function associateQuickList(CSI $csi)
    {
        if ($this->associatedDocuments()->contains($csi->code)) {
            $this->createQuickListEntry($csi, QuickListType::ASSOCIATED_DOCUMENTS);
        }

        if ($this->generalRequirementsQuickSelect()->contains($csi->code)) {
            $this->createQuickListEntry($csi, QuickListType::GENERAL_REQUIREMENTS);
        }

        if ($this->tabQuickSelect()->contains($csi->code)) {
            $this->createQuickListEntry($csi, QuickListType::TAB);
        }
    }

    protected function createQuickListEntry(CSI $csi, $type)
    {
        CSIQuickList::create([
            'csi_id' => $csi->id,
            'quick_list' => $type
        ]);
    }


    protected function associatedDocuments()
    {
        return collect([
            '013529', // Health, Safety, and Emergency Response Procedures (MSDS)
            '017500', // Starting and Adjusting
            '017819', // Maintenance Contracts
            '017823.13', // Operation Data
            '017823.16', // Maintenance Data
            '017823.19', // Preventative Maintenance Instructions
            '017836', // Warranties
            '017843', // Spare Parts
            '017846', // Extra Stock Materials
            '017900', // Demonstration and Training
        ]);
    }

    protected function generalRequirementsQuickSelect()
    {
        return collect([
            '003146', // Permits
            '004526', // Works Compensation Certificate Schedule
            '004536', // Equal Employment Opportunity Affidavit
            '004539', // Minority Business Enterprise Affidavit
            '006113.13', // Performance Bond Form
            '006113.16', // Payment Bond Form
            '006216', // Certificate of Insurance Form
            '006516', // Certificate of Substantial Completion Form
            '006519.16', // Affidavit of Release of Liens Form
            '009357', // Record Change Order Requests
            '009463', // Record Change Orders
            '012973', // Schedule of Values
            '012976', // Progress Payment Procedures
            '013119', // Project Meetings (Minutes)
            '013226', // Construction Progress Reporting
            '013233', // Photographic Documentation
            '014533', // Code-Required Special Inspections and Procedures
            '017423', // Final Cleaning (Report)
            '017813', // Completion and Correction List (Punch List)
            '017839', // Project Record Documents
        ]);
    }

    protected function tabQuickSelect()
    {
        return collect([
            '019119.43', // Exterior Enclosure Commissioning
            '019119.73', // Roofing Commissioning
            '210800', // Commissioning of Fire Suppression
            '220800', // Commissioning of Plumbing
            '260800', // Commissioning of Electrical Systems
            '270800', // Commissioning of Communications
            '330800', // Commissioning of Utilities
            '017853', // Sustainable Design Closeout Documentation
            '230593', // Testing, Adjusting and Balancing for HVAC
        ]);
    }

    protected function createCSIDivision($code, $name)
    {
        return CSIDivision::create([
           'name' => $name,
           'code' => $code
        ]);
    }

    protected function createCSI($code, $name)
    {
        return CSI::create([
            'name' => $name,
            'code' => $code,
            'csi_division_id' => $this->findCSIDivisionId($code)
        ]);
        // associate division by finding division where first 2 digits of incoming CSI matches first 2 digits of CSI Division
    }

    protected function findCSIDivisionId($csiCode)
    {
        $divisionCode = substr($csiCode, 0, 2) . '0000';
        return CSIDivision::firstWhere('code', '=', $divisionCode)->id;
    }
}
