<aside class="w-64 bg-white shadow-md p-4 space-y-6">
    <h1 class="text-2xl font-bold text-blue-600">Admin Panel</h1>
    <nav class="space-y-2">
      <a href="{{ route('dashboard') }}" class="block py-2 px-4 rounded hover:bg-blue-100 text-gray-700">Dashboard</a>
      <a href="{{ route('post.index') }}" class="block py-2 px-4 rounded hover:bg-blue-100 text-gray-700">Posts</a>
      @can('admin')
        <a href="{{ route('category.index') }}" class="block py-2 px-4 rounded hover:bg-blue-100 text-gray-700">Categories</a>
        <a href="{{ route('comment.index') }}" class="block py-2 px-4 rounded hover:bg-blue-100 text-gray-700">Comments</a>
        <a href="{{ route('user.index') }}" class="block py-2 px-4 rounded hover:bg-blue-100 text-gray-700">Users</a>
      @endCan
      <a href="{{ route('profile.edit') }}" class="block py-2 px-4 rounded hover:bg-blue-100 text-gray-700">Profile</a>
      <a href="{{ route('logout') }}" class="block py-2 px-4 rounded hover:bg-blue-100 text-gray-700">Logout</a>
    </nav>
    @if (auth()->user()->notifications->count())
      <div>
        Notifications:
        <ul>
          @foreach (auth()->user()->notifications as $notification)
            <li>{{ $notification->data['message'] }}</li>
          @endforeach
        </ul>
      </div>
    @endif
</aside>
