<div id="kkk">
@if(isset($allComments))
	@foreach ($allComments as $a)
		@if(isset($a))
			@foreach ($a as $b)
				@if(isset($b->id))
					<div>{{ $b->heading }}<button onclick="reply(this);" class="reply" name="reply" id="{{$b->id}}">Ответить</button>
					@if ($b->author == $b->profile)
						<button onclick="remove(this);" class="delete" name="delete" id="{{$b->id}}">Удалить</button>
					@endif
					</div>
					<div>{{ $b->text }}</div><br>
				@endif
			@endforeach
			@if(isset($a['kids']))
				@foreach($a['kids'] as $q)
					<div style="margin-left: 20px">
						<div>{{ $q->heading}}
						@if ($q->author == $q->profile)
						<button onclick="remove(this);" class="delete" name="delete" id="{{$q->id}}">Удалить</button>
						@endif
						</div>
						<div>{{ $q->text}}</div><br>
					</div>
				@endforeach
			@endif
		@endif
		@if(isset($kids))
			@foreach ($a as $b)
				{{ $b->id}}
			@endforeach
		@endif
	@endforeach
@endif
</div>

