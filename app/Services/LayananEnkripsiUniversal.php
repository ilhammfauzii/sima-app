<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LayananEnkripsiUniversal
{
    /**
     * Enkripsi file dan simpan ke storage
     */
    public function enkripsiFile($file, $jenisDokumen)
    {
        $start = microtime(true);

        $kunciEnkripsi = $this->generateKunciEnkripsi();

        $kontenFile = file_get_contents($file->getRealPath());

        $kontenTerenkripsi = $this->prosesEnkripsi($kontenFile, $kunciEnkripsi);

        $namaFileTerenkripsi = $this->generateNamaFileTerenkripsi($file, $jenisDokumen);

        $pathTerenkripsi = 'file_terenkripsi/' . $namaFileTerenkripsi;
        Storage::put($pathTerenkripsi, $kontenTerenkripsi);
        
        $end = microtime(true);
        $durasi = $end - $start;

        Log::info('Waktu Enkripsi: ' . $durasi . ' detik');

        return [
            'kunci_enkripsi' => $kunciEnkripsi,
            'path_file_terenkripsi' => $pathTerenkripsi,
            'nama_file_asli' => $file->getClientOriginalName(),
            'ukuran_file' => $file->getSize(),
            'tipe_file' => $file->getMimeType()
        ];
    }
    
    /**
     * Dekripsi file
     */
    public function dekripsiFile($pathFileTerenkripsi, $kunciEnkripsi)
    {
        $start = microtime(true);

        if (!Storage::exists($pathFileTerenkripsi)) {
            throw new \Exception("File terenkripsi tidak ditemukan.");
        }

        $kontenTerenkripsi = Storage::get($pathFileTerenkripsi);

        $kontenTerdekripsi = $this->prosesDekripsi($kontenTerenkripsi, $kunciEnkripsi);

        $end = microtime(true);
        $durasi = $end - $start;

        Log::info('Waktu Dekripsi: ' . $durasi . ' detik');

        return $kontenTerdekripsi;
    }
    
    /**
     * Proses enkripsi AES-256
     */
    private function prosesEnkripsi($data, $kunci)
    {
        $method = 'aes-256-cbc';
        $ivLength = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $dataTerenkripsi = openssl_encrypt(
            $data,
            $method,
            $kunci,
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode($iv . $dataTerenkripsi);
    }
    
    /**
     * Proses dekripsi AES-256
     */
    private function prosesDekripsi($dataTerenkripsi, $kunci)
    {
        $method = 'aes-256-cbc';
        $ivLength = openssl_cipher_iv_length($method);
        
        $data = base64_decode($dataTerenkripsi);
        $iv = substr($data, 0, $ivLength);
        $dataTerenkripsi = substr($data, $ivLength);
        
        $dataTerdekripsi = openssl_decrypt(
            $dataTerenkripsi,
            $method,
            $kunci,
            OPENSSL_RAW_DATA,
            $iv
        );
        
        if ($dataTerdekripsi === false) {
            throw new \Exception("Gagal mendekripsi file. Kunci mungkin salah.");
        }
        
        return $dataTerdekripsi;
    }
    
    /**
     * Generate kunci enkripsi yang mudah dibaca
     */
    private function generateKunciEnkripsi()
    {
        $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $kunci = '';
        
        for ($i = 0; $i < 16; $i++) {
            $kunci .= $karakter[rand(0, strlen($karakter) - 1)];
        }
        
        return $kunci;
    }
    
    /**
     * Generate nama file terenkripsi
     */
    private function generateNamaFileTerenkripsi($file, $jenisDokumen)
    {
        $ekstensi = $file->getClientOriginalExtension();
        $timestamp = now()->format('Ymd_His');
        $random = Str::random(8);
        
        return "{$jenisDokumen}_{$timestamp}_{$random}.{$ekstensi}.enc";
    }
    
    /**
     * Validasi kunci enkripsi
     */
    public function validasiKunci($kunci)
    {
        return preg_match('/^[A-Z2-9]{16}$/', $kunci);
    }
}