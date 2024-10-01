@extends('layouts.app')

@section('content')
<div class="row g-0 align-items-center">
    <div class="col-12">
        <form action="{{ route('{{camelCasePluralName}}.edit', ['id' => ${{camelCaseSingularName}}->id]) }}" class="needs-validation" method="POST" novalidate name="{{snakeCaseSingularName}}_edit_form" id="{{snakeCaseSingularName}}_edit_form">
            @csrf
            <div class="card border-0 pt-3">
                <div class="card-header bg-transparent border-0 row d-flex align-items-center p-0">
                    <div class="col-12 col-md-9 col-lg-10">
                        <h1 class="text-app_dblue">
                            {{ $page_title }}
                        </h1>
                        <div class="my-2 small fst-italic clearfix text-app_lblue">
                            {!! trans('messages.edit_{{snakeCaseSingularName}}_page_desc') !!}
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-lg-2">
                        <a class="btn btn-dark border-0 w-100" href="{{route('{{camelCasePluralName}}.index')}}" role="button" >
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body border-0 px-0">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="name" class="fw-bold form-label">
                                {{ trans('messages.{{snakeCaseSingularName}}_form_field_name_text') }} <sup><i class="fa-solid fa-asterisk text-danger small fw-bold"></i></sup>
                            </label>
                            <input type="text" class="form-control @error('name'){{ 'is-invalid' }}@enderror" id="name" name="name" value="{{ ${{camelCaseSingularName}}->name }}" autocomplete="off" />
                            @error('name')
                                <small class="invalid-feedback fw-bold fst-italic text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 mb-3"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent p-0 d-grid gap-1">
                    <button class="btn btn-app_dblue fw-bold border-0 text-white" type="submit" style="outline: none;">
                        {{ trans('messages.edit_{{snakeCaseSingularName}}_form_submit_btn_text') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
