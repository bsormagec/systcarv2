<!DOCTYPE html>
<html lang="en">

<head>
  @include('admin.partials.head')
</head>

<body>

  <!-- ======= Header ======= -->
  @include('admin.partials.header')
  <!-- End Header -->

  @yield('content')

  <!-- ======= Footer ======= -->
  @include('admin.partials.footer')
  <!-- End Footer -->

  <a href="#" class="back-to-top"><i class="ri-arrow-up-line"></i></a>
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="admin/vendor/jquery/jquery.min.js"></script>
  <script src="admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="admin/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="admin/vendor/php-email-form/validate.js"></script>
  <script src="admin/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="admin/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="admin/vendor/venobox/venobox.min.js"></script>
  <script src="admin/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="admin/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="admin/js/main.js"></script>

</body>

</html>