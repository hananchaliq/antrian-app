<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QueueController extends Controller
{
   public function index(): View
   {
      return view('queue.index');
   }

   /**
    * Endpoint JSON untuk pembaruan data realtime di sisi pengguna.
    */
   public function status(): JsonResponse
   {
      $today = now()->toDateString();

      $current = Queue::where('queue_date', $today)
         ->where('status', 'dilayani')
         ->orderByDesc('queue_number')
         ->first();

      $waitingList = Queue::where('queue_date', $today)
         ->where('status', 'menunggu')
         ->orderBy('queue_number')
         ->get(['id', 'queue_number', 'status', 'created_at']);

      return response()->json([
         'current' => $current,
         'waiting_list' => $waitingList,
         'waiting_count' => $waitingList->count(),
         'updated_at' => now()->format('H:i:s'),
      ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
   }

   /**
    * Pengunjung mengambil nomor antrian baru.
    */
   public function store(): JsonResponse
   {
      $today = now()->toDateString();

      $queue = DB::transaction(function () use ($today) {
         $lastQueue = Queue::where('queue_date', $today)
            ->orderByDesc('queue_number')
            ->lockForUpdate()
            ->first();

         $lastNumber = $lastQueue ? $lastQueue->queue_number : 0;

         return Queue::create([
            'queue_number' => $lastNumber + 1,
            'queue_date' => $today,
            'status' => 'menunggu',
         ]);
      });

      return response()->json([
         'message' => 'Nomor antrian berhasil dibuat.',
         'queue' => $queue,
      ], 201);
   }

   /**
    * Halaman dashboard panel admin (Data Hari Ini & Data Riwayat).
    */
   public function dashboard(): View
   {
      $today = now()->toDateString();

      $current = Queue::where('queue_date', $today)
         ->where('status', 'dilayani')
         ->first();

      $queues = Queue::with('admin')
         ->where('queue_date', $today)
         ->orderBy('queue_number')
         ->get();

      $historyQueues = Queue::with('admin')
         ->where('queue_date', '<', $today)
         ->orderByDesc('queue_date')
         ->orderByDesc('queue_number')
         ->limit(150)
         ->get();

      return view('admin.dashboard', compact('current', 'queues', 'historyQueues'));
   }

   /**
    * Navigasi Next: Selesaikan antrian aktif dan panggil urutan berikutnya.
    */
   public function next(): RedirectResponse
   {
      $today = now()->toDateString();

      DB::transaction(function () use ($today) {
         Queue::where('queue_date', $today)
            ->where('status', 'dilayani')
            ->update(['status' => 'selesai']);

         $nextQueue = Queue::where('queue_date', $today)
            ->where('status', 'menunggu')
            ->orderBy('queue_number')
            ->lockForUpdate()
            ->first();

         if ($nextQueue) {
            $nextQueue->update([
               'status' => 'dilayani',
               'served_by' => Auth::id(),
               'called_at' => now(),
            ]);
         }
      });

      return back()->with('status', 'Berhasil memanggil antrian berikutnya.');
   }

   /**
    * Navigasi Prev: Kembalikan antrian aktif ke menunggu, dan aktifkan lagi nomor selesai sebelumnya.
    */
   public function prev(): RedirectResponse
   {
      $today = now()->toDateString();

      DB::transaction(function () use ($today) {
         $current = Queue::where('queue_date', $today)
            ->where('status', 'dilayani')
            ->lockForUpdate()
            ->first();

         if ($current) {
            $previous = Queue::where('queue_date', $today)
               ->where('status', 'selesai')
               ->where('queue_number', '<', $current->queue_number)
               ->orderByDesc('queue_number')
               ->lockForUpdate()
               ->first();

            if ($previous) {
               $current->update([
                  'status' => 'menunggu',
                  'called_at' => null,
               ]);

               $previous->update([
                  'status' => 'dilayani',
                  'served_by' => Auth::id(),
                  'called_at' => now(),
               ]);
            }
         } else {
            $lastCompleted = Queue::where('queue_date', $today)
               ->where('status', 'selesai')
               ->orderByDesc('queue_number')
               ->lockForUpdate()
               ->first();

            if ($lastCompleted) {
               $lastCompleted->update([
                  'status' => 'dilayani',
                  'served_by' => Auth::id(),
                  'called_at' => now(),
               ]);
            }
         }
      });

      return back()->with('status', 'Berhasil kembali ke antrian sebelumnya.');
   }
}