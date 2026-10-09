<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\ContactInquiry;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\GraduateRecord;
use App\Models\ManualVerification;
use App\Models\PrivacySetting;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summit:backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a secure snapshot backup of the Summit University Alumni Database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting Summit University Alumni Database backup snapshot...');

        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $timestamp = now()->format('Ymd_His');
        $filename = "summit_alumni_db_backup_{$timestamp}.json";
        $filepath = "{$backupDir}/{$filename}";

        $data = [
            'metadata' => [
                'institution' => 'Summit University, Offa, Kwara State, Nigeria',
                'database' => config('database.connections.mysql.database', 'summit_alumni_db'),
                'created_at' => now()->toIso8601String(),
                'version' => '1.0.0',
            ],
            'tables' => [
                'faculties' => Faculty::all()->toArray(),
                'departments' => Department::all()->toArray(),
                'graduate_records' => GraduateRecord::all()->toArray(),
                'users' => User::all()->makeHidden(['password', 'remember_token'])->toArray(),
                'profiles' => Profile::all()->toArray(),
                'privacy_settings' => PrivacySetting::all()->toArray(),
                'manual_verifications' => ManualVerification::all()->toArray(),
                'contact_inquiries' => ContactInquiry::all()->toArray(),
                'audit_logs' => AuditLog::all()->toArray(),
            ],
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        File::put($filepath, $json);

        $sizeKb = round(File::size($filepath) / 1024, 2);

        AuditLog::create([
            'user_id' => null,
            'action' => 'database_backup_created',
            'description' => "Automated backup generated: {$filename} ({$sizeKb} KB).",
            'ip_address' => '127.0.0.1',
            'created_at' => now(),
        ]);

        $this->info("Backup created successfully: {$filename} ({$sizeKb} KB)");
        $this->line("Saved to: {$filepath}");

        return Command::SUCCESS;
    }
}
