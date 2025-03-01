@extends('admin.body.adminmaster')

@section('admin')

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0 d-flex">
                        <h2>USDT QR Details</h2>
                    </div>
                </div>
                <div class="table_section padding_infor_info">
                    <div class="table-responsive-sm">
                        <table id="example" class="table table-striped" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>QR Image</th>
                                    <th>Wallet Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($data)
                                <tr>
                                    <td>{{ $data->id }}</td>
                                    <td>
                                        <img id="qrImage" src="{{ $data->qr_image }}" alt="USDT QR" width="100">
                                    </td>
                                    <td id="walletAddress">{{ $data->usdt_wallet_address }}</td>
                                    <td>
                                        <i class="fa fa-edit mt-1" data-toggle="modal" data-target="#editModal" 
                                            onclick="setModalData('{{ $data->id }}', '{{ asset($data->qr_image) }}', '{{ $data->usdt_wallet_address }}')"
                                            style="font-size:30px; cursor:pointer;">
                                        </i>
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="4" class="text-center">No data available</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit USDT Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('update.usdtqr') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="editId">
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="modalQrImage" src="" alt="QR Image" width="150">
                    </div>
                    <div class="form-group">
                        <label for="wallet_address">Wallet Address</label>
                        <input type="text" class="form-control" id="editWalletAddress" name="wallet_address" required>
                    </div>
                    <div class="form-group">
                        <label for="qr_image">Upload New QR Image</label>
                        <input type="file" class="form-control-file" id="qr_image" name="qr_image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setModalData(id, imageUrl, walletAddress) {
    document.getElementById("editId").value = id;
    document.getElementById("modalQrImage").src = imageUrl;
    document.getElementById("editWalletAddress").value = walletAddress;
}
</script>

@endsection
