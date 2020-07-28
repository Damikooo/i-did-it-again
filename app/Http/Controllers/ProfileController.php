<?php
	namespace App\Http\Controllers;

	use App\User;
	use App\Comment;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\View;
	use Response;
	class ProfileController extends Controller
	{

		public function getCommentsByProfile($id)
		{
	
			$comments = Comment::where([
				['profile', $id],
				['parent_id']])
			->skip(5)
			->take(100000)
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

			
			$Comments['status'] = "false";

			if(empty($comments[0])):
				// return "Комментариев больше нет";
				return response($Comments);
			elseif(empty($users[0])):
				// return "Пользователя с таким id не существует";
				return response($Comments);
			else:
				$Comments['html'] = $view;
				$Comments['status'] = "true";
				return response($Comments);
			return $view;
			endif;

		}

		public function show($id, Request $request)
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
		public function delete(Request $request){
		    Comment::where('id', $request['post_id'])
			->delete();
		}
		public function reply(Request $request, $id){
			if (isset($request['parent_id'])){
			    Comments::insert([
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
