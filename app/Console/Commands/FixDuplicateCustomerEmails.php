<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;

class FixDuplicateCustomerEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:fix-duplicate-emails {--dry-run : Only show what would change without saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalize empty customer emails and append an incrementing suffix to duplicated ones so email can become unique';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');

        // empty string emails must become null, otherwise they'd collide under a unique index
        $emptyCount = Customer::where('email', '')->count();
        if ($emptyCount > 0) {
            $this->line("Normalizing {$emptyCount} empty email(s) to null.");
            if (!$dryRun) {
                Customer::where('email', '')->update(['email' => null]);
            }
        }

        $duplicatedEmails = Customer::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->select('email')
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('email');

        if ($duplicatedEmails->isEmpty()) {
            $this->info('No duplicated emails found.');
            return self::SUCCESS;
        }

        foreach ($duplicatedEmails as $email) {
            $customers = Customer::where('email', $email)->orderBy('id')->get();

            // keep the oldest record untouched, suffix the rest
            $customers->shift();

            $suffix = 1;
            foreach ($customers as $customer) {
                $newEmail = $this->buildSuffixedEmail($email, $suffix);

                while (Customer::where('email', $newEmail)->where('id', '!=', $customer->id)->exists()) {
                    $suffix++;
                    $newEmail = $this->buildSuffixedEmail($email, $suffix);
                }

                $this->line("Customer #{$customer->id}: {$email} -> {$newEmail}");

                if (!$dryRun) {
                    $customer->update(['email' => $newEmail]);
                }

                $suffix++;
            }
        }

        $this->info($dryRun ? 'Dry run complete, no changes saved.' : 'Duplicated emails fixed.');

        return self::SUCCESS;
    }

    private function buildSuffixedEmail(string $email, int $suffix): string
    {
        if (str_contains($email, '@')) {
            [$local, $domain] = explode('@', $email, 2);

            return "{$local}+{$suffix}@{$domain}";
        }

        return "{$email}+{$suffix}";
    }
}
