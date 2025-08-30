@foreach($comments as $comment)
  @include('issues._comment_item',['comment'=>$comment])
@endforeach
