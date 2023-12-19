<?php

namespace App\Jobs;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReserveTableFromWaitingListJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $nextOnWaitingList;
    public $availableTable;

    /**
     * Create a new job instance.
     */
    public function __construct($nextOnWaitingList, $availableTable)
    {
        $this->nextOnWaitingList = $nextOnWaitingList;
        $this->availableTable = $availableTable;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        dd($this->nextOnWaitingList);
        $date = Carbon::parse($this->nextOnWaitingList->from_date_time)->format('Y-m-d');
        $from = Carbon::parse($this->nextOnWaitingList->from_date_time)->format('h:i a');
        $to = Carbon::parse($this->nextOnWaitingList->to_date_time)->format('h:i a');

        (new Reservation)->store(
            $this->availableTable,
            $this->nextOnWaitingList->guests_count,
            $date,
            $from,
            $to,
        );

        $this->nextOnWaitingList->delete();
    }
}
