@extends('layouts.default')

@include('plugins.datatables')
@include('plugins.sweetalert2')

@section('content')
<h3 class="heading_b uk-margin-bottom">Kategori Kebermanfaatan</h3>
<div class="md-card uk-margin-medium-bottom">
    <div class="md-card-content">
        <div class="uk-grid">
            <div class="uk-width-1-1">
                <button type="button" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light md-btn-icon" data-uk-modal="{target:'#reason-create'}"><i class="uk-icon-plus"></i> Buat Kategori</button>
            </div>
        </div>
        <br>
        <div class="uk-overflow-container">
            <table class="uk-table uk-table-hover" id="reason-table">
                <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th>Nama</th>
                    <th>Urutan Ke</th>
                    <th>Jumlah Unduhan</th>
                    <th>Dibuat pada</th>
                    <th style="width: 12%">Aksi</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="uk-modal" id="reason-create">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Formulir Kategori</h3>
        </div>
        
        <form action="{{ route('reference.reason.create.submit') }}" method="POST">
            @csrf
            <div class="uk-form-row">
                <label>Urutan Ke</label>
                <input type="text" class="md-input" name="order" />
            </div>
            <div class="uk-form-row">
                <label>Kategori Kebermanfaatan <span class="uk-text-danger">*</span></label>
                <input type="text" class="md-input" name="name" required />
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-default uk-modal-close">Tutup</button>
                <button type="submit" class="md-btn md-btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="uk-modal" id="reason-update">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Formulir Perubahan Kategori</h3>
        </div>
        
        <form action="{{ route('reference.reason.update.submit') }}" method="POST">
            <span class="form"></span>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-default uk-modal-close">Tutup</button>
                <button type="submit" class="md-btn md-btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        var reason_table

        $(function () {
            reason_table = $('#reason-table').DataTable({
                language: defaultLang,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('reference.reason.data') }}"
                },
                columnDefs: [
                    {
                        targets: [0, 3, 5],
                        searchable: false,
                        orderable: false
                    },
                    {
                        targets: [6],
                        visible: false
                    }
                ],
                order: [
                    [6, 'desc']
                ], 
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'order',
                        name: 'order',
                        defaultContent: '-'
                    },
                    {
                        data: 'downloads_count',
                        name: 'downloads_count'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    },
                    {
                        data: 'order',
                        name: 'order'
                    }
                ]
            })
        })

        function updateReason(id) {
            $.get("{{ route('reference.reason.update.form') }}", {
                id: id
            }).done(function (result) {
                $('#reason-update').find('.form').html(result)
                UIkit.modal('#reason-update').show()
            })
        }

        function deleteReason(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Ingin menghapus tujuan ini",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.value) {
                    $.post("{{ route('reference.reason.delete.submit') }}", {
                        _token: "{{ csrf_token() }}",
                        _method: 'DELETE',
                        id: id
                    }).done(function (result) {
                        Swal.fire(
                            'Berhasil!',
                            result,
                            'success'
                        )

                        reason_table.draw(false)
                    })
                }
            })
        }
    </script>
@endpush