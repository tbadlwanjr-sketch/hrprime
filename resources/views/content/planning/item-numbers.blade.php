@extends('layouts/contentNavbarLayout')

@section('title', 'Item Numbers')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="card">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 style="color: #1d4bb2;">List of Item Numbers</h4>
      <button id="openModalBtn" class="btn btn-success">Add New Item Number</button>
    </div>

    <div class="table-responsive">
      <table id="itemNumberTable" class="table">
        <thead class="table-light">
          <tr>
            <th>No.</th>
            <th>Item Number</th>
            <th>Position</th>
            <th>Salary Grade</th>
            <th>Employment Status</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($itemNumbers as $index => $item)
          <tr data-id="{{ $item->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->item_number }}</td>
            <td>{{ $item->position->position_name ?? '-' }}</td>
            <td>{{ $item->salaryGrade->name ?? '-' }}</td>
            <td>{{ $item->employmentStatus->name ?? '-' }}</td>
            <td>{{ ucfirst($item->status ?? 'active') }}</td>
            <td class="text-nowrap">
              <div class="d-inline-flex gap-1">
                <button class="btn btn-sm btn-primary edit-btn"
                  data-id="{{ $item->id }}"
                  data-item_number="{{ $item->item_number }}"
                  data-position_id="{{ $item->position_id }}"
                  data-salary_grade_id="{{ $item->salary_grade_id }}"
                  data-employment_status_id="{{ $item->employment_status_id }}"
                  data-status="{{ $item->status }}">
                  Edit
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}">
                  Delete
                </button>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="itemNumberModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Add New Item Number</h5>
      <form id="itemNumberForm">
        <div class="modal-body">
          <div class="mb-3">
            <label>Item Number</label>
            <input type="text" name="item_number" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Position</label>
            <select name="position_id" class="form-control" required>
              <option value="">-- Select Position --</option>
              @foreach($positions as $pos)
              <option value="{{ $pos->id }}">{{ $pos->position_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Salary Grade</label>
            <select name="salary_grade_id" class="form-control" required>
              <option value="">-- Select Salary Grade --</option>
              @foreach($salaryGrades as $sg)
              <option value="{{ $sg->id }}">{{ $sg->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Employment Status</label>
            <select name="employment_status_id" class="form-control" required>
              <option value="">-- Select Employment Status --</option>
              @foreach($employmentStatuses as $es)
              <option value="{{ $es->id }}">{{ $es->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editItemNumberModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <h5 class="modal-title m-3">Edit Item Number</h5>
      <form id="editItemNumberForm">
        <input type="hidden" name="id" id="editItemNumberId">
        <div class="modal-body">
          <div class="mb-3">
            <label>Item Number</label>
            <input type="text" name="item_number" id="editItemNumber" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Position</label>
            <select name="position_id" id="editPositionId" class="form-control" required>
              <option value="">-- Select Position --</option>
              @foreach($positions as $pos)
              <option value="{{ $pos->id }}">{{ $pos->position_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Salary Grade</label>
            <select name="salary_grade_id" id="editSalaryGradeId" class="form-control" required>
              <option value="">-- Select Salary Grade --</option>
              @foreach($salaryGrades as $sg)
              <option value="{{ $sg->id }}">{{ $sg->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Employment Status</label>
            <select name="employment_status_id" id="editEmploymentStatusId" class="form-control" required>
              <option value="">-- Select Employment Status --</option>
              @foreach($employmentStatuses as $es)
              <option value="{{ $es->id }}">{{ $es->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Status</label>
            <select name="status" id="editStatus" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  $(document).ready(function() {
    $('#itemNumberTable').DataTable();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // Open Add Modal
    $('#openModalBtn').click(function() {
      $('#itemNumberForm')[0].reset();
      $('#itemNumberModal').modal('show');
      alert('dsdds');
    });

    // Add Item Number
    $('#itemNumberForm').submit(function(e) {
      e.preventDefault();
      $.ajax({
        url: '/planning/item-numbers',
        type: 'POST',
        data: $(this).serialize(),
        success: function(data) {
          if (data.success) {
            toastr.success(data.message);
            $('#itemNumberModal').modal('hide');
            location.reload();
          }
        },
        error: function(xhr) {
          let errors = xhr.responseJSON.errors;
          if (errors) {
            Object.values(errors).forEach(msg => toastr.error(msg));
          }
        }
      });
    });

    // Open Edit Modal
    $(document).on('click', '.edit-btn', function() {
      let btn = $(this);
      $('#editItemNumberId').val(btn.data('id'));
      $('#editItemNumber').val(btn.data('item_number'));
      $('#editPositionId').val(btn.data('position_id'));
      $('#editSalaryGradeId').val(btn.data('salary_grade_id'));
      $('#editEmploymentStatusId').val(btn.data('employment_status_id'));
      $('#editStatus').val(btn.data('status'));
      $('#editItemNumberModal').modal('show');
    });

    // Update Item Number
    $('#editItemNumberForm').submit(function(e) {
      e.preventDefault();
      let id = $('#editItemNumberId').val();
      $.ajax({
        url: '/planning/item-numbers/' + id,
        type: 'PUT',
        data: $(this).serialize(),
        success: function(data) {
          if (data.success) {
            toastr.success(data.message);
            $('#editItemNumberModal').modal('hide');
            location.reload();
          }
        },
        error: function(xhr) {
          let errors = xhr.responseJSON.errors;
          if (errors) {
            Object.values(errors).forEach(msg => toastr.error(msg));
          }
        }
      });
    });

    // Delete Item Number
    $(document).on('click', '.delete-btn', function() {
      if (!confirm('Are you sure you want to delete this item number?')) return;
      let id = $(this).data('id');
      $.ajax({
        url: '/planning/item-numbers/' + id,
        type: 'DELETE',
        success: function(data) {
          if (data.success) {
            toastr.success(data.message);
            location.reload();
          }
        }
      });
    });
  });
</script>
@endsection
