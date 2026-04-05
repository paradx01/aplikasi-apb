<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MedicationReminder;
use App\Models\User;
use App\Models\Product;
use App\Notifications\MedicationReminderNotification;

class SendMedicationReminders extends Command
{
    protected $signature = 'reminder:send-medication';
    protected $description = 'Kirim push notification pengingat minum obat sesuai jadwal dan user di medication_reminders';

    public function handle()
    {
        $now = now();
        $reminders = MedicationReminder::where('schedule_time', $now->format('H:i'))
            ->where('start_date', '<=', $now->toDateString())
            ->where('end_date', '>=', $now->toDateString())
            ->where('status', 'active')
            ->get();

        foreach ($reminders as $reminder) {
            $user = User::find($reminder->user_id);
            $product = Product::find($reminder->product_id);

            if ($user && $user->pushSubscriptions()->count() && $product) {
                $title = 'Jadwal Minum Obat!';
                $body = 'Waktunya minum ' . $product->name .
                        ' dosis: ' . $reminder->dosage .
                        ' pada jam ' . $reminder->schedule_time .
                        '. Jangan lupa sesuai jadwal (' . $reminder->frequency . 'x sehari)';
                
                        $user->notify(new MedicationReminderNotification($title, $body));
            }
        }

        $this->info('Push medication reminders sent: '.count($reminders));
    }
}

