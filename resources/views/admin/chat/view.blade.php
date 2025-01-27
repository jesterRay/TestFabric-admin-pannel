@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')

@section('content')


<div class="row">
  <div class="col-12 mb-6">
    <div class="card">
        <div class="card-body">
            <div class="card-title text-primary">
                Chats with <strong class="text-capitalize">{{$user_name}}</strong>
            </div>
            <div>
                <ul class="chat-view-list">
                    @if (isset($chats) && count($chats) > 0)
                        @foreach ($chats as $chat)
                            <li>
                                <strong class="text-capitalize">{{$chat->sender_type}}: </strong>
                                <span>{{$chat->message}}</span>
                            </li>
                        @endforeach
                    @else
                        <strong>No Chats History</strong>
                    @endif
                </ul>
                <div>
                    <form id="formAccountSettings" method="POST" action="{{route('chat.save')}}">
                        @csrf
                        <div class="row g-6">
                            <div class="col-12">
                                <label for="message" class="form-label" required>Message</label>
                                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Type Your Message"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="user_name" value="{{ $user_name }}">
                        <div class="mt-6">
                          <button type="submit" class="btn btn-primary me-3">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

@endsection
