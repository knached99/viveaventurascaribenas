@props(['testimonials'])

<table class="table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Trip Details</th>
      <th scope="col">Trip Date</th>
      <th scope="col">Trip Rating</th>
      <th scope="col">Status</th>
      <th scope="col">Submission Date</th>
      <th scope="col">View</th>
      <th scope="col">Delete</th>
    </tr>
  </thead>
  <tbody>
    @foreach($testimonials as $testimonial)
    <tr>
      <td>{{ $testimonial->name }}</td>
      <td>{{ $testimonial->email }}</td>
      <td>{{ $testimonial->trip_details }}</td>
      <td>{{date('F, Y', strtotime($testimonial->trip_date))}}</td>
      <td>
        <div class="d-flex">
          @for($i = 1; $i <= 5; $i++)
            <i class="bx bxs-star {{ $i <= $testimonial->trip_rating ? 'text-warning' : 'text-secondary' }}"></i>
          @endfor
        </div>
      </td>

      <td>
      @switch($testimonial->testimonial_approval_status)
      
      @case('Pending')
      <span class="bg-indigo-100 text-indigo-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-indigo-900 dark:text-indigo-300">{{$testimonial->testimonial_approval_status}}</span>
      @break 

      @case('Approved')
      <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{$testimonial_approval_status}}</span>
      @break

      @case('Denied')
      <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">{{$testimonial_approval_status}}</span>
      @break
      @endswitch 
      </td>

      <td>{{ date('F jS, Y \a\t g:i A', strtotime($testimonial->created_at)) }}</td>
      <td>
        <a href="{{ route('admin.testimonial', ['testimonialID' => $testimonial->testimonialID]) }}" class="text-decoration-underline">
          View
        </a>
      </td>
      <td>
        <form method="post" action="{{ route('admin.testimonial.delete', ['testimonialID' => $testimonial->testimonialID]) }}">
          @csrf 
          @method('DELETE')
          <button type="submit" class="btn btn-danger text-white">Delete</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
