<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Auth;
use App\Handlers\ImageUploadHandler;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request,Topic $topic)
	{
		$topics = $topic->withOrder($request->order)->paginate(20);
        $path = $request->order ? $request->getPathInfo().'?order='.$request->order : '';
        $topics->withPath($path);
		return view('topics.index', compact('topics'));
	}

    public function show(Request $request, Topic $topic)
    {
        // URL 矫正
        if ( ! empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function store(TopicRequest $request, Topic $topic)
	{
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();
		return redirect()->to($topic->link())->with('message', '成功创建话题！');
	}

    /**
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

    /**
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->to($topic->link())->with('message', '更新成功！');
	}

    /**
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', '成功删除！');
	}

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        $data = [
            'success' => false,
            'msg' => '上传失败',
            'file_path' => ''
        ];

        if($file = $request->upload_file){
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);

            if($result){
                $data = [
                    'success' => true,
                    'msg' => '上传成功',
                    'file_path' => $result['path']
                ];
            }
        }
        return $data;
    }
}