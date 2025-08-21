<!-- JS Global Compulsory -->
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

<script>
  // SweetAlert2 - Flash messages
  (function () {
    const flash = {
      success: @json(session('success')), 
      error: @json(session('error')), 
      warning: @json(session('warning')), 
      info: @json(session('info'))
    };
    const type = Object.keys(flash).find(k => !!flash[k]);
    if (type && window.Swal) {
      Swal.fire({
        icon: type,
        title: flash[type],
        timer: 2500,
        showConfirmButton: false
      });
    }
  })();

  // SweetAlert2 - Confirmations for forms and links
  (function () {
    function attachConfirm(selector, getMessage) {
      document.querySelectorAll(selector).forEach(function (el) {
        if (el.dataset.confirmBound) return;
        el.dataset.confirmBound = '1';
        el.addEventListener('click', function (e) {
          const message = getMessage(el) || '¿Estás seguro que deseas continuar?';
          if (!window.Swal) return; // fallback silently
          e.preventDefault();
          Swal.fire({
            icon: 'question',
            title: message,
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
          }).then(function (result) {
            if (result.isConfirmed) {
              if (el.tagName === 'A' && el.getAttribute('href')) {
                window.location.href = el.getAttribute('href');
              } else if (el.closest('form')) {
                el.closest('form').submit();
              }
            }
          });
        });
      });
    }

    // Forms with data-confirm attribute
    document.querySelectorAll('form[data-confirm]')
      .forEach(function (form) {
        if (form.dataset.confirmBound) return;
        form.dataset.confirmBound = '1';
        form.addEventListener('submit', function (e) {
          if (!window.Swal) return; // fallback
          e.preventDefault();
          const message = form.getAttribute('data-confirm') || '¿Confirmas esta acción?';
          Swal.fire({
            icon: 'warning',
            title: message,
            showCancelButton: true,
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar'
          }).then(function (result) {
            if (result.isConfirmed) form.submit();
          });
        });
      });

    // Any element with data-confirm-message triggers confirmation on click
    attachConfirm('[data-confirm-message]', function (el) { return el.getAttribute('data-confirm-message'); });
    // Common destructive buttons by class
    attachConfirm('.btn-delete, .btn-danger[data-confirm]', function (el) { return el.getAttribute('data-confirm') || '¿Eliminar este registro?'; });
  })();
</script>

@yield('scripts') 