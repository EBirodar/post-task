@extends('layouts.admin')

@section('content')
  <div class="row">
      <div class="col-lg-6">
      <div class="card">
          @foreach($datas as $data)

          <div class="card-body">
              @foreach ($data->posts as $item)
                  @if($loop->index==0)
                  @if(!$item->id==null)
            <h4 class="card-title">{{$data->name}}</h4>
                  @endif
                  @endif
              @endforeach

          <div class="table-responsive">
            <table class="table mb-3">
                @if($loop->index==0)
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Posts Name</th>
                  <th>Created Time</th>
                  <th>Action</th>
                </tr>
              </thead>
                @endif
              <tbody>

                @foreach ($data->posts as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>

                        <td>{{ $item->name }}</td>
                        <td>{{$item->created_at->diffInMinutes(\Carbon\Carbon::now())}} minutes ago
                        </td>
                      <td>
                          <a href="{{route('admin.users.edit',['user'=>$item->id])}}">
                              <i class="fas fa-pencil-alt" aria-hidden="true"></i>
                          </a>
                          <form method="post" action="{{route('admin.users.destroy',['user'=>$item->id])}}">
                              @method('DELETE')
                              @csrf
                              <button type="submit" class="btn btn-danger">

                                  <i class="fas fa-trash-alt" aria-hidden="true"></i>
                              </button>

                          </form>
                      </td>
                    </tr>
                @endforeach

              </tbody>
            </table>
{{--            {{ $datas->links() }}--}}
          </div>
        </div>
          @endforeach
      </div>

    </div>
      <div class="col-lg-6">
          <h1>{{auth()->user()->name}}</h1>

          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif

          @if(!isset($post))

              <form  action="{{route('admin.users.store')}}" method="post">
{{--                    @php dd('salom');@endphp--}}
                    @csrf
                    <input type="text" class="hidden" name="user_id" value="{{auth()->user()->id}}">
                    <input type="text" class="col-8 mt-2" name="name" >
                    <button type="submit" class="btn btn-primary">Add post</button>
                </form>
          @endif


      @if(isset($post))

              <form  action="{{route('admin.users.update',$post->id)}}" method="post">

                  @method('PUT')
                    @csrf
                    <input type="text" class="hidden" name="user_id" value="{{auth()->user()->id}}">
                    <input type="text" class="col-8 mt-2" name="name" value="{{$post->name}}">
                  <button type="submit" class="btn btn-primary">Update</button>
                </form>
          @endif



      </div>
  </div>
@endsection
