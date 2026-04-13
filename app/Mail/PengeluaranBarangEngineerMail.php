<?php

namespace App\Mail;

use App\Models\Pengeluaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PengeluaranBarangEngineerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengeluaran;
    public $pic;

    public function __construct(Pengeluaran $pengeluaran)
    {
        $this->pengeluaran = $pengeluaran;
        $this->pic = $pengeluaran->pic;
    }

    public function build()
    {
        return $this->subject('Notifikasi Pengeluaran Barang Engineer')->view('emails.pengeluaran_barang_engineer')->with([
                'pengeluaran' => $this->pengeluaran,
                'pic'         => $this->pic,
            ]);
    }
}