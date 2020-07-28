<?php
	namespace App\Http\Controllers;

	use App\User;
	use App\Comment;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\StoreBlogPost;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\View;
	use Illuminate\Contracts\Validation\Validator;
	use Response;

	class StoreBlogPostController {

    public function __invoke(BlogPostRequest $request) {
      Post::create($request->validated());
    }

}

	class ProfileController extends Controller
	{

		public function getCommentsByProfile($id)
		{
	
			$comments = Comment::where([
				['profile', $id],
				['parent_id']])
			->skip(5)
			->take(PHP_INT_MAX)
			->get();

			$users = User::where('id', $id)
			->get();

			$allComments = [];

			foreach($comments as $comment){
				$comments_kids = Comment::where([
				['profile', $id],
				['parent_id', $comment->id]])
				->get();

				$totalComment = [
					$comment,
					'kids' => $comments_kids
				];

				$allComments[] = $totalComment;
			}

			$view = View::make('showAll', [
				'allComments' => $allComments ,
			])->render();

			if(empty($comments[0]) and empty($users[0])):
				$response = [
					'status' => false
				];
				return response($response);
			else:
				$response = [
					'html' => $view,
					'status' => true
				];
				return response($response);
			endif;

		}

		public function show($id, StoreBlogPost $request)
		{
			$users = User::where('id', $id)
			->get();

			$comments = Comment::where([
				['profile', $id],
				['parent_id']])
			->take(5)
			->get();

			$comments_kids = Comment::where('profile', $id)
				->orWhereNotNull(['parent_id'])
				->get();

			$i = 0;
			$id_userr = Auth::id();

			$view = View::make('showAll', [
				'id' => $id ,
				'users' => $users,
				'comments' => $comments,
				'comments_kids' => $comments_kids,
			])->render();
			

			return view('profile', [
				'id' => $id ,
				'users' => $users,
				'comments' => $comments,
				'comments_kids' => $comments_kids,
				'id_userr' => $id_userr,
				'view' => $view,
				'i' => $i
			]);
		}
		public function showProfile($id)
		{
			$comments = DB::table('comments')
				->where([
				['author', '=', $id],
				])->get();
			return view('myprofile', [
				'id' => $id ,
				'comments' => $comments
			]);
		}
		public function showAll($id)
		{
			$users = User::where('id', $id)
			->get();

			$comments = Comment::where([
				['profile', $id],
				['parent_id']])
			->skip(5)
			->take(10000)
			->get();

			$comments_kids = Comment::where('profile', $id)
				->orWhereNotNull(['parent_id'])
				->get();

			return view('showAll', [
				'id' => $id ,
				'users' => $users,
				'comments' => $comments,
				'comments_kids' => $comments_kids,
			]);
		}
		public function delete(StoreBlogPost $request){
		    Comment::where('id', $request['post_id'])
			->delete();
		}
		public function reply(StoreBlogPost $request, $id){
			$this->validate($request, [
			    'heading' => 'required|max:100',
			    'text' => 'required',
			  ]);
			if (isset($request['parent_id'])){
			    Comment::insert([
					'parent_id' => $request['parent_id'],
					'profile' => $id,
					'author' => Auth::id(),
					'heading' => $request['heading'],
					'text' => $request['text'],
				]);
			} else {
				Comment::insert([
					'profile' => $id,
					'author' => Auth::id(),
					'heading' => $request['heading'],
					'text' => $request['text'],
				]);
			}
			return redirect()
			->route('profile', ['id' => $id]);
		}
	}
