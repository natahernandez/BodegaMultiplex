<!-- JS Global Compulsory -->
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

<!-- JS Implementing Plugins -->
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/hs-form-search/dist/hs-form-search.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/hs-nav-scroller/dist/hs-nav-scroller.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jsvectormap/dist/js/jsvectormap.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jsvectormap/dist/maps/world.js') }}"></script>

<!-- JS Front -->
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/js/theme.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/js/hs.theme-appearance-charts.js') }}"></script>

<!-- JS Plugins Init. -->
<script>
  $(document).on('ready', function () {
    // INITIALIZATION OF DATERANGEPICKER
    $('.js-daterangepicker').daterangepicker();

    var start = moment();
    var end = moment();

    function cb(start, end) {
      $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
    }

    $('#js-daterangepicker-predefined').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      }
    }, cb);

    cb(start, end);
  });
</script>

<script>
  (function() {
    window.onload = function () {
      // INITIALIZATION OF NAVBAR VERTICAL ASIDE
      new HSSideNav('.js-navbar-vertical-aside').init()

      // INITIALIZATION OF NAV SCROLLER
      new HsNavScroller('.js-nav-scroller', {
        delay: 400
      })

      // INITIALIZATION OF FORM SEARCH
      new HSFormSearch('.js-form-search')

      // INITIALIZATION OF BOOTSTRAP DROPDOWN
      HSBsDropdown.init()
    }
  })()
</script>

@yield('scripts') 