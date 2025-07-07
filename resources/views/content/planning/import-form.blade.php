@extends('layouts/contentNavbarLayout')

@section('title', 'Import Employees')

@section('content')
<div class="container py-4">
  <h4 class="mb-4">📤 Import Employees</h4>
  <p class="mb-2 text-muted">
    Make sure your CSV includes headers:<br>
    <code>username,employee_id,first_name,middle_name,last_name,gender,extension_name,employment_status,division,section,email</code>
  </p>

  <form action="{{ route('planning.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label for="file">Upload CSV File</label>
      <input type="file" name="file" id="file" accept=".csv" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary mt-2">Import</button>
  </form>

  @if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
  @elseif (session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
  @endif
</div>
@endsection
