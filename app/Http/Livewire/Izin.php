<?php

namespace App\Http\Livewire;

use App\Models\Izin as ModelsIzin;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Izin extends Component
{
  public $izin_id;
  public $user_id;
  public $tanggal_izin;
  public $alasan_izin;
  public $status_pengajuan;
  public $status_penolakan;

  public $form_active = false;
  public $form = false;
  public $update_mode = false;
  public $modal = true;

  protected $listeners = ['getDataById', 'getId'];

  public function render()
  {
    return view('livewire.izin', [
      'items' => ModelsIzin::all(),
      'users' => User::where('level', 'karyawan')->get(),
    ]);
  }

  public function store()
  {
    $this->_validate();
    try {
      DB::beginTransaction();

      ModelsIzin::create([
        'user_id' => $this->user_id,
        'tanggal_izin' => $this->tanggal_izin,
        'alasan_izin' => $this->alasan_izin,
        'status_pengajuan' => $this->status_pengajuan,
        'status_penolakan' => $this->status_penolakan,
      ]);

      DB::commit();

      $this->_reset();
      return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    } catch (Error $error) {
      DB::rollback();
      return $this->emit('showAlertError', ['msg' => 'Data Gagal Disimpan']);
    }
  }

  public function update()
  {
    $this->_validate();

    $izin = ModelsIzin::find($this->izin_id);

    try {
      DB::beginTransaction();

      $izin->update([
        'user_id' => $this->user_id,
        'tanggal_izin' => $this->tanggal_izin,
        'alasan_izin' => $this->alasan_izin,
        'status_pengajuan' => $this->status_pengajuan,
        'status_penolakan' => $this->status_penolakan,
      ]);

      DB::commit();
      $this->_reset();
      return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    } catch (Error $error) {
      DB::rollback();
      dd($error->getMessage());
      return $this->emit('showAlertError', ['msg' => 'Data Gagal Diupdate']);
    }
  }

  public function delete()
  {
    try {
      DB::beginTransaction();
      $izin = ModelsIzin::find($this->izin_id);
      $izin->delete();
      DB::commit();
      $this->_reset();
      return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    } catch (Error $error) {
      DB::rollback();
      return $this->emit('showAlertError', ['msg' => 'Data Gagal Dihapus']);
    }
  }

  public function _validate()
  {
    $rule = [];

    $rule['user_id'] = 'required';
    if ($this->update_mode) {
      if ($this->status_pengajuan == 2) {
        $rule['status_penolakan'] = 'required';
      }
      $rule['status_pengajuan'] = 'required';
    }

    return $this->validate($rule);
  }

  public function getDataById($izin_id)
  {
    $izin = ModelsIzin::find($izin_id);
    $this->izin_id = $izin->id;
    $this->user_id = $izin->user_id;
    $this->tanggal_izin = $izin->tanggal_izin;
    $this->alasan_izin = $izin->alasan_izin;
    $this->status_pengajuan = $izin->status_pengajuan;
    $this->status_penolakan = $izin->status_penolakan;

    if ($this->form) {
      $this->form_active = true;
      $this->emit('loadForm');
    }
    if ($this->modal) {
      $this->emit('showModal');
    }
    $this->update_mode = true;
  }

  public function getId($izin_id)
  {
    $izin = ModelsIzin::find($izin_id);
    $this->izin_id = $izin->id;
  }

  public function toggleForm($form)
  {
    $this->form_active = $form;
    $this->emit('loadForm');
  }

  public function showModal()
  {
    $this->emit('showModal');
  }

  public function _reset()
  {
    $this->emit('refreshTable');
    $this->emit('closeModal');
    $this->izin_id = null;
    $this->user_id = null;
    $this->tanggal_izin = null;
    $this->alasan_izin = null;
    $this->status_pengajuan = null;
    $this->status_penolakan = null;
    $this->form = false;
    $this->form_active = false;
    $this->update_mode = false;
    $this->modal = true;
  }
}
