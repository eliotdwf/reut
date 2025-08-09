<?php

namespace App\Jobs;

use App\Models\Asso;
use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncAssosJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**

     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 86400; // 1 day

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // 1. Fetch associations from API
            $response = Http::get(env('ASSOS_API_URL'));

            if ($response->failed()) {
                Log::error('SyncAssosJob: Failed to fetch associations', [
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);
                return;
            }

            $apiAssociations = $response->json();
            if (empty($apiAssociations)) {
                Log::info('SyncAssosJob: No associations found in API response');
                return;
            }

            // Collect the IDs of the associations
            $apiIds = collect($apiAssociations)->pluck('id')->map(fn($id) => (string) $id)->toArray();

            // 2. Get existing DB associations
            $dbAssos = Asso::all();
            $dbIds = $dbAssos->pluck('id')->map(fn($id) => (string) $id)->toArray();

            // 3. Find associations to add in database
            $toAddIds = array_diff($apiIds, $dbIds); // collect IDs that are in API but not in DB
            foreach ($apiAssociations as $asso) {
                if (in_array((string) $asso['id'], $toAddIds, true)) {
                    info("SyncAssosJob: Adding association {$asso['id']} ({$asso['shortname']})");
                    Asso::create([
                        'id' => $asso['id'],
                        'shortname' => $asso['shortname'],
                        'login' => $asso['login']
                    ]);
                }
            }

            // 4. Mark as not in cemetery assos that are in API
            $dbAssosToGetOutOfCemetery = $dbAssos->where('in_cemetery', true)
                ->whereIn('id', $apiIds);
            if ($dbAssosToGetOutOfCemetery->isNotEmpty()) {
                DB::transaction(function () use ($dbAssosToGetOutOfCemetery) {
                    foreach ($dbAssosToGetOutOfCemetery as $asso) {
                        Log::info("Marking association '{$asso->shortname}' as not in cemetery");
                        $asso->update(['in_cemetery' => false]);
                    }
                });
            }

            // 5. Find associations to mark as "in_cemetery"
            $toMarkIds = array_diff($dbIds, $apiIds); // collect IDs that are in DB but not in API
            if (!empty($toMarkIds)) {
                DB::transaction(function () use ($toMarkIds) {
                    // Fetch associations to be marked
                    $associations = Asso::whereIn('id', $toMarkIds)->get();

                    foreach ($associations as $asso) {
                        // Log shortname
                        Log::info("Marking association '{$asso->shortname}' as in cemetery");

                        // Delete all bookings for this association
                        Booking::where('asso_id', $asso->id)->delete();

                        // Mark as in cemetery
                        $asso->update(['in_cemetery' => true]);
                    }
                });
            }

            Log::info("SyncAssociationsJob: Sync complete", [
                'added' => count($toAddIds),
                'marked_out_of_cemetery' => count($dbAssosToGetOutOfCemetery),
                'marked_in_cemetery' => count($toMarkIds)
            ]);
        } catch (Throwable $e) {
            Log::error('SyncAssosJob: Exception', ['error' => $e->getMessage()]);
        }
    }
}
