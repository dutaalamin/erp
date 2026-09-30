<?php

namespace App\Console\Commands;

use App\Models\SalesInvoice;
use App\Models\User;
use App\Notifications\InvoiceOverdue;
use Illuminate\Console\Command;

class CheckOverdueInvoices extends Command
{
    protected $signature = 'erp:check-overdue-invoices';
    protected $description = 'Check for overdue invoices and send notifications';

    public function handle(): void
    {
        $overdueInvoices = SalesInvoice::query()
            ->whereIn('status', ['confirmed', 'partial_paid'])
            ->where('due_date', '<', now()->toDateString())
            ->get();

        if ($overdueInvoices->isEmpty()) {
            $this->info('No overdue invoices found.');
            return;
        }

        // Notify all admin/manager users
        $admins = User::role(['Super Admin', 'Admin', 'Sales Manager'])->get();

        foreach ($overdueInvoices as $invoice) {
            // Update status to overdue
            if ($invoice->status !== 'overdue') {
                $invoice->update(['status' => 'overdue']);
            }

            foreach ($admins as $admin) {
                $admin->notify(new InvoiceOverdue($invoice));
            }
        }

        $this->info("Found {$overdueInvoices->count()} overdue invoices. Notifications sent.");
    }
}
