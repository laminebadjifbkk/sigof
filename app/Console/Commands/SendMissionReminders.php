<?php

namespace App\Console\Commands;

use App\Mail\MissionReminderMail;
use App\Models\ParcMission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMissionReminders extends Command
{
    protected $signature = 'missions:send-reminders';
    protected $description = 'Envoi des rappels de mission';

    public function handle()
    {
        $now = now();

        $missions = ParcMission::with('employees')
            ->whereDate('date_depart', '>=', $now->toDateString())
            ->get();

        foreach ($missions as $mission) {

            $diffInHours = $now->diffInHours($mission->date_depart, false);

            if ($diffInHours === 48) {
                $this->sendMail($mission, 'J-2');
            }

            if ($diffInHours === 24) {
                $this->sendMail($mission, 'J-1');
            }

            if (
                $now->isSameDay($mission->date_depart)
                && $now->format('H:i') === '08:00'
            ) {
                $this->sendMail($mission, 'Jour J');
            }
        }
    }

    private function sendMail($mission, $type)
    {
        foreach ($mission->employees as $employee) {
            if ($employee->email) {
                Mail::to($employee->email)
                    ->send(new MissionReminderMail($mission, $type, $employee));
            }
        }
    }
}
