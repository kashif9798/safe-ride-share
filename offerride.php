<?php
    include('includes/DBconn.php'); 
    include('includes/header.php'); 
    if (!isset($_SESSION["user_email"])) {
      echo "<script>window.open('login.php','_self');</script>";	
    }

    if (isset($_POST["submit_offer"])) {
      $r_leaving        = $_POST["leaving_rider"];
      $r_going          = $_POST["going_rider"];
      $r_date           = $_POST["date_rider"];
      $r_time           = $_POST["time_rider"];
      $r_seat           = $_POST["number_rider"];

      $insert_ride = "INSERT INTO rides (rider_id,start,destination,date_ride,time_ride,seats) VALUES ('$id','$r_leaving','$r_going','$r_date','$r_time','$r_seat')";

      $run_ride = mysqli_query($con,$insert_ride);
  
      if ($run_ride) {
        echo "<script>alert('Ride successfully created')</script>";
        echo "<script>window.open('account.php?rides','_self');</script>";	
      }else{
        echo "<script>alert('Ride creation failed, Please contact administrator')</script>";
      } 
    }
    


?>

<div class="container mt-5">
    <div class="row">
        <div class="offset-sm-0 col-sm-12 col-md-5">
            <h1 class="text-center mb-5">Offer a ride</h1>
            <form method="post">
                <div class="form-group">
                    <input type="text" class="form-control find-ride-input w-100" name="leaving_rider" id="leavingfrom" aria-describedby="Where are you leaving from" placeholder="Leaving From" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control find-ride-input w-100" name="going_rider" id="goingto" aria-describedby="Where are you going to" placeholder="Going To" required>
                </div>

                <div class="form-group">
                    <label for="departure-ride" class="ride-label">Date & Time of Departure</label>
                    <div class="row ml-1">
                        <input type="date" class="form-control col-5 passenger_date" name="date_rider" id="departure-ride" value="1" required/>
                        <input type="time" class="form-control offset-1 col-5 passenger_time" name="time_rider" id="departure-ride" value="1" required/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="passenger-count" class="ride-label">Number of seats to offer</label>
                    <div class="number text-center mt-2">
                        <span class="minus"><i class="fas fa-minus"></i></span>
                        <input type="number" class="passenger" name="number_rider" id="passenger-count" value="1" required/>
                        <span class="plus"><i class="fas fa-plus"></i></span>
                    </div>
                </div>

                <button type="submit" name="submit_offer" class="btn btn-primary btn-block find-ride-btn mt-5">Lets Go Together!</button>
            </form>
        </div>
        <div class="offset-sm-0 col-sm-12 offset-md-1 col-md-6 mt-5 mt-md-0" id="map"></div>
        
    </div>
</div>


<!-- maps links below two -->
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTbYZF_kDxKNopcvej6oh-eVs1z9Xq2J0&callback=initMap&libraries=places&v=weekly"
    defer
></script>

<script>
      "use strict";
      function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
          mapTypeControl: false,
          center: {
            lat: 33.6844,
            lng: 73.0479
          },
          zoom: 13
        });
        new AutocompleteDirectionsHandler(map);
      }

      class AutocompleteDirectionsHandler {
        constructor(map) {
          this.map = map;
          this.originPlaceId = "";
          this.destinationPlaceId = "";
          this.travelMode = google.maps.TravelMode.WALKING;
          this.directionsService = new google.maps.DirectionsService();
          this.directionsRenderer = new google.maps.DirectionsRenderer();
          this.directionsRenderer.setMap(map);
          const originInput = document.getElementById("leavingfrom");
          const destinationInput = document.getElementById("goingto");
          const originAutocomplete = new google.maps.places.Autocomplete(
            originInput
          ); // Specify just the place data fields that you need.

          originAutocomplete.setFields(["place_id"]);
          const destinationAutocomplete = new google.maps.places.Autocomplete(
            destinationInput
          ); // Specify just the place data fields that you need.

          destinationAutocomplete.setFields(["place_id"]);

          this.setupPlaceChangedListener(originAutocomplete, "ORIG");
          this.setupPlaceChangedListener(destinationAutocomplete, "DEST");
        }

        setupPlaceChangedListener(autocomplete, mode) {
          autocomplete.bindTo("bounds", this.map);
          autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();

            if (!place.place_id) {
              window.alert("Please select an option from the dropdown list.");
              return;
            }

            if (mode === "ORIG") {
              this.originPlaceId = place.place_id;
            } else {
              this.destinationPlaceId = place.place_id;
            }

            this.route();
          });
        }

        route() {
          if (!this.originPlaceId || !this.destinationPlaceId) {
            return;
          }

          const me = this;
          this.directionsService.route(
            {
              origin: {
                placeId: this.originPlaceId
              },
              destination: {
                placeId: this.destinationPlaceId
              },
              travelMode: 'DRIVING'
            },
            (response, status) => {
              if (status === "OK") {
                me.directionsRenderer.setDirections(response);
              } else {
                window.alert("Directions request failed due to " + status);
              }
            }
          );
        }
      }
    </script>
<?php
    include('includes/footer.php'); 
?>
