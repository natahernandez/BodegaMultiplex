<!-- JS Global Compulsory -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS Implementing Plugins -->
<script src="{{ asset('vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside.min.js') }}"></script>
<script src="{{ asset('js/hs.core.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- JS Front -->
<script src="{{ asset('js/theme-custom.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>

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
  document.addEventListener('DOMContentLoaded', function() {
    // INITIALIZATION OF NAVBAR VERTICAL ASIDE
    if (typeof HSSideNav !== 'undefined') {
      new HSSideNav('.js-navbar-vertical-aside').init();
    }

    // INITIALIZATION OF NAV SCROLLER
    if (typeof HsNavScroller !== 'undefined') {
      new HsNavScroller('.js-nav-scroller', {
        delay: 400
      });
    }

    // INITIALIZATION OF FORM SEARCH
    if (typeof HSFormSearch !== 'undefined') {
      new HSFormSearch('.js-form-search');
    }

    // INITIALIZATION OF BOOTSTRAP DROPDOWN - Using native Bootstrap 5
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
      return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // INITIALIZATION OF BOOTSTRAP TOOLTIPS
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // INITIALIZATION OF NAVBAR TOGGLE
    const navbarToggler = document.querySelector('.js-navbar-vertical-aside-toggle-invoker');
    if (navbarToggler) {
      navbarToggler.addEventListener('click', function() {
        const navbar = document.querySelector('.js-navbar-vertical-aside');
        if (navbar) {
          navbar.classList.toggle('navbar-vertical-aside-mini');
        }
      });
    }
  });
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