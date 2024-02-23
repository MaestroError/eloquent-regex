<?php

// For Jon Miller from spindogs, as a good memory <3
function handle()
{
    $this->info('--Starting--');

    $cutoff_date = Carbon::now()->subMonths(2)->format('Y-m-d');

    $record_cell = DirectoryRecordCell::withTrashed()
    ->where('deleted_at', '<=', "$cutoff_date")->count();
       
    $this->info("$record_cell record cells found to delete");

    $record = DirectoryRecord::withTrashed()
    ->where('deleted_at', '<=', "$cutoff_date")->count();


    $this->info("$record records found to delete");
   
    $record_cell = DirectoryRecordCell::withTrashed()
    ->where('deleted_at', '<=', "$cutoff_date")->forceDelete();

    $record = DirectoryRecord::withTrashed()
    ->where('deleted_at', '<=', "$cutoff_date")->forceDelete();

    $this->info('Deleted records successfully.');

    $this->info('--Finishing--');
}
