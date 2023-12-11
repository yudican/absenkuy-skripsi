<?php

namespace App\Http\Livewire;

use App\Models\Cuti as ModelsCuti;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cuti extends Component
{
  public $cuti_id;
  public $user_id;
  public $tanggal_cuti;
  public $lama_cuti;
  public $alasan_cuti;
  public $status_pengajuan;
  public $status_penolakan;

  public $form_active = false;
  public $form = false;
  public $update_mode = false;
  public $modal = true;

  protected $listeners = ['getDataById', 'getId'];
  public function render()
  {
    return view('livewire.cuti', [
      'items' => ModelsCuti::all(),
      'users' => User::where('level', 'karyawan')->get(),
    ]);
  }

  public function store()
  {
    $this->_validate();
    try {
      DB::beginTransaction();

      ModelsCuti::create([
        'user_id' => $this->user_id,
        'tanggal_cuti' => $this->tanggal_cuti,
        'alasan_cuti' => $this->alasan_cuti,
        'lama_cuti' => $this->lama_cuti,
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

    $izin = ModelsCuti::find($this->cuti_id);

    try {
      DB::beginTransaction();

      $izin->update([
        'user_id' => $this->user_id,
        'tanggal_cuti' => $this->tanggal_cuti,
        'alasan_cuti' => $this->alasan_cuti,
        'lama_cuti' => $this->lama_cuti,
        'status_pengajuan' => $this->status_pengajuan,
        'status_penolakan' => $this->status_penolakan,
      ]);

      DB::commit();
      $this->_reset();
      return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    } catch (Error $error) {
      DB::rollback();
      return $this->emit('showAlertError', ['msg' => 'Data Gagal Diupdate']);
    }
  }

  public function delete()
  {
    try {
      DB::beginTransaction();
      $izin = ModelsCuti::find($this->cuti_id);
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

    if (!$this->update_mode) {
      $rule['user_id'] = 'required';
      if ($this->status_pengajuan == 2) {
        $rule['status_penolakan'] = 'required';
      }
      $rule['status_pengajuan'] = 'required';
    }


    return $this->validate($rule);
  }

  public function getDataById($cuti_id)
  {
    $izin = ModelsCuti::find($cuti_id);
    $this->cuti_id = $izin->id;
    $this->user_id = $izin->user_id;
    $this->tanggal_cuti = $izin->tanggal_cuti;
    $this->alasan_cuti = $izin->alasan_cuti;
    $this->lama_cuti = $izin->lama_cuti;
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

  public function getId($cuti_id)
  {
    $izin = ModelsCuti::find($cuti_id);
    $this->cuti_id = $izin->id;
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
    $this->cuti_id = null;
    $this->user_id = null;
    $this->tanggal_cuti = null;
    $this->alasan_cuti = null;
    $this->lama_cuti = null;
    $this->status_pengajuan = null;
    $this->status_penolakan = null;
    $this->form = false;
    $this->form_active = false;
    $this->update_mode = false;
    $this->modal = true;
  }
}
