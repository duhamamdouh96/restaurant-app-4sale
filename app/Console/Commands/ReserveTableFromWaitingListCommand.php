<?php

namespace App\Console\Commands;

use App\Jobs\ReserveTableFromWaitingListJob;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\WaitingList;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReserveTableFromWaitingListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reserve-table-from-waiting-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if a table is available and reserve it from a waiting list';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $waitingList = WaitingList::all();

        foreach($waitingList as $nextOnWaitingList) {
            $availableTable = Table::available(
                $nextOnWaitingList->guests_count,
                Carbon::parse($nextOnWaitingList->from_date_time)->format('Y-m-d'),
                Carbon::parse($nextOnWaitingList->from_date_time)->format('h:i a'),
                Carbon::parse($nextOnWaitingList->to_date_time)->format('h:i a')
            )->first();

            if($availableTable) {
                ReserveTableFromWaitingListJob::dispatch($nextOnWaitingList, $availableTable);
            }
        }
    }
}
