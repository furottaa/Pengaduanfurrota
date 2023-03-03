<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Petugas;

use App\Models\Masyarakat;

class LoginController extends BaseController{
    protected $masyarakats;
    
    function __construct()
    {
        $this->masyarakats = new Masyarakat;
    } 
    public function index()
    {
        return view('login_view');
    }
    public function register()
    {
        return view('register_view');
    }
    public function saveRegister()
    {
        $ceknik = $this->masyarakats->where('nik',$this->request->getPost('nik'))->findAll();
        // dd($ceknik);
        if ($ceknik==null)
        {
            $data=array(
                'nik'=> $this->request->getPost('nik'), 
                'nama'=> $this->request->getPost('nama'), 
                'password'=>password_hash ($this->request->getPost('password')."", PASSWORD_DEFAULT),
                'telp'=> $this->request->getPost('telp'), 
            );
            $this->masyarakats->insert($data);
            return redirect('login');
        }
        else{
            return redirect('register')->with('error', lang('Nik sudah ada'));
        }
    }
    public function login()
    {
        $masy= new Masyarakat();
        $petugas= new Petugas();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password')."";
        $cekPetugas = $petugas->where(['username'=>$username])->first();
        // dd ("asa");
        $cekmasy = $masy->where(['username'=>$username])->first();
        if (!($cekmasy == null) && ! ($cekPetugas == null)) 
        {
            return redirect('login')->with("error",lang('Username tidak ditemukan!'));
        }else
        {
            if ($cekmasy)
            {
                if (password_verify($password, $cekmasy['password'])) {
                    session()->set([
                        'id_masyarakat' =>$cekmasy['id_masyarakat'],
                        'nama' =>$cekmasy['nama'],
                        // 'level' =>$cekmasy['level'],
                        // 'hak_akses' =>'masyarakat',
                        'log_in'=>true,
                        'level' => 'masyarakat',
                    ]);
                    return redirect('/');
                }else
                {
                     return redirect('login')->with("error",lang('Password masyarakat salah!'));
                }
            }
            if($cekPetugas)
            {
                if (password_verify($password, $cekPetugas['password'])) {
                    session()->set([
                        'id_petugas' =>$cekPetugas['id_petugas'],
                        'nama' =>$cekPetugas['nama_petugas'],
                        'log_in'=>true,
                        'level'=>$cekPetugas['level'],
                    ]);
                    return redirect('/');
                }
                else
                {
                    return redirect('login')->with("error", lang('Password petugas salah!'));
                }
            }
        }
    }

    function logout(){
        session()->destroy();
        return redirect("login");
    }
    public function lihatProfil()
    {
        $petugass = new Petugas();
        $masyarakats = new Masyarakat();
        if(session('level')=='masyarakat'){
            $data['user'] = $masyarakats->where('nik',session('nik'))->findAll();
        }else{
            $data['user'] = $petugass->where('id_petugas',session('id_petugas'))->findAll(); 
        }
        return view('profil_view',$data);
    }



    public function editProfil(){
        $petugass = new Petugas();
        $masyarakats = new Masyarakat();
        $id = $this->request->getPost('id');
        $nama = $this->request->getPost('nama');
        $username = $this->request->getPost('username');
        $telp = $this->request->getPost('telp');
        $pass_old = $this->request->getPost('password_old');
        $pass_new = $this->request->getPost('password_new');

        if(session('level')=='masyarakat'){
            $datamasy = $masyarakats->where('id_masyarakat',$id)->first();
            if(empty($pass_old)){
                $data = [
                    'nama'=>$nama,
                    'username'=>$username,
                    'telp'=>$telp,
                ]; 
            }else{
                if(password_verify($pass_old,$datamasy['password'])){
                    $data = [
                        'nama'=>$nama,
                        'username'=>$username,
                        'telp'=>$telp,
                        'password'=>password_hash($pass_new,PASSWORD_DEFAULT)
                    ];
                }
            }
            $petugass->update($id,$data);
        }else{
            $datapetugas = $petugass->where('id_petugas',$id)->first();
            if(empty($pass_old)){
                $data = [
                        'nama'=>$nama,
                        'username'=>$username,
                        'telp'=>$telp,
                ];
            }else{
                if(password_verify($pass_old,$datapetugas['password'])){
                    $data =[
                        'nama_petugas'=>$nama,
                        'username'=>$username,
                        'telp'=>$telp,
                        'password'=>password_hash($pass_new,PASSWORD_DEFAULT)
                    ];
                }
            }
            $petugass->update($id,$data);
        }
        session()->setFlashdata('sukses','update profil berhasil');
        return redirect('profil');
    }

}