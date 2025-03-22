<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('formatTanggal')) {
    function formatTanggal($tanggal)
    {
        $hariIndo = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];
        $bulanIndo = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        $timestamp = strtotime($tanggal);
        $hari = $hariIndo[date('w', $timestamp)];
        $tanggal = date('j', $timestamp);
        $bulan = $bulanIndo[date('n', $timestamp)];
        $tahun = date('Y', $timestamp);

        return "$hari, $tanggal $bulan $tahun";
    }
}
