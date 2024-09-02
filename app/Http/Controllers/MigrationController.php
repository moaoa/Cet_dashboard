<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MigrationController extends Controller
{
    public function runMigrationsAndSeeders()
    {
        try {
            Artisan::call('migrate:fresh', [
                '--force' => true,  // This forces the migration to run in production
            ]);
            // Capture the output of the migration command immediately after running it
            $migrateOutput = Artisan::output();

            // Run the seeders
            Artisan::call('db:seed', [
                '--force' => true,  // Force the seeding in production
            ]);
            // Capture the output of the seeding command immediately after running it
            $seedOutput = Artisan::output();

            return response()->json([
                'message' => 'Migrations and seeders ran successfully.',
                'migrate_output' => $migrateOutput,
                'seed_output' => $seedOutput
            ]);
        } catch (\Exception $e) {
            // Log the error if needed
            // \Log::error('Migration/Seeder error: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while running migrations and seeders.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
