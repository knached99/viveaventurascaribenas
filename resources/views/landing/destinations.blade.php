<x-travelcomponents.header />

<x-travelcomponents.navbar />

<section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url({{ asset('assets/images/bg_1.jpg') }});"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate pb-5 text-center">
                <h1 class="mb-3 bread" style="font-weight:900;">Places to Travel</h1>
                <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i
                                class="ion-ios-arrow-forward"></i></a></span> <span>About us <i
                            class="ion-ios-arrow-forward"></i></span></p>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h2 class="mb-4">Most Popular Attractions</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 ftco-animate">
                <div class="project-destination">
                    <a href="#" class="img"
                        style="background-image: url({{ asset('assets/images/place-1.jpg') }});">
                        <div class="text">
                            <h3>Singapore</h3>
                            <span>8 Tours</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 ftco-animate">
                <div class="project-destination">
                    <a href="#" class="img"
                        style="background-image: url({{ asset('assets/images/place-2.jpg') }});">
                        <div class="text">
                            <h3>Canada</h3>
                            <span>2 Tours</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 ftco-animate">
                <div class="project-destination">
                    <a href="#" class="img"
                        style="background-image: url({{ asset('assets/images/place-3.jpg') }});">
                        <div class="text">
                            <h3>Thailand</h3>
                            <span>5 Tours</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3 ftco-animate">
                <div class="project-destination">
                    <a href="#" class="img"
                        style="background-image: url({{ asset('assets/images/place-4.jpg') }});">
                        <div class="text">
                            <h3>Autralia</h3>
                            <span>5 Tours</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- <section class="ftco-section ftco-no-pb ftco-no-pt">
    	<div class="container">
	    	<div class="row">
					<div class="col-md-12 mb-5">
						<div class="search-wrap-1 search-wrap-notop ftco-animate p-4">
							<form action="#" class="search-property-1">
		        		<div class="row">
		        			<div class="col-lg align-items-end">
		        				<div class="form-group">
		        					<label for="#">Destination</label>
		          				<div class="form-field">
		          					<div class="icon"><span class="ion-ios-search"></span></div>
				                <input type="text" class="form-control" placeholder="Search place">
				              </div>
			              </div>
		        			</div>
		        			<div class="col-lg align-items-end">
		        				<div class="form-group">
		        					<label for="#">Check-in date</label>
		        					<div class="form-field">
		          					<div class="icon"><span class="ion-ios-calendar"></span></div>
				                <input type="text" class="form-control checkin_date" placeholder="Check In Date">
				              </div>
			              </div>
		        			</div>
		        			<div class="col-lg align-items-end">
		        				<div class="form-group">
		        					<label for="#">Check-out date</label>
		        					<div class="form-field">
		          					<div class="icon"><span class="ion-ios-calendar"></span></div>
				                <input type="text" class="form-control checkout_date" placeholder="Check Out Date">
				              </div>
			              </div>
		        			</div>
		        			<div class="col-lg align-items-end">
		        				<div class="form-group">
		        					<label for="#">Price Limit</label>
		        					<div class="form-field">
		          					<div class="select-wrap">
		                      <div class="icon"><span class="ion-ios-arrow-down"></span></div>
		                      <select name="" id="" class="form-control">
		                        <option value="">$5,000</option>
		                        <option value="">$10,000</option>
		                        <option value="">$50,000</option>
		                        <option value="">$100,000</option>
		                        <option value="">$200,000</option>
		                        <option value="">$300,000</option>
		                        <option value="">$400,000</option>
		                        <option value="">$500,000</option>
		                        <option value="">$600,000</option>
		                        <option value="">$700,000</option>
		                        <option value="">$800,000</option>
		                        <option value="">$900,000</option>
		                        <option value="">$1,000,000</option>
		                        <option value="">$2,000,000</option>
		                      </select>
		                    </div>
				              </div>
			              </div>
		        			</div>
		        			<div class="col-lg align-self-end">
		        				<div class="form-group">
		        					<div class="form-field">
				                <input type="submit" value="Search" class="form-control btn btn-primary">
				              </div>
			              </div>
		        			</div>
		        		</div>
		        	</form>
		        </div>
					</div>
	    	</div>
	    </div>
    </section> --}}


<x-travelcomponents.available-bookings :trips="$trips"/>
<x-travelcomponents.footer />
