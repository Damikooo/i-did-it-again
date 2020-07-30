<?php
	namespace App\Http\Controllers;

	use App\User;
	use App\Comment;
	use App\Book;
	use App\Library;
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
			$count = Comment::where([
				['profile', $id],
				['parent_id']])
			->count();
			$skip = 5;
			$count = $count - $skip;
			$comments = Comment::where([
				['profile', $id],
				['parent_id']])
			->skip(5)
			->take($count)
			->get();

			$users = User::where('id', $id)
			->find(1)
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

			if(empty($comments[0]) and empty($users)):
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
			$guest = 1;

			$accesstomy = 0;

			$toMy = Library::where([
				'user_id' => Auth::id(),
				'library_access' => $id,
			])
       		->count();

			if($toMy == 1):
       			$accesstomy = 1;
       		endif;

			if($id == Auth::id()):
       			$guest = 0;
       		endif;

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
				'i' => $i,
				'guest' => $guest,
				'accesstomy' => $accesstomy
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
				'parent_id' => 'required|integer',
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
		public function showLibrary($id, StoreBlogPost $request)
		{
			$access = 0;

			$myaccess = 0;

			$books = Book::where(
				'author', $id
			)
       		->get();

       		if($id == Auth::id()):
       			$myaccess = 1;
       		endif;

			return view('library', [
				'id' => $id,
				'books' => $books ,
				'myaccess' => $myaccess,
			]);
		}
		public function shareBook(StoreBlogPost $request)
		{
			$this->validate($request, [
			    'share' => 'required|integer',
			    'access' => 'required|integer',
			  ]);

			Book::where('id', $request['share'])
					->update(['access' => $request['access']]);
		}
		public function shareLibrary(StoreBlogPost $request)
		{
			$this->validate($request, [
			    'share' => 'required'
			  ]);

			$lib = Library::where([
				'user_id' => Auth::id(),
				'library_access' => $request['share'],
			])
       		->count();

       		if ($lib == 1):
       			Library::where([
	        	'user_id' => Auth::id(),
	        	'library_access' => $request['share'],
	        ])
       		->delete();
       		else:
	        Library::insert([
	        	'user_id' => Auth::id(),
	        	'library_access' => $request['share'],
	        ]);
       		endif;
		}
		public function checkBook($id)
		{	
			$book = Book::where('id', $id)
			->get()
			->first();
			$id = Auth::id();
			return view('book', [
				'book' => $book,
				'id' => $id
			]);
		}
		public function writeBook(StoreBlogPost $request)
		{	
			$this->validate($request, [
			    'title' => 'required|max:100',
			    'text' => 'required',
			  ]);
			Book::insert([
					'author' => Auth::id(),
					'access' => 0,
					'title' => $request['title'],
					'text' => $request['text'],
				]);
			return redirect()
				->route('library', ['id' => Auth::id()]);
		}
		public function remove(StoreBlogPost $request){
			$this->validate($request, [
			    'book_id' => 'required|integer',
			  ]);
		    Book::where('id', $request['book_id'])
			->delete();
			return redirect()
				->route('library', ['id' => Auth::id()]);
		}
		public function edit(StoreBlogPost $request){
			$this->validate($request, [
			    'book_id' => 'required|integer',
			    'title' => 'required|max:100',
			    'text' => 'required',
			  ]);
		    Book::where('id', $request['book_id'])
					->update([
						'title' => $request['title'],
						'text' => $request['text'],
					]);
			return redirect()
				->route('book', ['id' => $request['book_id']]);
		}
	}