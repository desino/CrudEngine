<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{{modelName}};
use Illuminate\Support\Facades\DB;

class {{capitalCaseSingularName}}Controller extends Controller
{
    private $loggedUser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->loggedUser = \Auth::user();

            return $next($request);
        });
    }

    /**
     * Handles the index action for the {{lowerCasePluralName}}.
     *
     * Checks for basic authentication, retrieves and validates filter statuses and keywords,
     * and paginates the {{camelCasePluralName}} data based on the filter criteria.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!$this->loggedUser) {
            AppMiscService::addRedirectMsg(__('messages.general_not_allowed_access_message_text'));
            return redirect()->route('home');
        }

        //$config = AppMiscService::getConfig();
        ${{camelCaseSingularName}}Statuses = {{capitalCaseSingularName}}::getStatuses();

        if ($request->isMethod('POST')) {
            ${{camelCasePluralName}}IndexFilterStatus           = $request->post('{{camelCasePluralName}}IndexFilterStatus', [${{camelCaseSingularName}}Statuses[0]]);
            $validated_{{camelCasePluralName}}IndexFilterStatus = count(${{camelCasePluralName}}IndexFilterStatus) == count(array_intersect(${{camelCasePluralName}}IndexFilterStatus, ${{camelCaseSingularName}}Statuses)) ? ${{camelCasePluralName}}IndexFilterStatus : [${{camelCaseSingularName}}Statuses[0]];
            $request->session()->put('{{camelCasePluralName}}IndexFilterStatus', $validated_{{camelCasePluralName}}IndexFilterStatus);

            ${{camelCasePluralName}}IndexFilterKeyword = $request->post('{{camelCasePluralName}}IndexFilterKeyword', '');
            $request->session()->put('{{camelCasePluralName}}IndexFilterKeyword', ${{camelCasePluralName}}IndexFilterKeyword);
        }

        ${{camelCasePluralName}}IndexFilterStatus = $request->session()->get('{{camelCasePluralName}}IndexFilterStatus', [${{camelCaseSingularName}}Statuses[0]]);
        ${{camelCasePluralName}}IndexFilterStatus = count(${{camelCasePluralName}}IndexFilterStatus) == count(array_intersect(${{camelCasePluralName}}IndexFilterStatus, ${{camelCaseSingularName}}Statuses)) ? ${{camelCasePluralName}}IndexFilterStatus : [${{camelCaseSingularName}}Statuses[0]];

        ${{camelCasePluralName}}IndexFilterKeyword = $request->session()->get('{{camelCasePluralName}}IndexFilterKeyword', '');

        ${{camelCasePluralName}} = {{capitalCaseSingularName}}::orderByRaw('CAST(name as UNSIGNED) ASC')
            ->orderBy('name', 'ASC')
            ->status(${{camelCasePluralName}}IndexFilterStatus)
            ->when(strlen(${{camelCasePluralName}}IndexFilterKeyword) > 0, function ($q) use (${{camelCasePluralName}}IndexFilterKeyword) {
                $q->where('name','LIKE', '%'.${{camelCasePluralName}}IndexFilterKeyword.'%');
            })
            ->paginate(20);
            //->paginate($config['items_per_page_{{snakeCasePluralName}}']);

        $page_title = __('messages.{{snakeCasePluralName}}_list_page_title');

        return view('{{snakeCasePluralName}}.index', compact(
            'page_title',
            '{{camelCasePluralName}}',
            '{{camelCaseSingularName}}Statuses'.
            '{{camelCasePluralName}}IndexFilterStatus'
            '{{camelCasePluralName}}IndexFilterKeyword',
        ));
    }

    /**
     * Handles the request to create & process the post request to create the {{lowerCasePluralName}}.
     *
     * @throws \Exception if creation fails
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!$this->loggedUser) {
            AppMiscService::addRedirectMsg(__('messages.general_not_allowed_access_message_text'));
            return redirect()->route('home');
        }

        if ($request->isMethod('POST')) {
            //$validationsMsgs = AppMiscService::defaultValidationMsgs();
            $validationsMsgs = [];
            $validationsMsgs['name.unique'] = __('messages.{{snakeCasePluralName}}_general_error_name_is_not_unique');

            $validations = [
                'name' => ['required', 'unique:{{snakeCasePluralName}},name', 'max:55'],
            ];

            $validator = Validator::make($request->all(), $validations, $validationsMsgs);
            if ($validator->fails()) {
                AppMiscService::addRedirectMsg(__('messages.general_form_error_message'));

                return redirect()->route('{{camelCasePluralName}}.create')->withInput()->withErrors($validator->errors());
            }

            DB::beginTransaction();
            try {
                ${{camelCaseSingularName}} = {{capitalCaseSingularName}}::create([
                    'name'       => $request->post('name'),
                    'status'     => {{capitalCaseSingularName}}::getStatusActive(),
                    'created_at' => now()->toDateTimeString(),
                    'created_by' => $this->loggedUser->id,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                logger()->error($e);
                AppMiscService::addRedirectMsg(__('messages.create_{{snakeCaseSingularName}}_error_msg'));

                return redirect()->route('{{camelCasePluralName}}.create')->withInput();
            }

            AppMiscService::addRedirectMsg(__('messages.create_{{snakeCaseSingularName}}_success_msg'), 'success');

            return redirect()->route('{{camelCasePluralName}}.index');
        }

        $page_title = __('messages.create_{{snakeCaseSingularName}}_page_title');

        return view('{{snakeCasePluralName}}.create', compact(
            'page_title',
        ));
    }

    /**
     * Handles request to edit the record & also process the posted edit from for the {{capitalCaseSingularName}}.
     *
     * @param int $id The ID of the {{capitalCaseSingularName}} to be edited
     * @param Request $request The HTTP request object
     * @throws \Exception If an error occurs during the update process
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if (! $this->loggedUser) {
            AppMiscService::addRedirectMsg(__('messages.general_not_allowed_access_message_text'));

            return redirect()->route('home');
        }

        ${{camelCaseSingularName}} = {{capitalCaseSingularName}}::find($id);
        if (!${{camelCaseSingularName}}) {
            AppMiscService::addRedirectMsg(__('messages.general_error_{{snakeCaseSingularName}}_not_found'));

            return redirect()->route('{{camelCasePluralName}}.index');
        }

        if ($request->isMethod('POST')) {
            $validationsMsgs = AppMiscService::defaultValidationMsgs();
            $validationsMsgs['name.unique'] = __('messages.{{snakeCasePluralName}}_general_error_name_is_not_unique');

            $validations = [
                'name' => ['required', 'unique:{{camelCasePluralName}},name,'.${{camelCaseSingularName}}->id, 'max:55'],
            ];

            $validator = Validator::make($request->all(), $validations, $validationsMsgs);
            if ($validator->fails()) {
                AppMiscService::addRedirectMsg(__('messages.general_form_error_message'));

                return redirect()->route('{{camelCasePluralName}}.edit', ['id' => ${{camelCaseSingularName}}->id])->withInput()->withErrors($validator->errors());
            }

            DB::beginTransaction();
            try {
                ${{camelCaseSingularName}}->update([
                    'name'       => $request->post('name'),
                    'updated_at' => now()->toDateTimeString(),
                    'updated_by' => $this->loggedUser->id,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                logger()->error($e);
                AppMiscService::addRedirectMsg(__('messages.edit_{{snakeCaseSingularName}}_error_msg'));

                return redirect()->route('{{camelCasePluralName}}.edit', ['id' => ${{camelCaseSingularName}}->id]);
            }

            AppMiscService::addRedirectMsg(__('messages.edit_{{snakeCaseSingularName}}_success_msg'), 'success');

            return redirect()->route('{{camelCasePluralName}}.index');
        }

        $page_title = __('messages.edit_{{snakeCaseSingularName}}_page_title', ['{{upperCaseSingularName}}_NAME' => ${{camelCaseSingularName}}->name]);

        return view('{{snakeCasePluralName}}.edit', compact(
            'page_title',
            '{{camelCaseSingularName}}',
        ));
    }

    /**
     * Activates the {{camelCaseSingularName}}.
     *
     * @param Request $request The HTTP request object.
     * @throws \Exception if an error occurs during the activation process
     * @return \Illuminate\Http\Response The HTTP response.
     */
    public function activate(Request $request)
    {
        if (! $this->loggedUser) {
            AppMiscService::addRedirectMsg(__('messages.general_not_allowed_access_message_text'));

            return redirect()->route('home');
        }

        $activate_id = (int) $request->post('activate_id', 0);
        ${{camelCaseSingularName}} = {{capitalCaseSingularName}}::find($activate_id);
        if (!${{camelCaseSingularName}}) {
            AppMiscService::addRedirectMsg(__('messages.general_error_{{snakeCaseSingularName}}_not_found'));

            return redirect()->route('{{camelCasePluralName}}.index');
        }

        DB::beginTransaction();
        try {
            ${{camelCaseSingularName}}->update([
                'status'     => {{capitalCaseSingularName}}::getStatusActive(),
                'updated_at' => now()->toDateTimeString(),
                'updated_by' => $this->loggedUser->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            logger()->error($e);
            AppMiscService::addRedirectMsg(__('messages.enable_{{snakeCaseSingularName}}_error_msg'));

            return redirect()->route('{{camelCasePluralName}}.index');
        }

        AppMiscService::addRedirectMsg(__('messages.enable_{{snakeCaseSingularName}}_success_msg'), 'success');

        return redirect()->route('{{camelCasePluralName}}.index');
    }

    /**
     * Deactivates a {{camelCaseSingularName}}.
     *
     * @param Request $request The HTTP request object.
     * @throws \Exception If an error occurs during the deactivation process.
     * @return \Illuminate\Http\Response The HTTP response.
     */
    public function deactivate(Request $request)
    {
        if (! $this->loggedUser) {
            AppMiscService::addRedirectMsg(__('messages.general_not_allowed_access_message_text'));

            return redirect()->route('home');
        }

        $deactivate_id = (int) $request->post('deactivate_id', 0);
        ${{camelCaseSingularName}} = {{capitalCaseSingularName}}::find($deactivate_id);
        if (!${{camelCaseSingularName}}) {
            AppMiscService::addRedirectMsg(__('messages.general_error_{{snakeCaseSingularName}}_not_found'));

            return redirect()->route('{{camelCasePluralName}}.index');
        }

        DB::beginTransaction();
        try {
            ${{camelCaseSingularName}}->update([
                'status'     => {{capitalCaseSingularName}}::getStatusInactive(),
                'updated_at' => now()->toDateTimeString(),
                'updated_by' => $this->loggedUser->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            logger()->error($e);
            AppMiscService::addRedirectMsg(__('messages.disable_{{snakeCaseSingularName}}_error_msg'));

            return redirect()->route('{{camelCasePluralName}}.index');
        }

        AppMiscService::addRedirectMsg(__('messages.disable_{{snakeCaseSingularName}}_success_msg'), 'success');

        return redirect()->route('{{camelCasePluralName}}.index');
    }
}
