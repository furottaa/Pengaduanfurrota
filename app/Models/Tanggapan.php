<?php 
namespace App\Models;

use CodeIgniter\Model;

class tanggapan extends Model{
    protected $table = 'tb_tanggapan';
    // Uncomment below if you want add
    protected $primaryKey = 'id_tanggapan';
    protected $allowedFields = ['id_pengaduan','tgl_tanggapan','tanggapan','id_petugas','deleted_at'];
    protected $useSoftDeleted = true;
    protected $deletedFields = 'Deleted_at';
}