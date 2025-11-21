@extends('layouts/contentNavbarLayout')

@section('title', 'Travel Orders')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="card p-4">
  <div class="d-flex justify-content-between align-items-center mb-4">

    <h4 class="text-primary">Travel Orders</h4>
    <a href="{{ route('forms.travel.create') }}" class="btn btn-primary">New Travel Order</a>
  </div>
  <table id="travelTable" class="table table-bordered">
    <thead>
      <tr>
        <th>Reference No</th>
        <th>Employees</th>
        <th>Travel Dates</th>
        <th>Purposes</th>
        <th>Destinations</th>
        <th>Status</th>
        <th>Date Requested</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @php
      $groupedTravels = $travels->groupBy('travel_reference_number');
      @endphp

      @foreach($groupedTravels as $referenceNumber => $batch)
      <tr>
        <td>{{ $referenceNumber }}</td>
        <td>
          @foreach($batch as $travel)
          {{ $travel->employee->full_name ?? $travel->empid }}<br>
          @endforeach
        </td>
        <td>
          @foreach($batch as $travel)
          {{ \Carbon\Carbon::parse($travel->travel_date)->format('Y-m-d') }}<br>
          @endforeach
        </td>
        <td>
          @foreach($batch as $travel)
          {{ $travel->travel_purpose }}<br>
          @endforeach
        </td>
        <td>
          @foreach($batch as $travel)
          {{ $travel->travel_destination }}<br>
          @endforeach
        </td>
        <td>
          {{ $batch->pluck('status')->unique()->implode(', ') }}
        </td>
        <td>{{ \Carbon\Carbon::parse($batch->first()->date_requested)->format('Y-m-d H:i') }}</td>
        <td>
          <!-- Sign Button triggers signature type modal -->
          <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#signatureTypeModal_{{ $referenceNumber }}">
            Sign
          </button>

          <a href="{{ route('forms.travel.edit', $referenceNumber) }}" class="btn btn-warning btn-sm">Edit</a>

          <form action="{{ route('forms.travel.destroy', $referenceNumber) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this travel batch?')">Delete</button>
          </form>

          <a href="{{ route('forms.travel.print', $referenceNumber) }}" class="btn btn-primary btn-sm" target="_blank">
            Print
          </a>
        </td>
      </tr>

      <!-- Signature Type Modal -->
      <div class="modal fade" id="signatureTypeModal_{{ $referenceNumber }}" tabindex="-1" aria-labelledby="signatureTypeLabel_{{ $referenceNumber }}" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="signatureTypeLabel_{{ $referenceNumber }}">Choose Signature Type</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Select the type of signature for this document:</p>
              <div class="d-grid gap-2">
                <!-- Wet Signature -->
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#wetSignatureModal_{{ $referenceNumber }}">
                  Wet Signature
                </button>

                <!-- Digital Signature -->
                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#digitalSignatureModal_{{ $referenceNumber }}">
                  Digital Signature
                </button>

                <!-- Electronic Signature -->
                <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#electronicSignatureModal_{{ $referenceNumber }}">
                  Electronic Signature
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Wet Signature Modal -->
      <div class="modal fade" id="wetSignatureModal_{{ $referenceNumber }}" tabindex="-1" aria-labelledby="wetSignatureLabel_{{ $referenceNumber }}" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="{{ route('forms.travel.wetSign', $referenceNumber) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title">Wet Signature</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Upload Scanned Signature (PNG/JPG)</label>
                  <input type="file" name="signature_image" class="form-control" required accept="image/png,image/jpeg">
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Apply Signature</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Digital Signature Modal -->
      <div class="modal fade" id="digitalSignatureModal_{{ $referenceNumber }}" tabindex="-1"
        aria-labelledby="digitalSignatureLabel_{{ $referenceNumber }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form action="{{ route('forms.travel.digitalSignImage', $referenceNumber) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="digitalSignatureLabel_{{ $referenceNumber }}">
                  Digital Signature
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p class="text-muted small">
                  Upload your P12/PFX certificate and enter its password to digitally sign the travel order.
                  Optionally, upload a signature image to appear on the PDF.
                </p>

                <!-- Certificate -->
                <div class="mb-3">
                  <label class="form-label">Upload Certificate (.p12/.pfx)</label>
                  <input type="file" name="certificate" class="form-control" required accept=".p12">
                </div>

                <!-- Certificate Password -->
                <div class="mb-3">
                  <label class="form-label">Certificate Password</label>
                  <input type="password" name="password" class="form-control" required>
                </div>

                <!-- Optional Signature Image -->
                <div class="mb-3">
                  <label class="form-label">Signature Image (optional)</label>
                  <input type="file" name="signature_image" class="form-control" accept="image/png, image/jpg, image/jpeg">
                  <small class="text-muted">This image will overlay above the Regional Director's name.</small>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-file-earmark-check"></i> Sign PDF
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Electronic Signature Modal (Upload Image) -->
      <div class="modal fade" id="electronicSignatureModal_{{ $referenceNumber }}" tabindex="-1" aria-labelledby="electronicSignatureLabel_{{ $referenceNumber }}" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="{{ route('forms.travel.electronicSignImage', $referenceNumber) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title">Electronic Signature (Upload)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Upload Your Signature Image</label>
                  <input type="file" name="signature_image" class="form-control" required accept=".png,.jpg,.jpeg">
                  <small class="text-muted">Accepted formats: PNG, JPG, JPEG. Max size 2MB.</small>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Apply Signature</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>


      @endforeach
    </tbody>
  </table>

  @if(session('success'))
  <div class="toast-container  p-3">
    <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          {{ session('success') }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
  @endif
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
  jQuery(function($) {
    $('#travelTable').DataTable({
      paging: true,
      searching: true,
      info: true
    });
  });
</script>

@endsection