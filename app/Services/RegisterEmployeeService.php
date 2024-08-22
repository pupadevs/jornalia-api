<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\SendRegistrNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterEmployeeService{

    public function execute(string $name, string $email, string $password, float $normalHourlyRate, float $overtimeHourlyRate, float $nightHourlyRate, float $holidayHourlyRate, float $irpf,): void
    {
        $employee = [
            'name' => $name,
            'normal_hourly_rate' => $normalHourlyRate,
            'overtime_hourly_rate' => $overtimeHourlyRate,
            'night_hourly_rate' => $nightHourlyRate,
            'holiday_hourly_rate' => $holidayHourlyRate,
            'irpf' => $irpf
        ];


        DB::transaction(function () use ($name, $email, $password, $employee) {
            $user = User::create([
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            $user->assignRole('employee');
            $user->employee()->create($employee);
         SendRegistrNotification::dispatch($user);

        });
    
          
      

      
       
    }
}