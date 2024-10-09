<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ImportDBController extends Controller
{
    public function importDB()
    {

        // Artisan::call('migrate:fresh', [
        //     '--force' => true,  // This forces the migration to run in production
        // ]);
        // Capture the output of the migration command immediately after running it
        // $migrateOutput = Artisan::output();

        $sql = file_get_contents(database_path() . '/backup.sql');

        DB::statement($sql);
        // $dbName = env('DB_DATABASE');
        // $dbUser = env('DB_USERNAME');
        // $dbPassword = env('DB_PASSWORD');
        // $dbHost = env('DB_HOST');

        // $command = "sudo mysql -u $dbUser -p$dbPassword -h $dbHost $dbName < $file_path";

        // $output = null;
        // $returnVar = null;

        // exec($command, $output, $returnVar);

        // if ($returnVar !== 0) {
        //     $this->error('Error importing the database dump.');
        // } else {
        //     $this->info('Database import successful.');
        // }

        return response()->json([
            'message' => 'Database imported successfully',
            // 'migrate_output' => $migrateOutput,
            'import_output' => $output,
            'return var' => $returnVar
        ]);
    }
}
