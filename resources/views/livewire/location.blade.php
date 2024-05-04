<div class="page-inner">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">
            <a href="{{route('dashboard')}}">
              <span><i class="fas fa-arrow-left mr-3"></i>Data Lokasi</span>
            </a>
            <div class="pull-right">
              @if ($form_active)
              <button class="btn btn-danger btn-sm" wire:click="toggleForm(false)"><i class="fas fa-times"></i>
                Batal</button>
              @else
              <button class="btn btn-primary btn-sm" wire:click="{{$modal ? 'showModal' : 'toggleForm(true)'}}"><i class="fas fa-plus"></i>
                Tambah Data Baru</button>
              @endif
            </div>
          </h4>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      @if ($form_active)
      <div class="card">
        <div class="card-body">
          <x-text-field type="text" name="nama_lokasi" label="Nama Lokasi" id="pac-input" />
          <x-text-field type="text" name="latitude" label="latitude" readonly />
          <x-text-field type="text" name="longitude" label="longitude" readonly />
          <div wire:ignore class="form-group">
            <div id='map' class="w-100" style='height: 500px;'></div>
          </div>
          <div class="form-group">
            <button class="btn btn-primary pull-right" wire:click="{{$update_mode ? 'update' : 'store'}}">Simpan</button>
          </div>

        </div>
      </div>
      @else
      <livewire:table.lokasi-table />
      @endif
    </div>

    {{-- Modal confirm --}}
    <div id="confirm-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
      <div class="modal-dialog" permission="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="my-modal-title">Konfirmasi Hapus</h5>
          </div>
          <div class="modal-body">
            <p>Apakah anda yakin hapus data ini.?</p>
          </div>
          <div class="modal-footer">
            <button type="submit" wire:click='delete' class="btn btn-danger btn-sm"><i class="fa fa-check pr-2"></i>Ya,
              Hapus</button>
            <button class="btn btn-primary btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Batal</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCn6jmu8Wwant4jx0MvaOi6oEKT1H7bLuc&libraries=places" async defer></script>

  <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>

  <script>
    document.addEventListener('livewire:load', function(e) {
            var latitude = {{$latitude}}
            var longitude = {{$longitude}}
            window.livewire.on('loadForm', (data) => {
                initMap(data)
            });

            window.livewire.on('closeModal', (data) => {
                $('#confirm-modal').modal('hide')
            });

            async function getAddress(coordinate) {
              try {
                const response = await fetch(
                  `https://maps.googleapis.com/maps/api/geocode/json?latlng=${coordinate.latitude},${coordinate.longitude}&key=AIzaSyCn6jmu8Wwant4jx0MvaOi6oEKT1H7bLuc`,
                );
                const data = await response.json();
                if (data.status === 'OK') {
                  const address = data.results[0].formatted_address;
                  @this.set('nama_lokasi', address);
                } else {
                  console.error('Error retrieving address:', data.status);
                }
              } catch (error) {
                console.error('Error retrieving address:', error);
              }
            }

            function initMap(data) {
                const map = new google.maps.Map(document.getElementById('map'), {
                    center: { lat: parseFloat(data?.latitude || latitude), lng: parseFloat(data?.longitude || longitude) },
                    zoom: 12,
                });

                const input = document.getElementById('pac-input');
                const searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                map.addListener('bounds_changed', function () {
                    searchBox.setBounds(map.getBounds());
                });

                let marker = new google.maps.Marker({
                    position: { lat: parseFloat(data?.latitude || latitude), lng: parseFloat(data?.longitude || longitude) },
                    map: map,
                    draggable: true,
                });

                marker.addListener('dragend', function (event) {
                    const position = event.latLng;
                        @this.set('latitude', position.lat());
                        @this.set('longitude', position.lng());

                        getAddress({longitude:position.lng(),latitude:position.lat()})
                });

                searchBox.addListener('places_changed', function () {
                    const places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }

                    const bounds = new google.maps.LatLngBounds();
                    places.forEach(function (place) {
                        if (!place.geometry) {
                            console.log("Returned place contains no geometry");
                            return;
                        }

                        if (place.geometry.viewport) {
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });
            }
        })
  </script>
  @endpush
</div>