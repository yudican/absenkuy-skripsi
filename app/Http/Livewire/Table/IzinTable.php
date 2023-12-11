<?php

namespace App\Http\Livewire\Table;

use App\Http\Livewire\LivewireDatatable;
use App\Models\Izin;
use App\Models\Lokasi;
use Mediconesystems\LivewireDatatables\Column;

class IzinTable extends LivewireDatatable
{
  public $sort = 'tanggal_izin|asc';
  public function builder()
  {
    return Izin::query();
  }

  protected $listeners = ['refreshTable'];

  public function columns()
  {
    $data_column = [
      Column::name('user.karyawan.nama_karyawan')->label('Nama Karyawan')->searchable(),
      Column::name('tanggal_izin')->label('Tanggal Izin'),
      Column::name('alasan_izin')->label('Alasan Izin'),
      Column::callback(['status_pengajuan'], function ($status_pengajuan) {
        switch ($status_pengajuan) {
          case '0':
            return 'Menunggu Persetujuan';
          case '1':
            return 'Disetujui';
          case '2':
            return 'Ditolak';
          default:
            return 'Menunggu Persetujuan';
        }
      })->label(__('Status Pengajuan')),

      Column::callback(['id'], function ($id) {
        return view('livewire.components.action-button', [
          'id' => $id,
          'segment' => request()->segment(1)
        ]);
      })->label(__('Aksi')),
    ];

    return $data_column;
  }

  public function refreshTable()
  {
    $this->emit('refreshLivewireDatatable');
  }


  public function getDataById($id)
  {
    $this->emit('getDataById', $id);
  }

  public function getId($id)
  {
    $this->emit('getId', $id);
  }
}
