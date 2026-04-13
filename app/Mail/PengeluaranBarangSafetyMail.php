<?php

namespace App\Mail;

use App\Models\PengeluaranSafety;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengeluaranBarangSafetyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengeluaran;
    public $pic;

    public function __construct(PengeluaranSafety $pengeluaran)
    {
        $this->pengeluaran = $pengeluaran;
        $this->pic = $pengeluaran->pic;
    }

    public function build()
    {
        return $this->subject('Notifikasi Pengeluaran Barang Safety')->view('emails.pengeluaran_barang_safety')->with([
                'pengeluaran' => $this->pengeluaran,
                'pic'         => $this->pic,
            ]);
    }
}