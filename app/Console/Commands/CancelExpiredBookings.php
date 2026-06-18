<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredBookings extends Command
{
    protected $signature = 'booking:cancel-expired';

    protected $description = 'Auto-cancel booking yang belum dibayar dan sudah melewati batas waktu pembayaran';

    public function handle(): void
    {
        $expiredBookings = Booking::whereIn('status', ['menunggu_approval', 'menunggu'])
            ->whereNotNull('bayar_sebelum')
            ->where('bayar_sebelum', '<', Carbon::now())
            ->with('pembayaran')
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('Tidak ada booking yang kedaluwarsa.');
            return;
        }

        $count = 0;

        foreach ($expiredBookings as $booking) {
            DB::beginTransaction();
            try {
                // Kembalikan kuota promo jika ada
                if ($booking->pembayaran && $booking->pembayaran->id_promo) {
                    DB::table('promo')
                        ->where('id', $booking->pembayaran->id_promo)
                        ->increment('kuota');
                }

                // Update status pembayaran
                if ($booking->pembayaran) {
                    $booking->pembayaran->update(['status_pembayaran' => 'dibatalkan']);
                }

                // Update status booking
                $booking->update(['status' => 'dibatalkan']);

                DB::commit();
                $count++;

                Log::info("Booking #{$booking->id} dibatalkan otomatis karena batas waktu pembayaran terlampaui.");
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Gagal membatalkan booking #{$booking->id}: " . $e->getMessage());
            }
        }

        $this->info("Berhasil membatalkan {$count} booking yang kedaluwarsa.");
    }
}
