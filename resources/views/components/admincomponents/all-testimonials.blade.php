@props(['testimonials'])

<table class="table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Trip Details</th>
      <th scope="col">Trip Date</th>
      <th scope="col">Trip Rating</th>
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
