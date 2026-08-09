<?php

namespace App\Http\Controllers;

use App\Models\TemplateCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Template;
//use App;
use File;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('templates.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if (auth()->user()->role == 'company') {
            if ($request->from == 'mine') {
                $templates = Template::query()->where('is_default', '=', 0)->where('company_id', '=', auth()->user()->id);
            } elseif ($request->from == 'gallery') {
                $templates = Template::query()->where('is_default', '=', 1)->where('company_id', '=', auth()->user()->id);
            }
        } else if (auth()->user()->role == 'guest') {
            if ($request->from == 'mine') {
                $templates = Template::query()->where('is_default', '=', 0)->where('is_guest', '=', true)->where('company_id', '=', auth()->user()->id);
            } elseif ($request->from == 'gallery') {
                $templates = Template::query()->where('is_default', '=', 1)->where('is_guest', '=', true)->where('company_id', '=', auth()->user()->id);
            }
        } else {
            if ($request->from == 'gallery') {
                $templates = Template::query()->where('is_default', '=', 1);
            } else {
                $templates = $request->user()->templates();
            }
        }

        // view
        $view = $request->view ? $request->view : 'list';

        // sort, pagination
        $templates = $templates
            ->where('name', 'like', '%' . $request->keyword . '%')
            ->orderBy($request->sort_order ?: 'id', $request->sort_direction ?: 'asc')
            ->paginate($request->per_page ? $request->per_page : 12);

        return view('templates._list_' . $view, [
            'templates' => $templates,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Generate info
        $template = new Template();


        // Get old post values
        if (null !== $request->old()) {
            $template->fill($request->old());
        }

        return view('templates.create', [
            'template' => $template,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $uid)
    {
        // Generate info
        $user = $request->user();
        if ($user->role == 'guest' || $user->role == 'company') {
            $template = Template::withoutGlobalScope('filterByRole')->where('uid', $uid)->first();
        } else {
            $template = Template::findByUid($uid);
        }


        // Get old post values
        if (null !== $request->old()) {
            $template->fill($request->old());
        }

        return view('templates.edit', [
            'template' => $template,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $user = $request->user();
        $template = Template::findByUid($request->uid);


        // Save template
        $template->fill($request->all());

        $rules = array(
            'name' => 'required',
            'content' => 'required',
        );

        // make validator
        $validator = \Validator::make($request->all(), $rules);

        // redirect if fails
        if ($validator->fails()) {
            // faled
            return response()->json($validator->errors(), 400);
        }
        $template->updateContent($request['content']);

        // success
        return response()->json([
            'status' => 'success',
            'message' => 'Template was successfully updated',
        ], 201);
    }

    /**
     * Upload template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadTemplate(Request $request)
    {

        // validate and save posted data
        if ($request->isMethod('post')) {
            $template = Template::uploadTemplate($request);

            $request->session()->flash('alert-success', 'Template was successfully updated');

            if (auth()->user()->role == 'guest') {
                return redirect()->route('guest.templates.index');
            } else if (auth()->user()->role == 'company') {
                return redirect()->route('company.templates.index');
            } else {
                return redirect()->route('templates.index');
            }
        } else {
            return view('templates.upload');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $templates = Template::whereIn(
            'uid',
            is_array($request->uids) ? $request->uids : explode(',', $request->uids)
        );
        $total = $templates->count();
        $deleted = 0;
        foreach ($templates->get() as $template) {
            $template->deleteAndCleanup();
            $deleted += 1;
        }

        echo $deleted . '/' . $total . ' template(s) were successfully deleted';
    }

    /**
     * Preview template.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function preview(Request $request, $id)
    {
        $template = Template::findByUid($id);

        return view('templates.preview', [
            'template' => $template,
        ]);
    }

    /**
     * Copy template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request)
    {
        $template = Template::findByUid($request->uid);

        if ($request->isMethod('post')) {

            $template->copy([
                'name' => $request->name,
                'customer_id' => $request->user()->id,
            ]);

            echo 'Template was successfully copied';
            return;
        }

        return view('templates.copy', [
            'template' => $template,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function builderEdit(Request $request, $uid)
    {
        // Generate info
        $user = $request->user();
        if ($user->role == 'guest' || $user->role == 'company') {
            $template = Template::withoutGlobalScope('filterByRole')->where('uid', $uid)->first();
        } else {
            $template = Template::findByUid($uid);
        }



        // validate and save posted data
        if ($request->isMethod('post')) {

            $rules = array(
                'content' => 'required',
            );

            $this->validate($request, $rules);

            $template->updateContent($request['content']);

            return response()->json([
                'status' => 'success',
            ]);
        }

        return view('templates.builder.edit', [
            'template' => $template,
            'templates' => $user->getBuilderTemplates(),
            'customer' => $user,
        ]);
    }

    /**
     * Change template from exist template.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function builderChangeTemplate(Request $request, $uid, $change_uid)
    {
        // Generate info
        $template = Template::findByUid($uid);
        $changeTemplate = Template::findByUid($change_uid);

        $template->changeTemplate($changeTemplate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function builderEditContent(Request $request, $uid)
    {
        // Generate info
        $user = $request->user();
        if ($user->role == 'guest' || $user->role == 'company') {
            $template = Template::withoutGlobalScope('filterByRole')->where('uid', $uid)->first();
        } else {
            $template = Template::findByUid($uid);
        }

        return view('templates.builder.content', [
            'content' => $template->content,
        ]);
    }

    /**
     * Upload asset to builder.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadTemplateAssets(Request $request, $uid)
    {
        $user = $request->user();
        if ($user->role == 'guest' || $user->role == 'company') {
            $template = Template::withoutGlobalScope('filterByRole')->where('uid', $uid)->first();
        } else {
            $template = Template::findByUid($uid);
        }

        if ($request->assetType == 'upload' || $request->assetType == 'audio') {
            $assetUrl = $template->uploadAsset($request->file('file'));
        } elseif ($request->assetType == 'url') {
            $assetUrl = $template->uploadAssetFromUrl($request->url);
        } elseif ($request->assetType == 'base64') {
            $assetUrl = $template->uploadAssetFromBase64($request->url_base64);
        }

        return response()->json([
            'url' => $assetUrl
        ]);
    }

    /**
     * Create template / temlate selection.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function builderCreate(Request $request)
    {

        // validate and save posted data
        if ($request->isMethod('post')) {
            // Get selected template
            // Copy
            $selectedTemplate = Template::findByUid($request->template);

            if (!$selectedTemplate) {

                session()->flash('message', 'Please select a template below.');
                return redirect()->back();
            }

            $template = User::copyTemplateAs($selectedTemplate, $request->name);

            if (auth()->user()->role == 'guest') {
                return redirect()->route('guest.templates.builderEdit', $template->uid);
            } else if (auth()->user()->role == 'company') {
                return redirect()->route('company.templates.builderEdit', $template->uid);
            } else {
                return redirect()->route('templates.builderEdit', $template->uid);
            }
        } else {
            $template = new Template();
            $template->name = 'Untitled template';

            return view('templates.builder.create', [
                'template' => $template,
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function builderTemplates(Request $request)
    {
        // category
        $category = TemplateCategory::findByUid($request->category_uid);

        // sort, pagination
        $templates = $category->templates()
            //            ->search($request->keyword)
            ->orderBy($request->sort_order, $request->sort_direction)
            ->paginate($request->per_page);

        return view('templates.builder.templates', [
            'templates' => $templates,
        ]);
    }

    /**
     * Update template thumb.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateThumb(Request $request, $uid)
    {
        $user = $request->user();
        if ($user->role == 'guest' || $user->role == 'company') {
            $template = Template::withoutGlobalScope('filterByRole')->where('uid', $uid)->first();
        } else {
            $template = Template::findByUid($uid);
        }


        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'file' => 'required',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('templates.updateThumb', [
                    'template' => $template,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // update thumb
            $template->uploadThumbnail($request->file);

            return response()->json([
                'status' => 'success',
                'message' => 'Template thumbnail was uploaded successfully',
            ], 201);
        }

        return view('templates.updateThumb', [
            'template' => $template,
        ]);
    }

    /**
     * Update template thumb url.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateThumbUrl(Request $request, $uid)
    {
        $user = $request->user();
        if ($user->role == 'guest' || $user->role == 'company') {
            $template = Template::withoutGlobalScope('filterByRole')->where('uid', $uid)->first();
        } else {
            $template = Template::findByUid($uid);
        }


        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'url' => 'required|url',
            ]);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('templates.updateThumbUrl', [
                    'template' => $template,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // update thumb
            $template->uploadThumbnailUrl($request->url);

            return response()->json([
                'status' => 'success',
                'message' => 'Template thumbnail was uploaded successfully',
            ], 201);
        }

        return view('templates.updateThumbUrl', [
            'template' => $template,
        ]);
    }

    /**
     * Template categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories(Request $request, $uid)
    {
        $user = $request->user();
        if ($user->role == 'guest' || $user->role == 'company') {
            $template = Template::withoutGlobalScope('filterByRole')->where('uid', $uid)->first();
        } else {
            $template = Template::findByUid($uid);
        }


        if ($request->isMethod('post')) {
            foreach ($request->categories as $key => $value) {
                $category = TemplateCategory::findByUid($key);
                if ($value == 'true') {
                    $template->addCategory($category);
                } else {
                    $template->removeCategory($category);
                }
            }
        }

        return view('templates.categories', [
            'template' => $template,
        ]);
    }

    public function export(Request $request)
    {
        $template = Template::findByUid($request->uid);

        $zipPath = $template->createTmpZip();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function changeName(Request $request)
    {
        $template = Template::findByUid($request->uid);


        if ($request->isMethod('post')) {
            // change name
            $validator = $template->changeName($request->name);

            if ($validator->fails()) {
                return response()->view('templates.changeName', [
                    'template' => $template,
                    'errors' => $validator->errors(),
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Template name was changed',
            ], 201);
        }

        return view('templates.changeName', [
            'template' => $template,
        ]);
    }

    public function parseRss(Request $request)
    {
        return parseRss($request->config);
    }

    public function chat()
    {
        return view('templates.chat');
    }
}
