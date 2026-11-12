<?php

namespace Database\Seeders;

use App\Models\Queue;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
   public function run(): void
   {
      $admin = User::create([
         'name' => 'Budi Santoso',
         'email' => 'budi@morbis.id',
         'password' => Hash::make('password123'),
      ]);

      User::create([
         'name' => 'Siti Aminah',
         'email' => 'siti@morbis.id',
         'password' => Hash::make('password123'),
      ]);

      $today = now()->toDateString();

      Queue::create([
         'queue_number' => 1,
         'queue_date' => $today,
         'status' => 'selesai',
         'served_by' => $admin->id,
         'called_at' => now()->subMinutes(15),
      ]);

      Queue::create([
         'queue_number' => 2,
         'queue_date' => $today,
         'status' => 'dilayani',
         'served_by' => $admin->id,
         'called_at' => now()->subMinutes(5),
      ]);

      Queue::create([
         'queue_number' => 3,
         'queue_date' => $today,
         'status' => 'menunggu',
      ]);

      Queue::create([
         'queue_number' => 4,
         'queue_date' => $today,
         'status' => 'menunggu',
      ]);
   }
}