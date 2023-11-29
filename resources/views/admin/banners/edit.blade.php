@extends('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h2>Edit Banner</h2>
                <form method="POST" action="{{ route('admin.banners.update', $banner->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description"
                               value="{{ $banner->description }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Banner</button>
                </form>
            </main>
        </div>
    </div>
@endsection
