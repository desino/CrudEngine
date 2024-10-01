@extends('layouts.app')

@section('customscript')
<script>
    jQuery(document).ready(function($) {
        jQuery('#loading_screen').css('display', 'none');

        jQuery(document).on('click', '.deactivate{{capitalCaseSingularName}}', function(event) {
            event.preventDefault();
            var eleId = parseInt(jQuery(this).attr('id').split('deactivate{{capitalCaseSingularName}}')[1]);
            if (eleId > 0) {
                jQuery("form#deactivate_form #deactivate_id").val(eleId);
                jQuery("form#deactivate_form").submit();
            }
        });
        jQuery(document).on('click', '.activate{{capitalCaseSingularName}}', function(event) {
            event.preventDefault();
            var eleId = parseInt(jQuery(this).attr('id').split('activate{{capitalCaseSingularName}}')[1]);
            if (eleId > 0) {
                jQuery("form#activate_form #activate_id").val(eleId);
                jQuery("form#activate_form").submit();
            }
        });

        jQuery(document).on('click', '#{{snakeCasePluralName}}_index_clear', function(event) {
            event.preventDefault();
            jQuery('#loading_screen').show();
            jQuery('input[name="{{camelCasePluralName}}IndexFilterStatus[]"], #{{camelCasePluralName}}IndexFilterKeyword').val('');
            jQuery("#{{snakeCasePluralName}}_index_submit").trigger('click');
        });
    });
</script>
@endsection

@section('content')
<div class="row g-0 align-items-center">
    <div class="col-12">
        <div class="card border-0 pt-3">
            <div class="card-header bg-transparent border-0 row align-items-center p-0">
                <div class="col-12 col-md-9 col-lg-10">
                    <h1 class="text-app_dblue">
                        {{ $page_title }}
                    </h1>
                    <div class="my-2 small fst-italic clearfix text-app_lblue">
                        {!! __('messages.{{snakeCasePluralName}}_list_page_desc') !!}
                    </div>
                </div>
                <div class="col-12 col-md-3 col-lg-2">
                    <a class="btn btn-app_dblue border-0 w-100" href="{{route('{{camelCasePluralName}}.create')}}" role="button" >
                        <i class="fa-solid fa-plus"></i> {{ __('messages.{{snakeCasePluralName}}_list_create_btn_text') }}
                    </a>
                </div>
            </div>
            <div class="card-body border-0 px-0">
                <div class="clearfix py-4">
                    <form action="{{route('{{camelCasePluralName}}.index')}}" class="needs-validation" method="POST" novalidate name="{{snakeCasePluralName}}_index" id="{{snakeCasePluralName}}_index">
                        <div class="row w-100 g-0 align-items-end">
                            <div class="col-12 col-md-12 col-lg-4 px-1">
                                <div class="w-100 my-1">
                                    @foreach (${{camelCaseSingularName}}Statuses as $each{{camelCaseSingularName}}Status)
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="{{camelCasePluralName}}IndexFilterStatus{{ $each{{camelCaseSingularName}}Status }}" name="{{camelCasePluralName}}IndexFilterStatus[]" value="{{ $each{{camelCaseSingularName}}Status }}"
                                                @checked(in_array($each{{camelCaseSingularName}}Status, ${{camelCasePluralName}}IndexFilterStatus)) >
                                            <label class="form-check-label" for="{{camelCasePluralName}}IndexFilterStatus{{ $each{{camelCaseSingularName}}Status }}">
                                                <small class="fw-bold">{{__('messages.{{snakeCaseSingularName}}_form_field_status_option'.$each{{camelCaseSingularName}}Status.'_text')}}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3 px-1">
                                <div class="w-100 my-1">
                                    <input class="form-control" type="text" name="{{camelCasePluralName}}IndexFilterKeyword" id="{{camelCasePluralName}}IndexFilterKeyword" value="{{ ${{camelCasePluralName}}IndexFilterKeyword }}" placeholder="{{ __('messages.{{snakeCasePluralName}}_filter_keyword_text') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3 px-1">
                                <div class="w-100 my-1"></div>
                            </div>
                            <div class="col-6 col-md-6 col-lg-1 px-1">
                                <div class="w-100 my-1">
                                    <button type="submit" class="btn btn-app_dblue w-100 border-0" id="{{snakeCasePluralName}}_index_submit" name="{{snakeCasePluralName}}_index_submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-6 col-md-6 col-lg-1 px-1">
                                <div class="w-100 my-1">
                                    <button class="btn btn-dark w-100 border-0" id="{{snakeCasePluralName}}_index_clear" name="{{snakeCasePluralName}}_index_clear">
                                        <i class="fas fa-eraser"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @csrf
                    </form>
                </div>

                <div class="w-100">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-none d-md-block active border-0 bg-app_lblue">
                            <div class="row align-items-md-center">
                                <div class="col-md-4 col-lg-4 col-xl-4 text-start">
                                    <small class="fw-bold fst-italic">
                                        {{ __('messages.{{snakeCasePluralName}}_column_name_text') }}
                                    </small>
                                </div>
                                <div class="col-md-4 col-lg-4 col-xl-4 text-start">
                                    <small class="fw-bold fst-italic"></small>
                                </div>
                                <div class="col-md-2 col-lg-2 col-xl-2 text-start">
                                    <small class="fw-bold fst-italic"></small>
                                </div>
                                <div class="col-md-2 col-lg-2 col-xl-2 text-end">
                                    <small class="fw-bold fst-italic">
                                        {{ __('messages.{{snakeCasePluralName}}_column_actions_text') }}
                                    </small>
                                </div>
                            </div>
                        </li>

                        @forelse(${{camelCasePluralName}} as $each{{capitalCaseSingularName}})
                            <li id="each{{capitalCaseSingularName}}{{ $each{{capitalCaseSingularName}}->id }}" class="each{{capitalCaseSingularName}} @if($each{{capitalCaseSingularName}}->isStatusInactive()) list-group-item-secondary @endif list-group-item border border-app_lblue @if(!$loop->first){{'border-top-0'}}@endif">
                                <div class="row align-items-md-center">
                                    <div class="col-12 col-md-4 col-lg-4 col-xl-4 text-start fw-bold text-app_dblue">
                                        {{ $each{{capitalCaseSingularName}}->name }}
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4 col-xl-43 text-start fw-bold text-app_dblue"></div>
                                    <div class="col-6 col-md-2 col-lg-2 col-xl-2 text-start"></div>
                                    <div class="col-6 col-md-2 col-lg-2 col-xl-2 text-end">
                                        @if ($each{{capitalCaseSingularName}}->isStatusActive())
                                            <a class="text-app_dblue me-1" href="JavaScript:void(0);" tabindex="0" style="text-decoration: none;"
                                                data-bs-title="{{trans('messages.{{snakeCasePluralName}}_deactivate_tooltip_{{snakeCaseSingularName}}_text')}}"
                                                data-bs-trigger="focus" data-bs-html="true" data-bs-toggle="popover"
                                                data-bs-content="<div class='text-center'><a role='button' class='deactivate{{capitalCaseSingularName}} btn btn-danger w-100 border-0' id='deactivate{{capitalCaseSingularName}}{{$each{{capitalCaseSingularName}}->id}}' >{{trans('messages.{{snakeCasePluralName}}_tooltip_confirmation_deactivate_btn_text')}}</a></div>" >
                                                <i class="fa-solid fa-circle-check"></i>
                                            </a>
                                        @else
                                            <a class="text-danger me-1" href="JavaScript:void(0);" tabindex="0" style="text-decoration: none;"
                                                data-bs-title="{{trans('messages.{{snakeCasePluralName}}_activate_tooltip_{{snakeCaseSingularName}}_text')}}"
                                                data-bs-trigger="focus" data-bs-html="true" data-bs-toggle="popover"
                                                data-bs-content="<div class='text-center'><a role='button' class='activate{{capitalCaseSingularName}} btn btn-app_dblue w-100 border-0' id='activate{{capitalCaseSingularName}}{{$each{{capitalCaseSingularName}}->id}}' >{{trans('messages.{{snakeCasePluralName}}_tooltip_confirmation_activate_btn_text')}}</a></div>" >
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            </a>
                                        @endif
                                        <a class="me-1 text-app_dblue" target="_blank" href="{{ route('{{camelCasePluralName}}.edit', ['id' => $each{{capitalCaseSingularName}}->id]) }}" style="text-decoration: none;"
                                            data-bs-toggle="tooltip"  title="{{__('messages.{{snakeCasePluralName}}_tooltip_edit_action_text')}}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item border border-app_lblue border-left-0 border-right-0 border-app_lblue">
                                <div class="row g-0">
                                    <div class="col-12 text-center small">
                                        <small class="fw-bold">
                                            {{ __('messages.general_no_results_text') }}
                                        </small>
                                    </div>
                                </div>
                            </li>
                        @endforelse

                        @if (${{camelCasePluralName}}->hasPages())
                            <li class="list-group-item border-0">
                                <div class="row g-0">
                                    <div class="col-12 text-center">
                                        {{ ${{camelCasePluralName}}->links() }}
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>

                <form action="{{route('{{camelCasePluralName}}.activate')}}" id="activate_form" name="activate_form" method="POST">
                    <input type="hidden" name="activate_id" id="activate_id" value="" />
                    @csrf
                </form>
                <form action="{{route('{{camelCasePluralName}}.deactivate')}}" id="deactivate_form" name="deactivate_form" method="POST">
                    <input type="hidden" name="deactivate_id" id="deactivate_id" value="" />
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection