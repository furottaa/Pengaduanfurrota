<?php 
namespace App\Models;

use CodeIgniter\Model;

class Petugas extends Model{
    protected $table = 'tb_petugas';
    // Uncomment below if you want add
    protected $primaryKey = 'id_petugas';
    protected $allowedFields = ['nama_petugas','username','password','telp','level','deleted_at'];
    protected $useSoftDeleted = true;
    protected $deletedFields = 'Deleted_at';
}