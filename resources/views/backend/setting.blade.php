@extends('backend.layouts.master')

@section('main-content')
    <div class="card">
        <h5 class="card-header">Edit Post</h5>
        <div class="card-body">
            <form method="post" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf
                {{-- @method('PATCH') --}}
                {{-- {{dd($data)}} --}}
                <div class="form-group">
                    <label for="short_des" class="col-form-label">Short Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="quote" name="short_des">{{ $data->short_des }}</textarea>
                    @error('short_des')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description" class="col-form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description">{!! $data->description !!}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Logo <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input id="thumbnail1" class="form-control" type="file" name="logo" value="">
                    </div>
                    @if ($data->logo)
                        <img src="{{ $data->logo }}" alt="" height="50" width="50">
                    @endif
                    @error('logo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input id="inputPhoto" class="form-control" type="file" name="photo" value="">
                    </div>
                    @if ($data->photo)
                        <img src="{{ $data->photo }}" alt="" height="50" width="50">
                    @endif
                    <div id="holder" style="margin-top:15px;max-height:100px;"></div>

                    @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address" class="col-form-label">Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="address" required value="{{ $data->address }}">
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email" class="col-form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" required value="{{ $data->email }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone" class="col-form-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="phone" required value="{{ $data->phone }}">
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <button class="btn btn-success" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush
@push('scripts')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('description');
    </script>
@endpush
